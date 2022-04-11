<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Faker\Factory;
use App\Entity\Product;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{

    protected $slugger;
    protected $encoder;

    public function __construct(SluggerInterface $slugger, UserPasswordEncoderInterface $encoder)
    {
        $this->slugger = $slugger;
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $faker->addProvider(new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));

        // $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));



        $admin = new User;
        $users = [];
        $hashedPassword = $this->encoder->encodePassword($admin, "password");
        $admin->setEmail("admin@gmail.com")
            ->setPassword($hashedPassword)
            ->setRoles(["ROLE_ADMIN"])
            ->setFullName("Admin");

        $manager->persist($admin);
        for ($u = 0; $u < 5; $u++) {
            $user = new User;
            $hashedPassword = $this->encoder->encodePassword($user, "password");
            $user->setEmail("user$u@gmail.com")
                ->setFullName($faker->name())
                ->setPassword($hashedPassword);
            $users[] = $user;
            $manager->persist($user);
        }

        $category1 = new Category;
        $category1->setName("Meuble");
        //->setSlug("meuble");
        $manager->persist($category1);

        $products = [];
        for ($i = 0; $i < mt_rand(15, 20); $i++) {
            $product = new Product;
            $product
                ->setName($faker->sentence())
                ->setPrice($faker->price(4000, 2000))
                //  ->setSlug(strtolower($this->slugger->slug($product->getName()))) this is done with our ProductSlugListener.ph
                ->setCategory($category1)
                ->setShortDescription($faker->paragraph())
                ->setMainPicture($faker->imageUrl(400, 400));

            $products[] = $product;

            $manager->persist($product);
        }

        $category2 = new Category;
        $category2->setName("Books,Automotive & jewelry");
        //  ->setSlug("books-automotive-jewelry");
        $manager->persist($category2);


        for ($i = 0; $i < mt_rand(15, 20); $i++) {
            $product = new Product;
            $product
                ->setName($faker->sentence())
                ->setPrice($faker->price(4000, 2000))
                //->setSlug(strtolower($this->slugger->slug($product->getName())))
                ->setCategory($category2)
                ->setShortDescription($faker->paragraph())
                ->setMainPicture($faker->imageUrl(400, 400));

            $products[] = $product;

            $manager->persist($product);
        }

        $category3 = new Category;
        $category3->setName("Home & Computers");
        //->setSlug("home-computers");
        $manager->persist($category3);

        for ($i = 0; $i < mt_rand(15, 20); $i++) {
            $product = new Product;
            $product
                ->setName($faker->sentence())
                ->setPrice($faker->price(4000, 2000))
                //->setSlug(strtolower($this->slugger->slug($product->getName())))
                ->setCategory($category3)
                ->setShortDescription($faker->paragraph())
                ->setMainPicture($faker->imageUrl(400, 400));

            $products[] = $product;

            $manager->persist($product);
        }

        for ($p = 0; $p < mt_rand(20, 40); $p++) {
            $purchase = new Purchase;
            $purchase->setFullName($faker->name)
                ->setAddress($faker->streetAddress)
                ->setCity($faker->city)
                ->setPostalCode($faker->postcode)
                ->setUser($faker->randomElement($users))
                ->setTotal(mt_rand(2000, 30000))
                ->setPurchasedAt($faker->dateTimeBetween('-6 months'));

            $selectedProduct = $faker->randomElements($products, mt_rand(3, 5));
            foreach ($selectedProduct as $product) {
                $purchaseItem = new PurchaseItem;
                $purchaseItem->setProduct($product)
                    ->setQuantity(mt_rand(1, 3))
                    ->setProductName($product->getName())
                    ->setProductPrice($product->getPrice())
                    ->setTotal($purchaseItem->getProductPrice() * $purchaseItem->getQuantity())
                    ->setPurchase($purchase);
                $manager->persist($purchaseItem);
            }
            if ($faker->boolean(90)) {
                $purchase->setStatus(Purchase::STATUS_PAID);
            }
            $manager->persist($purchase);
        }
        $manager->flush();
    }
}
