<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ValidationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note', IntegerType::class)
            ->add('avis', TextareaType::class, [
                'required' => false,
                'attr' => ['class' => 'tinymce'],
            ])
            ->add('validation', ChoiceType::class, [
                'choices'  => [
                    'Le voyage s\'est bien passé' => 'Ok',
                    'Le voyage s\'est mal passé' => 'Ko',
                ],
            ])
            ->add('commentaire', TextareaType::class, [
                'required' => true,
                // 'disabled' => true,
                'attr' => ['class' => 'tinymce'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
