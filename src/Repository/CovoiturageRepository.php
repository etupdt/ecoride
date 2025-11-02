<?php

namespace App\Repository;

use App\Entity\Covoiturage;
use App\Entity\User;
use App\Entity\Ville;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Covoiturage>
 */
class CovoiturageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Covoiturage::class);
    }

    public function findCovoiturageByChauffeur(User $chauffeur): ?array
    {
        return $this->createQueryBuilder('c')
            ->Join('c.voiture', 'v')
            ->Where('v.chauffeur = :utilisateur')
            ->setParameter('utilisateur', $chauffeur->getId())
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function findCovoiturageByLieuxAndDate(?Ville $depart, ?Ville $arrivee, ?string $date_depart, bool $ecologique, int $prix_personne, string $duree_voyage, int $note_chauffeur): ?array
    {

        return $this->createQueryBuilder('c')

            ->Join('c.voiture', 'v')
            ->Join('v.chauffeur', 'u')
            ->Join('v.energie', 'e')

            ->Where('c.lieu_depart = :lieu_depart or :lieu_depart = 0')

            ->andWhere('c.lieu_arrivee = :lieu_arrivee or :lieu_arrivee = 0')
            ->andWhere('c.date_depart = :date_depart or :date_depart = \'0001-01-01\'')

            ->andWhere(':ecologique = false or e.ecologique = true')
            ->andWhere(':prix_personne = 0 or c.prix_personne <= :prix_personne')
            ->andWhere(':duree_voyage = \'00:00\' or (c.date_arrivee + c.heure_arrivee) - (c.date_depart + c.heure_depart) <= :duree_voyage')
            ->andWhere(':note_chauffeur = -1 or u.note <= :note_chauffeur')

            ->setParameter('lieu_depart', $depart === null ? 0 : $depart->getId())
            ->setParameter('lieu_arrivee', $arrivee === null ? 0 : $arrivee->getId())
            ->setParameter('date_depart',  $date_depart === null ? '0001-01-01' : $date_depart)

            ->setParameter('ecologique', $ecologique)
            ->setParameter('prix_personne', $prix_personne)
            ->setParameter('duree_voyage', $duree_voyage)
            ->setParameter('note_chauffeur', $note_chauffeur)

            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

    }

    //    /**
    //     * @return Covoiturage[] Returns an array of Covoiturage objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Covoiturage
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
