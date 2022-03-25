<?php

namespace App\Controller;

use Twig\Environment;
use App\Taxes\Calculator;
use Cocur\Slugify\Slugify;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use __TwigTemplate_83f4bd8b1c02ffea0a4f9769009dbb9d8061f74e4a984a5c516aa5e3905af0b0;
use App\Taxes\Detector;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HelloController extends AbstractController
{

    protected $calculator;
    // protected $twig; taken care by our AbstractController 
    public function __construct(Calculator $calculator)
    {
        // $this->twig = $twig;  taken care by our AbstractController 
        $this->calculator = $calculator;
    }
    /**
     * @Route("/hello/{prenom?World}",name="hello",methods={"GET","POST"},schemes={"http","https"})
     */


    public function hello($prenom = "World")
    {
        // dump($detector->detect(101));
        // dump($detector->detect(11));
        // //dump($twig);
        // dump($slugify->slugify("Hello World"));

        // //  $this->logger->error("hi this is the log msg");

        // $tva = $this->calculator->calcul(100);
        // dump($tva);
        return $this->render('hello.html.twig', [
            'prenom' => $prenom,
            'formateur' => [
                "nom" => 'Chamla',
                "prenom" => 'Lior',
                "age" => 33,
                'enfant' => [
                    'tenzin',
                    'yega'
                ]
            ],
            'class1' => ['place' => 'sartrouville', 'cp' => 78500],
            'class2' => ['place' => 'paris', 'cp' => 75000]
        ]);
    }


    /**
     * @Route("/example", name="example")
     */
    public function example()
    {
        return $this->render("example.html.twig", [
            'age' => 33
        ]);
    }

    //taken care by our AbstractController 
    // protected function myRender(string $path, array $variables = [])
    // {
    //     $html = $this->twig->render($path, $variables);
    //     return new Response($html);
    // }
}
