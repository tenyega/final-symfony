<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 100; $i++) {
            $product = new Product;
            $product
                ->setName("Product No$i")
                ->setPrice(mt_rand(100, 200))
                ->setSlug("product-no-$i");

            $manager->persist($product);
        }
        $manager->flush();
    }
}
