<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EnergieFormType;
use App\Repository\EnergieRepository;
use App\Entity\Energie;
use Doctrine\ORM\EntityManagerInterface;

final class EnergieController extends AbstractController
{


    #[Route('/energie/d/{id}', name: 'app_delete_energie')]
    public function delete(int $id, Request $request, EnergieRepository $energieRepository, EntityManagerInterface $entityManager): Response
    {

        $energie = $energieRepository->find($id);
        
        $entityManager->remove($energie);
        $entityManager->flush();

        return $this->redirectToRoute('app_energies');

    }

    #[Route('/energies', name: 'app_energies', methods: ['GET'])]
    public function energies(Request $request, EnergieRepository $energieRepository): Response
    {

        $energies = $energieRepository->findAll();

        /** @var User $user */
        $user = $this->getUser();

        return $this->render('energie/list.html.twig', [
            'controller_name' => 'EnergieController',
            'utilisateur' => $user,
            'energies' => $energies
        ]);

    }

    #[Route('/energie/u/{id}', name: 'app_update_energie')]
    public function edit(int $id, Request $request, EnergieRepository $energieRepository, EntityManagerInterface $entityManager): Response
    {

        $energie = $energieRepository->find($id);
        
        $form = $this->createForm(EnergieFormType::class, $energie);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($energie);
            $entityManager->flush();

            return $this->redirectToRoute('app_energies');

        }

        /** @var User $user */
        $user = $this->getUser();

        return $this->render('energie/index.html.twig', [
            'controller_name' => 'EnergieController',
            'energieForm' => $form,
            'utilisateur' => $user,
            'action' => 'Modifier',
        ]);

    }
    
    #[Route('/energie/c', name: 'app_create_energie')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        
        $energie = new Energie();
        
        $form = $this->createForm(EnergieFormType::class, $energie);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($energie);
            $entityManager->flush();

            return $this->redirectToRoute('app_energies');

    }

        /** @var User $user */
        $user = $this->getUser();

        return $this->render('energie/index.html.twig', [
            'controller_name' => 'EnergieController',
            'energieForm' => $form,
            'utilisateur' => $user,
            'action' => 'Créer',
        ]);
        
    }

}
