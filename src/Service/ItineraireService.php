<?php

namespace App\Service;

use App\Entity\Covoiturage;
use App\Repository\CovoiturageRepository;
use App\Repository\VilleRepository;
use App\Repository\ParticipationRepository;
use App\Entity\User;
use App\Entity\Ville;
use App\Entity\Participation;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class ItineraireService 
{

    public function __construct(
        private CovoiturageRepository $covoiturageRepository,
        private VilleRepository $villeRepository,
        private ParticipationRepository $participationRepository,
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
        ?User $chauffeur
    ): array
    {

        $covoiturages = $this->covoiturageRepository->findCovoiturageByLieuxAndDate(
            $depart, 
            $arrivee, 
            $date_depart, 
            $ecologique, 
            $prix_personne, 
            $duree_voyage === null ? '00:00' : $duree_voyage->format('H:i'), 
            $note_chauffeur === null ? -1 : $note_chauffeur,
            $chauffeur
        );

        return $covoiturages;

    }

    public function gatParticipations(
        User $passager
    ): array
    {

        $covoiturages = $this->covoiturageRepository->findCovoituragesByParticipation($passager);

        return $covoiturages;

    }

    public function getCovoituragesByJour(int $nbJ, int $nbJAvant) 
    {

        $date1 = new Datetime();
        $date1->modify(''.(-$nbJAvant).' day');
        $date2 = new Datetime();
        $date2->modify(''.($nbJ - $nbJAvant).' day');

        return $this->covoiturageRepository->findCovoituragesByJour($date1, $date2);

    }

}