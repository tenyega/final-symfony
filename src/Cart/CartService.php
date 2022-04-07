<?php

namespace App\Cart;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class CartService
{

    protected $session;
    protected $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {

        $this->session = $session;

        //  $this->$productRepository = $productRepository;
    }

    protected function getCart()
    {
        return $this->session->get('cart', []);
    }
    protected function saveCart(array $cart)
    {
        return $this->session->set('cart', $cart);
    }

    public function empty()
    {
        $this->saveCart([]);
    }

    public function add(int $id)
    {

        // 1. Find pannier in the session in the form of a table 

        // 2. if panier doesnt exist take blank table;

        // $cart = $request->getSession()->get('cart', []);
        $cart = $this->getCart();




        //key=>value and here its id=>value
        // 3.if exist  see the id already available in pannier  
        //4. if yes then add the quantiy only 
        //5.if not then add the product with quantity =1
        // if (array_key_exists($id, $cart)) {
        //     $cart[$id]++;
        // } else {
        //     $cart[$id] = 1;
        // }

        if (!array_key_exists($id, $cart)) {
            $cart[$id] = 0;
        }
        $cart[$id]++;




        //6.save the table and update the session
        //$request->getSession()->set('cart', $cart);
        $this->saveCart($cart);


        //this method can be avoided with the help of argument resolver which is flashbaginterface directly in the method with a route 
        // $session->set('cart', $cart);
    }

    public function remove(int $id)
    {
        $cart = $this->getCart();
        unset($cart[$id]);
        $this->saveCart($cart);
    }

    public function decrement(int $id)
    {
        $cart = $this->getCart();

        if (!array_key_exists($id, $cart)) {
            $this->remove($id);
            return;
        }
        $cart[$id]--;
        $this->saveCart($cart);
    }

    public function getTotal($productRepository): int
    {

        dump('inside cart Sercive');
        $total = 0;
        $productRepo = $productRepository;
        foreach ($this->getCart() as $id => $quantity) {

            //   $cartItem = new CartItem($id, $quantity);
            $product =  $productRepo->find($id);
            if (!$product) {
                continue;
            }

            $total += ($product->getPrice() / 100) * $quantity;

            // $total += $cartItem->getTotal($productRepo);
        }
        return $total;
    }
    /**
     * @return CartItem[]
     */
    public function getDetailedCartItems($productRepository): array
    {
        $detailedCart = [];
        $productRepo = $productRepository;
        foreach ($this->getCart() as $id => $quantity) {
            $product =  $productRepo->find($id);
            if (!$product) {
                continue;
            }

            $detailedCart[] = [
                'product' => $product,
                'qty' => $quantity
            ];
        }
        return $detailedCart;
    }
}
