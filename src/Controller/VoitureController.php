<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\VoitureFormType;
use App\Repository\VoitureRepository;
use App\Entity\Voiture;
use Doctrine\ORM\EntityManagerInterface;

final class VoitureController extends AbstractController
{

    #[Route('/voiture/d/{id}', name: 'app_delete_voiture')]
    public function delete(int $id, VoitureRepository $voitureRepository, EntityManagerInterface $entityManager): Response
    {

        $voiture = $voitureRepository->find($id);
        
        $entityManager->remove($voiture);
        $entityManager->flush();

        return $this->redirectToRoute('app_voitures');

    }

    #[Route('/voitures', name: 'app_voitures')]
    public function voitures(Request $request, VoitureRepository $voitureRepository): Response
    {

        /** @var User $user */
        $user = $this->getUser();

        $voitures = $voitureRepository->findBy(['chauffeur' => $user]);

        return $this->render('voiture/list.html.twig', [
            'controller_name' => 'VoitureController',
            'user' => $user,
            'voitures' => $voitures
        ]);

    }

    #[Route('/voiture/u/{id}', name: 'app_update_voiture')]
    public function edit(int $id, Request $request, VoitureRepository $voitureRepository, EntityManagerInterface $entityManager): Response
    {

        $voiture = $voitureRepository->find($id);
        
        $form = $this->createForm(VoitureFormType::class, $voiture);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($voiture);
            $entityManager->flush();

            return $this->redirectToRoute('app_voitures');

        }

        /** @var User $user */
        $user = $this->getUser();

        return $this->render('voiture/index.html.twig', [
            'controller_name' => 'VoitureController',
            'voitureForm' => $form,
            'user' => $user,
            'action' => 'Modifier',
        ]);

    }
    
    #[Route('/voiture/c', name: 'app_create_voiture')]
    public function new(Request $request, VoitureRepository $voitureRepository, EntityManagerInterface $entityManager): Response
    {
        
        $voiture = new Voiture();
        
        $form = $this->createForm(VoitureFormType::class, $voiture);
        $form->handleRequest($request);
        
        /** @var User $user */
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {

            $voiture->setChauffeur($user);

            $entityManager->persist($voiture);
            $entityManager->flush();

            return $this->redirectToRoute('app_voitures');

        }

        return $this->render('voiture/index.html.twig', [
            'controller_name' => 'VoitureController',
            'voitureForm' => $form,
            'user' => $user,
            'action' => 'Créer',
        ]);
        
    }

}
