<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\DonneesFormType;
use Doctrine\ORM\EntityManagerInterface;

final class DonneesController extends AbstractController
{
    #[Route('/donnees', name: 'app_donnees')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {

        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(DonneesFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('accueil');

        }

        return $this->render('donnees/index.html.twig', [
            'controller_name' => 'DonneesController',
            'email' => isSet($user) ? $user->getEmail() : '',
            'userForm' => $form,
        ]);
    
    }

}
