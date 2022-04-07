<?php

namespace App\Controller\Purchase;

use DateTime;
use App\Entity\Purchase;
use App\Cart\CartService;
use App\Entity\PurchaseItem;
use App\Form\CartConfirmationType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;


class PurchaseConfirmationController extends AbstractController
{

    protected $cartService;
    protected $productRepository;
    protected $em;

    public function __construct(CartService $cartService, ProductRepository $productRepository, EntityManagerInterface $em)
    {

        $this->cartService = $cartService;
        $this->productRepository = $productRepository;
        $this->em = $em;
    }

    /**
     * @Route("/purchase/confirm", name="purchase_confirm")
     * @IsGranted("ROLE_USER", message="Cant order with out connecting")
     */
    public function confirm(Request $request) // Here the request and flashBag is not put inside our constructor coz it changes for every route and session thats why
    {
        //1. We want to read the form : formfactoryInterface / Request

        $form = $this->createForm(CartConfirmationType::class);

        $form->handleRequest($request);

        //2. If the form is not submited go out of this controller 
        if (!$form->isSubmitted()) {
            $this->addFlash('warning', "U need to fill the form");
            //Message Flash puis redirection( FlashBagInterface)
            return $this->redirectToRoute('cart_show');
        }


        //3. If m not connected  then go out of this controller : Security
        $user = $this->getUser();



        //4. if form is submited, we are connected  but theres no product in the cart 
        // then go out also :- CartService 
        $cartItems = $this->cartService->getDetailedCartItems($this->productRepository);
        if (count($cartItems) === 0) {
            $this->addFlash('warning', "you cant confirm an order with  nothing in the cart");
            return $this->redirectToRoute('cart_show');
        }


        //5. if everything goes well then we create a purchase // $purchase = $form->getData(); is possible coz we have set the data_class in our CartConfirmationType as Purchase Class

        /** @var Purchase */
        $purchase = $form->getData();

        //6.a link with the user connected : Security 
        $purchase->setUser($user)
            ->setPurchasedAt(new DateTime());

        //7. Link our purchase to the product inside the cart 

        $total = 0;

        foreach ($cartItems as $cartItem) {
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
        $this->cartService->empty();
        $this->addFlash('success', "La commande a bien été enregistré");
        return $this->redirectToRoute('purchase_index');
    }
}

// Actual PROCESS
// class PurchaseConfirmationController extends AbstractController
// {

//     protected $formfactory;
//     protected $router;
//     protected $security;
//     protected $cartService;
//     protected $productRepository;
//     protected $em;

//     public function __construct(FormFactoryInterface $factory, RouterInterface $router, Security $security, CartService $cartService, ProductRepository $productRepository, EntityManagerInterface $em)
//     {
//         $this->formfactory = $factory;
//         $this->router = $router;
//         $this->security = $security;
//         $this->cartService = $cartService;
//         $this->productRepository = $productRepository;
//         $this->em = $em;
//     }
//     /**
//      * @Route("/purchase/confirm", name="purchase_confirm")
//      */
//     public function confirm(Request $request, FlashBagInterface $flashBag) // Here the request and flashBag is not put inside our constructor coz it changes for every route and session thats why
//     {
//         //1. We want to read the form : formfactoryInterface / Request

//         $form = $this->formfactory->create(CartConfirmationType::class);

//         $form->handleRequest($request);

//         //2. If the form is not submited go out of this controller 
//         if (!$form->isSubmitted()) {
//             $flashBag->add('warning', "U need to fill the form");
//             //Message Flash puis redirection( FlashBagInterface)
//             return new RedirectResponse($this->router->generate('cart_show'));
//         }


//         //3. If m not connected  then go out of this controller : Security
//         $user = $this->security->getUser();
//         if (!$user) {

//             throw new AccessDeniedException("You need to connect to confirm an order");
//         }


//         //4. if form is submited, we are connected  but theres no product in the cart 
//         // then go out also :- CartService 
//         $cartItems = $this->cartService->getDetailedCartItems($this->productRepository);
//         if (count($cartItems) === 0) {
//             $flashBag->add('warning', "you cant confirm an order with  nothing in the cart");
//             return new RedirectResponse($this->router->generate('cart_show'));
//         }


//         //5. if everything goes well then we create a purchase // $purchase = $form->getData(); is possible coz we have set the data_class in our CartConfirmationType as Purchase Class

//         /** @var Purchase */
//         $purchase = $form->getData();

//         //6.a link with the user connected : Security 
//         $purchase->setUser($user)
//             ->setPurchasedAt(new DateTime());

//         //7. Link our purchase to the product inside the cart 

//         $total = 0;

//         foreach ($cartItems as $cartItem) {
//             $purchaseItem = new PurchaseItem;
//             // dd($cartItem->getTotal($this->productRepository));

//             $purchaseItem->setPurchase($purchase)
//                 ->setProduct($cartItem['product'])
//                 ->setProductName($cartItem['product']->getName())
//                 ->setQuantity($cartItem['qty'])
//                 ->setTotal($cartItem['product']->getPrice())
//                 ->setProductPrice($cartItem['product']->getPrice());
//             $this->em->persist($purchaseItem);
//             $total += $this->cartService->getTotal($this->productRepository);
//         }
//         //8. Save the commande entityManagerInterface
//         $purchase->setTotal($total * 100);
//         $this->em->persist($purchase);


//         $this->em->flush();
//         $flashBag->add('success', "La commande a bien été enregistré");
//         return new RedirectResponse($this->router->generate('purchase_index'));
//     }
// }
