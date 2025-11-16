<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\EventSubscriber\FormDonneesSubscriber;
use App\EventSubscriber\FormCompteSubscriber;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class UtilisateurFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventSubscriber(new FormDonneesSubscriber())
            ->addEventSubscriber(new FormCompteSubscriber())
            ->add('suspendu', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'label' => 'Suspendu',
                'label_attr' => ['class' => 'form-check-label'],
                'data' => $options['suspendu']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'suspendu' => true
        ]);

        $resolver->setAllowedTypes('suspendu', 'bool');

    }
}
