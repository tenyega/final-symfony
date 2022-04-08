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
        $purchase->setUser($user)
            ->setPurchasedAt(new DateTime());

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
        $total += $this->cartService->getTotal($this->productRepository);
        //8. Save the commande entityManagerInterface
        $purchase->setTotal($total * 100);
        $this->em->persist($purchase);


        $this->em->flush();
    }
}
