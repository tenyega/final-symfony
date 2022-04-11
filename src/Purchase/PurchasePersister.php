<?php

namespace App\Purchase;

use App\Cart\CartService;
use DateTime;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class PurchasePersister
{

    protected $cartService;
    protected $em;
    protected $security;
    protected $productRepository;

    public function __construct(Security $security, EntityManagerInterface $em, CartService $cartService, ProductRepository $productRepository)
    {
        $this->security = $security;
        $this->em = $em;
        $this->cartService = $cartService;
        $this->productRepository = $productRepository;
    }

    public function storePurchase(Purchase $purchase)
    {
        // all that we need to persist a purchase

        $user = $this->security->getUser();
        //6.a link with the user connected : Security 
        $purchase->setUser($user);
        // ->setPurchasedAt(new DateTime()); As its always the todays date we can automatically do it with Doctrine Events inside the entity Purchase

        //7. Link our purchase to the product inside the cart 

        $total = 0;

        foreach ($this->cartService->getDetailedCartItems($this->productRepository) as $cartItem) {
            $purchaseItem = new PurchaseItem;
            // dd($cartItem->getTotal($this->productRepository));

            $purchaseItem->setPurchase($purchase)
                ->setProduct($cartItem['product'])
                ->setProductName($cartItem['product']->getName())
                ->setQuantity($cartItem['qty'])
                ->setTotal($cartItem['product']->getPrice())
                ->setProductPrice($cartItem['product']->getPrice());
            $this->em->persist($purchaseItem);
        }
        // $purchase->addPurchaseItem($purchaseItem); we can also do this in the place of setPurchase to get the total before flush();
        // $total += $this->cartService->getTotal($this->productRepository); this total can be calculated with the help of our doctrine events 
        //8. Save the commande entityManagerInterface
        $purchase->setTotal($total * 100);
        $this->em->persist($purchase);


        $this->em->flush();
    }
}
