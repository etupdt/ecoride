<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use App\Entity\Energie;
use App\Entity\Marque;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class FormVoitureSubscriber implements EventSubscriberInterface
{

    public function preSetData(FormEvent $event): void
    {

        $form = $event->getForm();

        $form
            ->add('modele')
            ->add('immatriculation')
            ->add('energie')
            ->add('couleur')
            ->add('datePremiereImmatriculation', DateType::class, [
                'label' => 'Date de première immatriculation',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('energie', EntityType::class, [
                'class' => Energie::class,
                'choice_label' => 'libelle',
            ])
            ->add('marque', EntityType::class, [
                'class' => Marque::class,
                'choice_label' => 'libelle',
            ])
            ->add('fumeur', CheckboxType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'label' => 'Fumeur',
                'label_attr' => ['class' => 'form-check-label'],
            ])
            ->add('animal', CheckboxType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'label' => 'Animaux accepté',
                'label_attr' => ['class' => 'form-check-label'],
            ])
        ;
    
    }

    public static function getSubscribedEvents(): array
    {
        return [FormEvents::PRE_SET_DATA => 'preSetData'];
    }

}
