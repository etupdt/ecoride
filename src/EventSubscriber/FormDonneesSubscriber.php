<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints as Assert;

class FormDonneesSubscriber implements EventSubscriberInterface
{
    public function preSetData(FormEvent $event): void
    {

        $form = $event->getForm();

        $form
            ->add('email', EmailType::class, [
                'label' => 'Adresse mail',
                'attr' => [
                    'class' => 'form-control',
                    'reqired' => true,
                ],
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Assert\NotBlank(message: 'Vous devez saisir un pseudo'),
                    new Assert\Length(
                        min: 2,
                        minMessage: 'Le pseudo doit contenir au minimum {{ limit }} charactères',
                        max: 50,
                        maxMessage: 'Le pseudo doit contenir au maximum {{ limit }} charactères',
                    )
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe',
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Assert\NotBlank(message: 'Vous devez saisir un mot de passe'),
                    new Assert\Length(
                        min: 8,
                        minMessage: 'Le mot de passe doit contenir au minimum {{ limit }} charactères',
                    )
                ],
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => 'Confirmation du mot de passe',
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Assert\NotBlank(message: 'Vous devez confirmer votre mot de passe'),
                    new Assert\Length(
                        min: 8,
                        minMessage: 'Le mot de passe de confirmation doit contenir au minimum {{ limit }} charactères',
                    ),
                    new EqualTo([
                        'propertyPath' => 'parent.all[plainPassword].data',
                        'message' => 'Le mot de passe n\'est pas confirmé'
                    ]),
                ],
            ])
        ;
    
    }

    public static function getSubscribedEvents(): array
    {
        return [FormEvents::PRE_SET_DATA => 'preSetData'];
    }

}
