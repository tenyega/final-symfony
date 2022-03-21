<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{

    public function index()
    {

        dd("ca fontionne");
    }

    /**
     * @Route("/test/{age<\d+>?0}",name="test",methods={"GET","POST"}, host="localhost",schemes={"http","https"})
     */

    public function test(Request $request)
    {
        //$request = Request::createFromGlobals(); here instead can be
        // directly created as our parameter in test() 
        // dd($request);
        $age = $request->query->get('age', 0);
        $age = $request->attributes->get('age');

        // $age = 0;
        // if (!empty($_GET['age'])) {
        //     $age = $_GET['age'];
        // }

        // dump("vous avez $age ans");
        // die;

        return new Response("vous avez $age ans yega!!!");
    }
}
