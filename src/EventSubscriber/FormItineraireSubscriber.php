<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class FormItineraireSubscriber implements EventSubscriberInterface
{

    public function preSetData(FormEvent $event): void
    {

        $form = $event->getForm();

        $form
            ->add('date_filtre', DateType::class, [
                "mapped" => false,
                'required' => false,
                'label' => 'Date',
                'label_attr' => [
                    'class' => 'input-group-text',
                ],
            ])
            ->add('lieu_depart', TextType::class, [
                "mapped" => false,
                'required' => false,
                'label' => 'Départ',
                'label_attr' => [
                    'class' => 'input-group-text',
                ],
                'attr' => [
                    'list' => 'list-ville',
                ],
            ])
            ->add('lieu_arrivee', TextType::class, [
                "mapped" => false,
                'required' => false,
                'label' => 'Arrivée',
                'label_attr' => [
                    'class' => 'input-group-text',
                ],
                'attr' => [
                    'list' => 'list-ville',
                ],
            ])
        ;
    
    }

    public static function getSubscribedEvents(): array
    {
        return [FormEvents::PRE_SET_DATA => 'preSetData'];
    }

}
