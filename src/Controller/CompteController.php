<?php

namespace App\Controller;

use App\Form\CompteFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class CompteController extends AbstractController
{
    #[Route('/compte', name: 'app_compte')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {

        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(CompteFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $fichier = $form['photo']->getData();
            $nomFichier = uniqid().'.'.$fichier->guessExtension();
            $fichier->move("photos/", $nomFichier);
            $user->setPhoto($nomFichier);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('accueil');

        }

        return $this->render('compte/index.html.twig', [
            'controller_name' => 'CompteController',
            'utilisateur' => $user,
            'userForm' => $form,
        ]);

    }
    
}
