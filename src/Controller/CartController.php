<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Form\CartConfirmationType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Annotation\Requirement;

class CartController extends AbstractController
{

    protected $productRepository;
    protected $cartService;

    public function __construct(ProductRepository $productRepository, CartService $cartService)
    {
        $this->productRepository = $productRepository;
        $this->cartService = $cartService;
    }
    /**
     * @Route("/cart/add/{id}", name="cart_add")
     */
    public function add($id, Request $request)
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException(" cant find this product");
        }


        $this->cartService->add($id);

        // /**@var FlashBag */
        // $flashBag = $session->getBag('flashes');


        // $flashBag->add('success', "The product has been added to the cart");
        $this->addFlash('success', "The product has been added to the cart");
        // $flashBag->add('warning', "careful");
        // $flashBag->add('info', "information");
        // $flashBag->add('success', "another success");

        // dump($flashBag->get('warning')); // this method reads the flashbag and ones read the flashbags are cleared automatically
        //$flashBag->get('success');
        //dd($session->getBag('flashes'));
        //dd($request->getSession()->remove('cart')); // only ones during the developement stage if in case we have a prob
        // dd($request->getSession()->get('cart'));

        if ($request->query->get('returntocart')) {
            return $this->redirectToRoute('cart_show');
        }
        return $this->redirectToRoute('product_show', [
            'category_slug' => $product->getCategory()->getSlug(),
            'slug' => $product->getSlug()
        ]);
    }

    /**
     * @Route("/cart", name="cart_show")
     */
    public function show()
    {
        $form = $this->createForm(CartConfirmationType::class);

        $detailedCart = $this->cartService->getDetailedCartItems($this->productRepository);

        $total = $this->cartService->getTotal($this->productRepository);
        // dd($detailedCart);
        return $this->render("cart/index.html.twig", [
            'items' => $detailedCart,
            'total' => $total,
            'confirmationForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/cart/delete/{id}", name="cart_delete", requirements={"id":"\d+"})
     */
    public function delete($id)
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("le product $id n'existe pas et ne peut pas etre suprimé");
        }
        $this->cartService->remove($id);
        $this->addFlash('success', "le Produit a ete bien supprimer de panier");
        return $this->redirectToRoute('cart_show');
    }

    /**
     * @Route("/cart/decrement/{id}", name="cart_decrement", requirements={"id":"\d+"})
     */
    public function decrement($id)
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("le product $id n'existe pas et ne peut pas etre suprimé");
        }
        $this->cartService->decrement($id);
        $this->addFlash('success', "the quantity has been reduced");

        return $this->redirectToRoute('cart_show');
    }
}
