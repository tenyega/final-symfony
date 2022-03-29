<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du Produit',
                'attr' => ['placeholder' => 'Tapez le nom du produit']
            ])
            ->add('shortDescription', TextareaType::class, [
                'label' => 'Description du produit',
                'attr' => ['placeholder' => 'Tapez la description du produit']
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix de Produit',
                'attr' => ['placeholder' => 'Tapez le prix de produit']
            ])

            ->add('mainPicture', UrlType::class, [
                'label' => 'Url d\'image',
                'attr' => ['placeholder' => 'Tapez url d\'image svp']
            ])
            //     ;

            // $options = [];

            // foreach ($categoryRepository->findAll() as $category) {
            //     $options[$category->getName()] = $category->getId();
            // }


            // $builder
            ->add('category', EntityType::class, [
                'label' => 'Category de produit',
                'placeholder' => '--Choisir une category--',
                'class' => Category::class,
                'choice_label' => function (Category $category) {
                    return strtoupper($category->getName());
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
