<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CovoiturageRepository;
use App\Form\CovoiturageFormType;
use App\Form\FiltresFormType;
use App\Entity\Covoiturage;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ItineraireService;
use App\Form\ItineraireFormType;

final class CovoiturageController extends AbstractController
{

    #[Route('/covoiturage/d/{id}', name: 'app_delete_covoiturage')]
    public function delete(int $id, CovoiturageRepository $covoiturageRepository, EntityManagerInterface $entityManager): Response
    {

        $covoiturage = $covoiturageRepository->find($id);
        
        $entityManager->remove($covoiturage);
        $entityManager->flush();

        return $this->redirectToRoute('app_covoiturages');

    }

    #[Route('/covoiturage/v/{id}', name: 'app_detail_covoiturage')]
    public function detail(
        int $id, 
        Request $request, 
        CovoiturageRepository $covoiturageRepository, 
        EntityManagerInterface $entityManager
        ): Response
    {

        $covoiturage = $covoiturageRepository->find($id);
        
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('covoiturage/detail.html.twig', [
            'controller_name' => 'CovoiturageController',
            'covoiturage' => $covoiturage,
            'utilisateur' => $user,
        ]);


    }

    #[Route('/covoiturage/u/{id}', name: 'app_update_covoiturage')]
    public function edit(
        int $id, 
        Request $request, 
        CovoiturageRepository $covoiturageRepository, 
        ItineraireService $itineraireService, 
        EntityManagerInterface $entityManager
    ): Response
    {

        $covoiturage = $covoiturageRepository->find($id);
        
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(CovoiturageFormType::class, $covoiturage, [
            'depart' => $covoiturage->getLieuDepart()->getLibelle(),
            'arrivee' => $covoiturage->getLieuArrivee()->getLibelle(),
            'chauffeur' => $user
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($covoiturage);
            $entityManager->flush();

            return $this->redirectToRoute('app_covoiturages');

        }

        $villes = $itineraireService->getVilles();

        return $this->render('covoiturage/index.html.twig', [
            'controller_name' => 'CovoiturageController',
            'covoiturageForm' => $form,
            'utilisateur' => $user,
            'action' => 'Modifier',
            'villes' => $villes
        ]);

    }
    
    #[Route('/covoiturage/c', name: 'app_create_covoiturage')]
    public function new(
        Request $request, 
        ItineraireService $itineraireService, 
        EntityManagerInterface $entityManager,
    ): Response
    {
        
        $covoiturage = new Covoiturage();
        
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(CovoiturageFormType::class, $covoiturage, ['chauffeur' => $user]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $lieu_depart = $form->get('lieu_depart')->getData();
            $lieu_arrivee = $form->get('lieu_arrivee')->getData();

            $covoiturage->setLieuDepart($itineraireService->getOrCreateVille($lieu_depart));
            $covoiturage->setLieuArrivee($itineraireService->getOrCreateVille($lieu_arrivee));

            $entityManager->persist($covoiturage);
            $entityManager->flush();

            return $this->redirectToRoute('app_covoiturages', [
                'lieu_depart' => $lieu_depart,
                'lieu_arrivee' => $lieu_arrivee
            ]);

        }

        $villes = $itineraireService->getVilles();

        return $this->render('covoiturage/index.html.twig', [
            'controller_name' => 'CovoiturageController',
            'covoiturageForm' => $form,
            'utilisateur' => $user,
            'action' => 'Créer',
            'villes' => $villes
        ]);
        
    }

    #[Route('/covoiturages', name: 'app_covoiturages')]
    public function covoiturages(
        Request $request, 
        ItineraireService $itineraireService, 
    ): Response
    {

        $villes = $itineraireService->getVilles();

        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(ItineraireFormType::class, null);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

        }

        $covoiturages = $itineraireService->getCovoituragesByChauffeur($user);

        return $this->render('covoiturage/list.html.twig', [
            'covoiturageForm' => $form,
            'controller_name' => 'CovoiturageController',
            'utilisateur' => $user,
            'covoiturages' => $covoiturages,
            'nouveau' => true,
            'villes' => $villes
        ]);

    }

    #[Route('/itineraires', name: 'app_itineraires')]
    public function itineraires(
        Request $request, 
        ItineraireService $itineraireService, 
    ): Response
    {

        $covoiturages = [];

        $form = $this->createForm(FiltresFormType::class, null, [
            'ecologique' => false
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

        }    

        $lieu_depart = $itineraireService->getVille($form->get('lieu_depart')->getData());
        $lieu_arrivee = $itineraireService->getVille($form->get('lieu_arrivee')->getData());
        $date_filtre = $form->get('date_filtre')->getData();
        $ecologique = $form->get('ecologique')->getData();
        $prix_personne = $form->get('prix_personne')->getData();
        $duree_voyage = $form->get('duree_voyage')->getData();
        $note_chauffeur = $form->get('note_chauffeur')->getData();
    
        $covoiturages = $itineraireService->getItineraires(
            $lieu_depart, 
            $lieu_arrivee, 
            $date_filtre === null ? null : date_format($date_filtre, 'Y-m-d'),
            $ecologique,
            $prix_personne === null ? 0 : $prix_personne,
            $duree_voyage,
            $note_chauffeur,
        );

        $villes = $itineraireService->getVilles();

        /** @var User $user */
        $user = $this->getUser();

        return $this->render('covoiturage/list.html.twig', [
            'covoiturageForm' => $form,
            'controller_name' => 'CovoiturageController',
            'utilisateur' => $user,
            'covoiturages' => $covoiturages,
            'nouveau' => false,
            'villes' => $villes
        ]);

    }

}
