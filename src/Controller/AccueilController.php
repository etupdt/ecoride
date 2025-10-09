<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;

final class AccueilController extends AbstractController
{
    #[Route('/', name: 'racine')]
    #[Route('/accueil', name: 'accueil')]
    public function index(): Response
    {

        /** @var User $user */
        $user = $this->getUser();

        // $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'user' => $user
        ]);
    }
}
