<?php

namespace App\Cart;

use App\Entity\Product;
use App\Repository\ProductRepository;

class CartItem
{

    protected $productid;
    protected $quantity;

    public function __construct(int $id, int $quantity)
    {
        $this->productid = $id;
        $this->quantity = $quantity;
    }

    public function getTotal(ProductRepository $productRepository)
    {
        $product = $productRepository->find($this->productid);

        return ($product->getPrice() / 100) * $this->quantity;
    }
}
