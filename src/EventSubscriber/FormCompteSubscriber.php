<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

class FormCompteSubscriber implements EventSubscriberInterface
{

    public function preSetData(FormEvent $event): void
    {

        $form = $event->getForm();

        $form
            ->add('telephone')
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Assert\NotBlank(message: 'Vous devez saisir un nom'),
                    new Assert\Length(
                        min: 2,
                        minMessage: 'Le nom doit contenir au minimum {{ limit }} charactères',
                    )
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Assert\NotBlank(message: 'Vous devez saisir un prénom'),
                    new Assert\Length(
                        min: 2,
                        minMessage: 'Le prénom doit contenir au minimum {{ limit }} charactères',
                    )
                ],
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('date_naissance', DateType::class, [
                'label' => 'Date de naissance',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo',
                'mapped' => false,
                'required' => false,
                // 'constraints' => [
                //     new File(
                //         maxSize: '1024k',
                //         extensions: ['pdf'],
                //         extensionsMessage: 'Veuillez charger une photo',
                //     )
                // ],
            ])
        ;
    
    }

    public static function getSubscribedEvents(): array
    {
        return [FormEvents::PRE_SET_DATA => 'preSetData'];
    }
}
