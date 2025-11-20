<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CovoiturageRepository;
use App\Repository\ParticipationRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\Avis;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ValidationFormType;

final class ValidationController extends AbstractController
{
    #[Route('/validation/{id}', name: 'app_valider_covoiturage')]
    public function index(
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

        $form = $this->createForm(ValidationFormType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $participation->setStatut($form->get('validation')->getData());
            $participation->setCommentaire($form->get('commentaire')->getData());

            $chauffeur = $covoiturage->getVoiture()->getChauffeur();
            
            if ($form->get('validation')->getData() === 'Ok') {
                $chauffeur->setCredits($chauffeur->getCredits() + $covoiturage->getPrixPersonne());
            }

            $avis = new Avis();
            $avis->setChauffeur($chauffeur->getId());
            $avis->setPseudo($user->getPseudo());
            $avis->setAvis($form->get('avis')->getData());
            $avis->setModere(false);
            $avis->setValide(false);
            $avis->setNote($form->get('note')->getData());

            $documentManager->persist($avis);   
            $documentManager->flush();

            $note = $documentManager->getRepository(Avis::class)->findNoteByChauffeur($chauffeur);
            $chauffeur->setNote($note[0]['note']);

            $entityManager->persist($chauffeur);
            $entityManager->persist($participation);
            $entityManager->flush();

            return $this->redirectToRoute('app_participations');

        }

        return $this->render('validation/index.html.twig', [
            'controller_name' => 'ValidationController',
            'validationForm' => $form,
            'user' => $user,
        ]);

    }
    
    #[Route('/avis/d/{id}', name: 'app_invalid_avis')]
    public function delete(string $id, DocumentManager $documentManager): Response
    {

        $avis = $documentManager->getRepository(Avis::class)->find($id);
        
        $avis->setModere(true);
        $avis->setValide(false);

        $documentManager->persist($avis);
        $documentManager->flush();

        return $this->redirectToRoute('app_avis');

    }

    #[Route('/avis', name: 'app_avis', methods: ['GET'])]
    public function avis(DocumentManager $documentManager): Response
    {

        $avis = $documentManager->getRepository(Avis::class)->findBy([
            'modere' => false
        ]);

        /** @var User $user */
        $user = $this->getUser();

        return $this->render('avis/list.html.twig', [
            'controller_name' => 'ValidationController',
            'user' => $user,
            'avis' => $avis
        ]);

    }

    #[Route('/contentieux', name: 'app_contentieux', methods: ['GET'])]
    public function contentieux(
        ParticipationRepository $participationRepository, 
    ): Response
    {

        $kos = $participationRepository->findBy([
            'statut' => 'Ko'
        ]);

        /** @var User $user */
        $user = $this->getUser();

        return $this->render('contentieux/list.html.twig', [
            'controller_name' => 'ValidationController',
            'user' => $user,
            'participations' => $kos
        ]);

    }

    #[Route('/contentieux/u/{id}', name: 'app_regle_contentieux')]
    public function regle(
        int $id, 
        ParticipationRepository $participationRepository,
        EntityManagerInterface $entityManager,
    ): Response
    {

        $contentieux = $participationRepository->find($id);
        
        $contentieux->setStatut('Ok');
        $covoiturage = $contentieux->getCovoiturage();
        $chauffeur = $covoiturage->getVoiture()->getChauffeur();

        $chauffeur->setCredits($chauffeur->getCredits() + $covoiturage->getPrixPersonne() - 2);
        $entityManager->persist($chauffeur);

        $entityManager->persist($contentieux);
        $entityManager->flush();

        return $this->redirectToRoute('app_contentieux');

    }
    
    #[Route('/contentieux/r/{id}', name: 'app_rembourser_contentieux')]
    public function rembourser(
        int $id, 
        ParticipationRepository $participationRepository,
        EntityManagerInterface $entityManager,
    ): Response
    {

        $contentieux = $participationRepository->find($id);
        $covoiturage = $contentieux->getCovoiturage();
        $chauffeur = $covoiturage->getVoiture()->getChauffeur();
        $passager = $contentieux->getPassager();

        $contentieux->setStatut('Ko réglé');

        $chauffeur->setCredits($chauffeur->getCredits() - 2);
        $entityManager->persist($chauffeur);
        $passager->setCredits($passager->getCredits() + $covoiturage->getPrixPersonne());
        $entityManager->persist($passager);

        $entityManager->persist($contentieux);
        $entityManager->flush();

        return $this->redirectToRoute('app_contentieux');

    }
    
    #[Route('/avis/u/{id}', name: 'app_valid_avis')]
    public function edit(string $id, Request $request, DocumentManager $documentManager): Response
    {

        $avis = $documentManager->getRepository(Avis::class)->find($id);
        
        $avis->setModere(true);
        $avis->setValide(true);

        $documentManager->persist($avis);
        $documentManager->flush();

        return $this->redirectToRoute('app_avis');

    }
    
}
