<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Formateur;
use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomSession', TextType::class, [
                'label' => 'Nom de la session * : ',
                'attr' => [
                    'class' => 'form-session',
                ]
            ])
            ->add('nbPlaces', IntegerType::class, [
                'attr' => [
                    'class' => 'form-session'
                ]
            ])

            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text',
                'attr' => [
                   'class' => 'form-session'
                ]
            ])

            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
                'attr' => [
                   'class' => 'form-session'
                ]
            ])

            ->add('formation', EntityType::class, [
                'class' => Formation::class,
                'attr' => [
                    'class' => 'form-session'
                ]
            ])
            ->add('formateur', EntityType::class, [
                'class' => Formateur::class,
                'attr' => [
                    'class' => 'form-session'
                ]
            ])
            ->add('valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn-session'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
