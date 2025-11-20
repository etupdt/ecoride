<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use App\Entity\Covoiturage;
use App\Service\ItineraireService;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use App\Form\ItineraireFormType;

final class AccueilController extends AbstractController
{
    
    #[Route('/', name: 'racine')]
    #[Route('/accueil', name: 'accueil')]
    public function index(
        Request $request, 
        ItineraireService $itineraireService, 
    ): Response
    {

        $covoiturage = new Covoiturage();

        $form = $this->createForm(ItineraireFormType::class, $covoiturage, [
            'action' => $this->generateUrl('app_itineraires'),
            'method' => 'POST'
        ]);
        $form->handleRequest($request);
        
        // if ($form->isSubmitted() && $form->isValid()) {

        //     $lieu_depart = $form->get('lieu_depart')->getData();
        //     $lieu_arrivee = $form->get('lieu_arrivee')->getData();

        //     return $this->redirectToRoute('app_itineraires', ['request' => $request]);

        // }

        $villes = $itineraireService->getVilles();

        /** @var User $user */
        $user = $this->getUser();

        // $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'user' => $user,
            'covoiturageForm' => $form,
            'villes' => $villes
        ]);

    }

}
