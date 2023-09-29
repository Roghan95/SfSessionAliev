<?php

namespace App\Form;

use App\Entity\Formateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FormateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // On ajoute les champs du formulaire
        $builder
            ->add('nomFormateur', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])

            ->add('prenomFormateur', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
                ])

            ->add('sexe', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])

            ->add('dateNaissance', DateType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form_control'
                ]
            ])

            ->add('email', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])

            ->add('telephone', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])

            ->add('valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn-submit'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formateur::class,
        ]);
    }
}
