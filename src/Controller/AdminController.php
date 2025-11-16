<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ODM\MongoDB\DocumentManager;
use DateTime;
use App\Service\ItineraireService;

final class AdminController extends AbstractController
{

    #[Route('/statistiques', name: 'app_statistiques')]
    public function statistiques(
    ): Response
    {

        /** @var User $user */
        $user = $this->getUser();

        return $this->render('statistique/index.html.twig', [
            'controller_name' => 'AdminController',
            'user' => $user,
        ]);

    }

    #[Route('/admin/covoiturages', name: 'app_covoiturages_stat')]
    public function coviturages(
        ItineraireService $itineraireService,
    ): JsonResponse
    {

        $nbJours = 20;
        $nbJoursAvant = -10;
        
        $covoiturages = $itineraireService->getCovoituragesByJour($nbJours, $nbJoursAvant);
        $credits = $itineraireService->getCovoituragesByJour(50000, 25000);

        $labels = [];
        $data1 = [];
        $data2 = [0, 0, 0, 0, 0, 0, 0];
        
        $indice = 0;

        for ($i = -$nbJoursAvant; $i < $nbJours - $nbJoursAvant; $i++) {

            $date = new Datetime();
            $date->modify(''.$i.' day');
            $labels[] = $date->format('d/m/Y');

            if ($indice < count($covoiturages) && $date->format('Y-m-d') === $covoiturages[$indice]['date_depart']->format('Y-m-d')) {
                $data1[] = $covoiturages[$indice]['covoiturages'];
                // $data2[] = $covoiturages[$indice]['covoiturages'] * 2;
                $indice++;
            } else {
                $data1[] = 0;
            }

        }

        $indice = 0;

        while ($indice < count($credits)) {

            $data2[$credits[$indice]['date_depart']->format('w')] += $credits[$indice]['covoiturages'];

            $indice++;

        }

        return $this->json([
            'labels1' => $labels,
            'data1' => $data1,
            'labels2' => ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
            'data2' => $data2
        ]);
    
    }

}
