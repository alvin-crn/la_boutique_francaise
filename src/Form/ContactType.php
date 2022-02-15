<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pernom', TextType::class, [
                'label' => 'Vous prénom'
            ])
            ->add('nom', TextType::class, [
                'label' => 'Vous nom'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Vous adresse email'
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Vous message'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyez',
                'attr' => [
                    'class' => 'btn-block btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
