<?php

namespace App\Controller;

use Collator;
use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use Doctrine\ORM\EntityManager;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends AbstractController
{
    /**
     * @Route("/{slug}", name="product_category")
     */
    public function category($slug, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBy([
            'slug' => $slug
        ]);

        if (!$category) {
            //throw new NotFoundHttpException("The category doesnt exist");
            throw  $this->createNotFoundException("The category doesnt exist");
        }

        return $this->render('product/category.html.twig', [
            'slug' => $slug,
            'category' => $category
        ]);
    }

    /**
     * @Route("/{category_slug}/{slug}", name="product_show")
     */
    public function show($slug, ProductRepository $productRepository)
    {

        $product = $productRepository->findOneBy([
            'slug' => $slug
        ]);

        if (!$product) {
            throw $this->createNotFoundException(" Product doesnt exist");
        }

        return $this->render("product/show.html.twig", [
            'product' => $product
        ]);
    }
    /**
     * @Route("/admin/product/{id}/edit", name="product_edit")
     */

    public function edit($id, ProductRepository $productRepository, Request $request, EntityManagerInterface $em, ValidatorInterface $validator)
    {

        //Validation Scalaire, a simple validation 
        // $age = 10;

        // // for  {{ compared_value }} {{ value }} the space between the name and {{ }} is very important 
        // $resultat =  $validator->validate($age, [
        //     new LessThanOrEqual([
        //         'value' => 120,
        //         'message' => 'age must be less than {{ compared_value }} and you have given {{ value }}'
        //     ]),
        //     new GreaterThan([
        //         'value' => 0,
        //         'message' => 'age must be greater than 0'
        //     ])
        // ]);




        //Validation Complex for tableau associatif
        // $client = [
        //     'nom' => '',
        //     'prenom' => 'Dolma',
        //     'voiture' => [
        //         'marque' => 'Hyundai',
        //         'coleur' => 'Noire'
        //     ]
        // ];

        // $collection = new Collection([
        //     'nom' => new NotBlank(['message' => 'Nom ne doit pas etre vide']),
        //     'prenom' => [
        //         new NotBlank(['message' => "Nom ne doit pas etre vide"]),
        //         new Length(['min' => 3, 'minMessage' => "le Prenom ne doit pas etre moins de 3 caractere"])
        //     ],
        //     'voiture' => new Collection([
        //         'marque' => new NotBlank(['message' => "La marque de la voiture est obligatoir"]),
        //         'coleur' => new NotBlank(['message' => "La coleur ne doit pas etre vide"])
        //     ])
        // ]);

        // $resultat = $validator->validate($client, $collection);


        //Validation complex for object added a static method (loadValidatorMetaData) inside product entity which takes ClassMetaData as param. 
        //The validator checks in yaml first and if it doesnt find any validator then it will automatically look inside the entity for this static method grace a compiler Pass
        // $product = new Product;
        // $product->setName('hiiii');
        // $product->setPrice('150');

        // $resultat = $validator->validate($product);

        //group Validataion 
        // $product = new Product;
        // $resultat = $validator->validate($product, null, ["Default", "with_price"]);

        // if ($resultat->count() > 0) {
        //     dd('il ya des error', $resultat);
        // }

        // dd('tout va bien');

        $product = $productRepository->find($id);
        $form = $this->createForm(ProductType::class, $product);

        //        $form->setData($product);
        $formView = $form->createView();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //dd($form->getData());
            $em->flush();
            // $response = new Response();
            // $url = $urlGenerator->generate('product_show', [
            //     'category_slug' => $product->getCategory()->getSlug(),
            //     'slug' => $product->getSlug()
            // ]);
            // $response->headers->set('Location', $url);
            // $response->setStatusCode(302);
            // return $response;

            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug()
            ]);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'formView' => $formView
        ]);
    }

    /**
     * @Route("/admin/product/create", name="product_create")
     */
    public function create(CategoryRepository $categoryRepository, Request $request, SluggerInterface $slugger, EntityManagerInterface $em)
    {


        // $builder = $factor->createBuilder(ProductType::class); with the autowiring of FormFactoryInterface $factor,
        $product = new Product;
        $form = $this->createForm(ProductType::class, $product);

        // $form = $builder->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $product = $form->getData(); not needed as we have created a new Product obj blank and we have passed to our form, the form will modify directly our $product
            // $product = new Product;
            // $product->setName($data['name'])
            //     ->setPrice($data['price'])
            //     ->setShortDescription($data['shortDescription'])
            //     ->setCategory($data['category']);

            $product->setSlug(strtolower($slugger->slug($product->getName())));

            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug()
            ]);
        }

        $formView = $form->createView();

        return $this->render("product/create.html.twig", [
            'formView' => $formView
        ]);
    }
}
