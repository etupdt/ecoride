<?php

namespace App\Form;

use App\Entity\Covoiturage;
use App\Entity\Voiture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CovoiturageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lieu_depart', TextType::class, [
                "mapped" => false,
                'label' => 'Départ',
                'data' => $options['depart'],
                'attr' => [
                    'class' => 'form-control',
                    'list' => 'list-ville',
                ],
            ])
            ->add('lieu_arrivee', TextType::class, [
                "mapped" => false,
                'label' => 'Arrivée',
                'data' => $options['arrivee'],
                'attr' => [
                    'list' => 'list-ville',
                ],
            ])
            ->add('date_depart')
            ->add('heure_depart')
            ->add('date_arrivee')
            ->add('heure_arrivee', DateTimeType::class, [
                'label' => 'Heure d\'arrivée',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('heure_arrivee')
            ->add('statut')
            ->add('nb_place')
            ->add('prix_personne', IntegerType::class)
            ->add('voiture', EntityType::class, [
                'class' => Voiture::class,
                'choice_label' => 'immatriculation',
                'choices' => $options['chauffeur']->getVoitures()
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Covoiturage::class,
            'chauffeur' => null,
            'depart' => '',
            'arrivee' => ''
        ]);

        $resolver->setAllowedTypes('chauffeur', 'App\Entity\User');

    }
}
