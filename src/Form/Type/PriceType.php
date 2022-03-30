<?php

namespace App\Form\Type;

use App\Form\DataTransformer\CentimesTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PriceType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['divide'] === false) {
            return;
        }
        // our own class CentimesTransformer to convert the values of the price /100 to show to the user 
        $builder->addModelTransformer(new CentimesTransformer);
    }


    // Need one type of class that is already known to our forms 
    public function getParent()
    {
        // by having its NumberType our PriceType has all the setting of NumberType by default eg 'label', 'attr' etc
        return NumberType::class;
    }


    // to have our own setting like divide so that we can divide it when ever we want 
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'divide' => true
        ]);
    }
}
