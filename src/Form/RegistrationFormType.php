<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse mail',
                'attr' => [
                    'class' => 'form-control',
                    'autofocus' => true,
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
            ->add('agreeTerms', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'label' => 'Agréer.',
                'label_attr' => ['class' => 'form-check-label'],
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
