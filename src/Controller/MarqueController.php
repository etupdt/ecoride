<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\MarqueFormType;
use App\Repository\MarqueRepository;
use App\Entity\Marque;
use Doctrine\ORM\EntityManagerInterface;

final class MarqueController extends AbstractController
{

    #[Route('/marque/d/{id}', name: 'app_delete_marque')]
    public function delete(int $id, Request $request, MarqueRepository $marqueRepository, EntityManagerInterface $entityManager): Response
    {

        $marque = $marqueRepository->find($id);
        
        $entityManager->remove($marque);
        $entityManager->flush();

        return $this->redirectToRoute('app_marques');

    }

    #[Route('/marques', name: 'app_marques', methods: ['GET'])]
    public function marques(Request $request, MarqueRepository $marqueRepository): Response
    {

        $marques = $marqueRepository->findAll();

        /** @var User $user */
        $user = $this->getUser();

        return $this->render('marque/list.html.twig', [
            'controller_name' => 'MarqueController',
            'utilisateur' => $user,
            'marques' => $marques
        ]);

    }

    #[Route('/marque/u/{id}', name: 'app_update_marque')]
    public function edit(int $id, Request $request, MarqueRepository $marqueRepository, EntityManagerInterface $entityManager): Response
    {

        $marque = $marqueRepository->find($id);
        
        $form = $this->createForm(MarqueFormType::class, $marque);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($marque);
            $entityManager->flush();

            return $this->redirectToRoute('app_marques');

        }

        /** @var User $user */
        $user = $this->getUser();

        return $this->render('marque/index.html.twig', [
            'controller_name' => 'MarqueController',
            'marqueForm' => $form,
            'utilisateur' => $user,
            'action' => 'Modifier',
        ]);

    }
    
    #[Route('/marque/c', name: 'app_create_marque')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        
        $marque = new Marque();
        
        $form = $this->createForm(MarqueFormType::class, $marque);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($marque);
            $entityManager->flush();

            return $this->redirectToRoute('app_marques');

    }

        /** @var User $user */
        $user = $this->getUser();

        return $this->render('marque/index.html.twig', [
            'controller_name' => 'MarqueController',
            'marqueForm' => $form,
            'utilisateur' => $user,
            'action' => 'Créer',
        ]);
        
    }

}
