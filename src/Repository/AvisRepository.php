<?php

namespace App\Repository;

use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use App\Document\Avis;
use App\Entity\User;

class AvisRepository extends DocumentRepository
{

    public function findNoteByChauffeur(User $chauffeur)
    {

        $avis = new Avis();
        $avis->setChauffeur($chauffeur->getId());

        return $this->createAggregationBuilder(Avis::class)
            ->match()
                ->field('chauffeur')
                ->equals($chauffeur->getId())
                // ->equals($chauffeur->getId())
            ->group()
                ->field('id')
                ->expression('$chauffeur')
                ->field('note')
                ->avg('$note')
            ->getAggregation()->execute()->toArray();

    }

}