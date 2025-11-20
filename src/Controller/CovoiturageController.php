<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CovoiturageRepository;
use App\Repository\ParticipationRepository;
use App\Entity\Covoiturage;
use App\Entity\Participation;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ItineraireService;
use App\Form\CovoiturageFormType;
use App\Form\FiltresFormType;
use App\Form\ItineraireFormType;
use App\Form\DetailFormType;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\Avis;

final class CovoiturageController extends AbstractController
{

    #[Route('/covoiturage/d/{id}', name: 'app_delete_covoiturage')]
    public function delete(int $id, CovoiturageRepository $covoiturageRepository, EntityManagerInterface $entityManager): Response
    {

        $covoiturage = $covoiturageRepository->find($id);

        $chauffeurCredits = 0;

        foreach($covoiturage->getParticipatione() as $participation) {
            $participation->getPassager()->setCredits($participation->getPassager()->getCredits() + $covoiturage->getPrixPersonne());
            $entityManager->persist($participation);
        }

        $entityManager->remove($covoiturage);
        $entityManager->flush();

        return $this->redirectToRoute('app_covoiturages');

    }

    #[Route('/covoiturage/s/{id}', name: 'app_annuler_covoiturage')]
    public function annuler(
        int $id, 
        CovoiturageRepository $covoiturageRepository, 
        ParticipationRepository $participationRepository, 
        EntityManagerInterface $entityManager
    ): Response
    {

        /** @var User $user */
        $user = $this->getUser();

        $covoiturage = $covoiturageRepository->find($id);
        $participation = $participationRepository->findOneBy([
            'covoiturage' => $covoiturage,
            'passager' => $user
        ]);

        $entityManager->remove($participation);
        $entityManager->flush();

        return $this->redirectToRoute('app_participations');

    }

    #[Route('/covoiturage/m/{id}', name: 'app_statut_covoiturage')]
    public function statut(
        int $id, 
        CovoiturageRepository $covoiturageRepository, 
        ParticipationRepository $participationRepository, 
        EntityManagerInterface $entityManager
    ): Response
    {

        /** @var User $user */
        $user = $this->getUser();

        $covoiturage = $covoiturageRepository->find($id);

        $statut = $covoiturage->getStatut();

        if ($statut === 'planifié') {
            $covoiturage->setStatut('démarré');
        } elseif ($statut === 'démarré') {
            $covoiturage->setStatut('arrivé');
        } elseif ($statut = 'arrivé') {
            // send mail
        }
        
        $entityManager->persist($covoiturage);
        $entityManager->flush();

        return $this->redirectToRoute('app_covoiturages');

    }

    #[Route('/covoiturage/v/{id}', name: 'app_detail_covoiturage')]
    public function detail(
        int $id, 
        Request $request, 
        EntityManagerInterface $entityManager,
        CovoiturageRepository $covoiturageRepository, 
        ParticipationRepository $participationRepository, 
        DocumentManager $documentManager,
    ): Response
    {

        /** @var User $user */
        $user = $this->getUser();
        
        $covoiturage = $covoiturageRepository->find($id);
        
        $participation = $participationRepository->findOneBy([
            'passager' => $user,
            'covoiturage' => $covoiturage
        ]);

        $avis = $documentManager->getRepository(Avis::class)->findBy([
            'chauffeur' => $covoiturage->getVoiture()->getChauffeur()->getId()
        ]);
        

        $form = $this->createForm(DetailFormType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->denyAccessUnlessGranted('ROLE_USER');
    
            $participation = new Participation();    
            $participation->setPassager($user);
            $participation->setCovoiturage($covoiturage);
            $participation->setStatut('inscrit');

            $user->setCredits($user->getCredits() - $covoiturage->getPrixpersonne());            
            
            $covoiturage->setNbPlace($covoiturage->getNbPlace() - 1);

            $entityManager->persist($participation);
            $entityManager->persist($user);
            $entityManager->persist($covoiturage);
            $entityManager->flush();

            return $this->redirectToRoute('app_participations');

        }

        return $this->render('covoiturage/detail.html.twig', [
            'controller_name' => 'CovoiturageController',
            'detailForm' => $form,
            'covoiturage' => $covoiturage,
            'user' => $user,
            'participation' => $participation,
            'avis' => $avis
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
            'user' => $user,
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
            $covoiturage->setStatut('planifié');

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
            'user' => $user,
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
            'user' => $user,
            'covoiturages' => $covoiturages,
            'nouveau' => true,
            'villes' => $villes,
            'type' => 'Mes propositions de covoiturages',
            'date_depart' => null
        ]);

    }

    #[Route('/participations', name: 'app_participations')]
    public function participations(
        Request $request, 
        ItineraireService $itineraireService, 
    ): Response
    {

        $villes = $itineraireService->getVilles();

        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(FiltresFormType::class, null, [
            'ecologique' => false
        ]);
        $form->handleRequest($request);
        
        $covoiturages = $itineraireService->gatParticipations($user);

        return $this->render('covoiturage/list.html.twig', [
            'covoiturageForm' => $form,
            'controller_name' => 'CovoiturageController',
            'user' => $user,
            'covoiturages' => $covoiturages,
            'nouveau' => false,
            'villes' => $villes,
            'type' => 'Mes participations à des covoiturages',
            'date_depart' => null
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
        
        /** @var User $user */
        $user = $this->getUser();

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
            $user
        );

        $villes = $itineraireService->getVilles();

        return $this->render('covoiturage/list.html.twig', [
            'covoiturageForm' => $form,
            'controller_name' => 'CovoiturageController',
            'user' => $user,
            'covoiturages' => $covoiturages,
            'nouveau' => false,
            'villes' => $villes,
            'type' => 'Covoiturages',
            'date_depart' => $date_filtre
        ]);

    }

}
