<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/",name="homepage")
     */
    public function homepage()
    {
        /*  $count = $productRepository->count(['price' => 1500]);
        // $count = $productRepository->count([]); count without criteria
        $product = $productRepository->find(2);
        $products = $productRepository->findAll();
        $products = $productRepository->findBy([], ['name' => 'DESC']);
       $products = $productRepository->findOneBy(['name' => 'table en plastique']);

        foreach ($products as  $value) {
           // dump($value->getUpperName());  # code...
        }

       dump($products);*/


        /* Adding new Product
        $product = new Product;

        $product->setName('table en métal');
        $product->setPrice('3000');
        $product->setSlug('table-en-metal');

        $em->persist($product);
        $em->flush();

        */

        /* To Modify the values in DB
        $productRepository = $em->getRepository(Product::class);
        $product = $productRepository->find(5);

        $product->setPrice('3500');
        $em->flush();
        dump($product);
        */


        /*To Remove an entry in DB 
        $productRepository = $em->getRepository(Product::class);
        $product = $productRepository->find(5);
        $em->remove($product);
        $em->flush();
        */

        return $this->render("home.html.twig");
    }
}
