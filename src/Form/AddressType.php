<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TelType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de mon adresse :',
                'attr' => [
                    'placeholder' => 'Nommez votre adresse'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom :',
                'attr' => [
                    'placeholder' => 'Entrez votre prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Votre nom :',
                'attr' => [
                    'placeholder' => 'Entrez votre nom'
                ]
            ])
            ->add('company', TextType::class, [
                'label' => 'Votre société :',
                'required' => false,
                'attr' => [
                    'placeholder' => '(Facultatif) Nom de votre société'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Votre adresse :',
                'attr' => [
                    'placeholder' => 'Ex : 8 rue des lylas...'
                ]
            ])
            ->add('postal', TextType::class, [
                'label' => 'Code postal :',
                'attr' => [
                    'placeholder' => 'Ex : 75010'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville :',
                'attr' => [
                    'placeholder' => 'Ex : Paris'
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => 'Pays : '
            ])
            ->add('phone', TelType::class, [
                'label' => 'Votre téléphone :',
                'attr' => [
                    'placeholder' => 'Entrez votre numéro de téléphone'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'btn-block btn-info'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
