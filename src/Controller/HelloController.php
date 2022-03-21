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

class HelloController
{

    protected $calculator;
    public function __construct(Calculator $calculator)
    {
        $this->calculator = $calculator;
    }
    /**
     * @Route("/hello/{prenom?World}",name="hello",methods={"GET","POST"},schemes={"http","https"})
     */


    public function hello($prenom, Slugify $slugify, Environment $twig)
    {
        dump($twig);
        dump($slugify->slugify("Hello World"));

        //  $this->logger->error("hi this is the log msg");

        $tva = $this->calculator->calcul(100);
        dump($tva);
        return new Response("Hello $prenom");
    }
}
