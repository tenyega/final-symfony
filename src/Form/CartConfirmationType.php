<?php

namespace App\Form;

use App\Entity\Purchase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CartConfirmationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'label' => "Nom Complete",
                'attr' => [
                    'placeholder' => "nom complete pour la livarison"
                ]
            ])
            ->add('address', TextareaType::class, [
                'label' => 'Adresse Complete',
                'attr' => [
                    'placeholder' => 'adresse pour la livraison'
                ]
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code Postale',
                'attr' => [
                    'placeholder' => 'Votre Code Postale'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Votre Ville',
                'attr' => [
                    'placeholder' => 'Votre Ville pour la livraison'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => Purchase::class
        ]);
    }
}
