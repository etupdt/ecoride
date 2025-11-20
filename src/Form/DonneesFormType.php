<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\EventSubscriber\FormDonneesSubscriber;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class DonneesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('oldPassword', PasswordType::class, [
                'label' => 'Pour modifier ces informations, vous devez ressaisir votre ancien mot de passe',
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
            ->addEventSubscriber(new FormDonneesSubscriber())
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
