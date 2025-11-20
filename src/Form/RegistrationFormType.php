<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use App\EventSubscriber\FormDonneesSubscriber;
use App\EventSubscriber\FormCompteSubscriber;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventSubscriber(new FormDonneesSubscriber())
            ->addEventSubscriber(new FormCompteSubscriber())
            ->add('agreeTerms', CheckboxType::class, [
                'required' => false,
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
            ->add('photo', FileType::class, [
                'label' => 'Photo',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'compte_form_photo',
                ],
                // 'required' => false,
                'constraints' => [
                    new File(
                        maxSize: '2048k',
                        extensions: ['jpg', 'png'],
                        extensionsMessage: 'Veuillez charger une photo',
                    )
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
