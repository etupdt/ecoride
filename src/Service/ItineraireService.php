<?php

namespace App\Service;

use App\Repository\CovoiturageRepository;
use App\Repository\VilleRepository;
use App\Entity\User;
use App\Entity\Ville;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class ItineraireService 
{

    public function __construct(
        private CovoiturageRepository $covoiturageRepository,
        private VilleRepository $villeRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function getVille(?string $libelle): ?Ville
    {

        $ville = null;

        if ($libelle !== null) {
            $ville = $this->villeRepository->findOneBy(['libelle' => $libelle]);
        }

        return $ville;

    }

    public function getOrCreateVille(string $libelle): Ville
    {

        $ville = $this->getVille($libelle);

        if ($ville === null) {
            $ville = new Ville();
            $ville->setLibelle($libelle);
            $this->entityManager->persist($ville);
            $this->entityManager->flush();
        }

        return $ville;

    }

    public function getVilles(): array
    {

        $villes = $this->villeRepository->findVilles(10);

        return $villes;

    }

    public function getCovoituragesByChauffeur(User $chauffeur): array
    {

        $covoiturages = $this->covoiturageRepository->findCovoiturageByChauffeur($chauffeur);

        return $covoiturages;

    }

    public function getItineraires(
        ?Ville $depart, 
        ?Ville $arrivee, 
        ?string $date_depart,
        bool $ecologique,
        ?int $prix_personne,
        ?DateTime $duree_voyage,
        ?int $note_chauffeur,
    ): array
    {

        $covoiturages = $this->covoiturageRepository->findCovoiturageByLieuxAndDate(
            $depart, 
            $arrivee, 
            $date_depart, 
            $ecologique, 
            $prix_personne, 
            $duree_voyage === null ? '00:00' : $duree_voyage->format('H:i'), 
            $note_chauffeur === null ? -1 : $note_chauffeur
        );

        return $covoiturages;

    }

}