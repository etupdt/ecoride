<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\EventSubscriber\FormItineraireSubscriber;
use App\EventSubscriber\FormFiltresSubscriber;

class FiltresFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventSubscriber(new FormItineraireSubscriber())
            ->addEventSubscriber(new FormFiltresSubscriber())
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {


        $resolver->setDefaults([
            'ecologique' => false,
            'prix_personne' => 0,
            'duree_voyage' => null,
            'note_chauffeur' => 0,
        ]);
    }
}
