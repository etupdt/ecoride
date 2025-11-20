<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class FormFiltresSubscriber implements EventSubscriberInterface
{

    public function preSetData(FormEvent $event): void
    {

        $form = $event->getForm();

        $form
            ->add('ecologique', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'label' => 'Ecologique',
                'label_attr' => ['class' => 'form-check-label'],
                'mapped' => false,
                'required' => false,
            ])
            ->add('prix_personne', IntegerType::class, [
                'label' => 'Prix maximum',
                'attr' => [
                    'class' => 'form-control',
                ],
                'mapped' => false,
                'required' => false,
            ])
            ->add('duree_voyage', TimeType::class, [
                'label' => 'Durée maximum',
                'attr' => [
                    'class' => 'form-control',
                ],
                'mapped' => false,
                'required' => false,
            ])
            ->add('note_chauffeur', IntegerType::class, [
                'label' => 'Note minimum du chauffeur',
                'attr' => [
                    'class' => 'form-control',
                ],
                'mapped' => false,
                'required' => false,
            ])
        ;
    
    }

    public static function getSubscribedEvents(): array
    {
        return [FormEvents::PRE_SET_DATA => 'preSetData'];
    }

}
