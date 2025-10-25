<?php

namespace App\Form;

use App\Entity\Marque;
use App\Entity\User;
use App\Entity\Voiture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use App\EventSubscriber\FormVoitureSubscriber;

class VoitureFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventSubscriber(new FormVoitureSubscriber())
            // ->add('modele')
            // ->add('immatriculation')
            // ->add('energie')
            // ->add('couleur')
            // ->add('datePremiereImmatriculation', DateType::class, [
            //     'label' => 'Date de première immatriculation',
            //     'attr' => [
            //         'class' => 'form-control',
            //     ],
            // ])
            // ->add('marque', EntityType::class, [
            //     'class' => Marque::class,
            //     'choice_label' => 'libelle',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}
