<?php

namespace App\Form;

use App\Entity\Covoiturage;
use App\Entity\Ville;
use App\Entity\Voiture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DetailFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('date_depart')
            // ->add('heure_depart')
            // ->add('date_arrivee')
            // ->add('heure_arrivee')
            // ->add('statut')
            // ->add('nb_place')
            // ->add('prix_personne')
            // ->add('lieu_depart', EntityType::class, [
            //     'class' => Ville::class,
            //     'choice_label' => 'id',
            // ])
            // ->add('lieu_arrivee', EntityType::class, [
            //     'class' => Ville::class,
            //     'choice_label' => 'id',
            // ])
            // ->add('voiture', EntityType::class, [
            //     'class' => Voiture::class,
            //     'choice_label' => 'id',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Covoiturage::class,
        ]);
    }
}
