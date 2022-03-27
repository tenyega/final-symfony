<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Faker\Factory;
use App\Entity\Product;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{

    protected $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $faker->addProvider(new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));

        // $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));

        $category1 = new Category;
        $category1->setName("Meuble")
            ->setSlug("meuble");
        $manager->persist($category1);

        for ($i = 0; $i < mt_rand(15, 20); $i++) {
            $product = new Product;
            $product
                ->setName($faker->sentence())
                ->setPrice($faker->price(4000, 2000))
                ->setSlug(strtolower($this->slugger->slug($product->getName())))
                ->setCategory($category1)
                ->setShortDescription($faker->paragraph())
                ->setMainPicture($faker->imageUrl(200, 200));

            $manager->persist($product);
        }

        $category2 = new Category;
        $category2->setName("Books,Automotive & jewelry")
            ->setSlug("books-automotive-jewelry");
        $manager->persist($category2);


        for ($i = 0; $i < mt_rand(15, 20); $i++) {
            $product = new Product;
            $product
                ->setName($faker->sentence())
                ->setPrice($faker->price(4000, 2000))
                ->setSlug(strtolower($this->slugger->slug($product->getName())))
                ->setCategory($category2)
                ->setShortDescription($faker->paragraph())
                ->setMainPicture($faker->imageUrl(200, 200));



            $manager->persist($product);
        }

        $category3 = new Category;
        $category3->setName("Home & Computers")
            ->setSlug("home-computers");
        $manager->persist($category3);

        for ($i = 0; $i < mt_rand(15, 20); $i++) {
            $product = new Product;
            $product
                ->setName($faker->sentence())
                ->setPrice($faker->price(4000, 2000))
                ->setSlug(strtolower($this->slugger->slug($product->getName())))
                ->setCategory($category3)
                ->setShortDescription($faker->paragraph())
                ->setMainPicture($faker->imageUrl(200, 200));



            $manager->persist($product);
        }
        $manager->flush();
    }
}
