<?php

namespace App\Controller\Purchase;

use App\Entity\User;
use Twig\Environment;
use App\Form\CartConfirmationType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PurchasesListController extends AbstractController
{
    // protected $security;
    // protected $router;
    // protected $twig;
    // public function __construct(Security $security, RouterInterface $router, Environment $twig)
    // {
    //     $this->security = $security;
    //     $this->router = $router;
    //     $this->twig = $twig;
    // }

    /**
     * @Route("/purchases" ,name="purchase_index")
     * @IsGranted("ROLE_USER", message="Cant see the commande with out connecting")
     */
    public function index()
    {
        //1. We need to assure that the person is connnected -->security
        /**@var User */
        //$user= $this->security->getUser();
        $user = $this->getUser();

        // not needed this coz if @IsGranted
        // if (!$user) {
        //     //redirection->RedirectResponse
        //     //Genere une URL en fonction du nom d'une route-> URL Generator or RouterInterface
        //     // $url = $this->router->generate('homepage');
        //     // return new RedirectResponse($url);

        //     //OR
        //     throw new AccessDeniedException("You need to be connected to be able to see the commandes ");
        // }
        //2. We need to know who is connected ->security

        //3. We need to pass the user to our Twig so that we can show the commandes 
        // Environment de Twig/Response
        // $html = $this->twig->render('purchase/index.html.twig', [
        //     'purchases' => $user->getPurchases()
        // ]);

        // return new Response($html);



        //MY WAY 
        return $this->render('purchase/index.html.twig', [
            'purchases' => $user->getPurchases()

        ]);
    }
}
