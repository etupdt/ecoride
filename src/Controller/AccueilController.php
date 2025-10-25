<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use App\Service\ItineraireService;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;

final class AccueilController extends AbstractController
{
    #[Route('/', name: 'racine')]
    #[Route('/accueil', name: 'accueil')]
    public function index(#[MapQueryParameter] ?string $depart='',#[MapQueryParameter] ?string $arrivee='', ItineraireService $itineraireService): Response
    {

        error_log('==========================> /');

        error_log('==========================> supports'.$depart.'   '.$arrivee);

        $villes = $itineraireService->getVilles();

        /** @var User $user */
        $user = $this->getUser();

        // $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'utilisateur' => $user,
            'depart' => $depart,
            'arrivee' => $arrivee,
            'villes' => $villes
        ]);

    }

}
