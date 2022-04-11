<?php

namespace App\EventDispatcher;

use Symfony\Component\HttpKernel\Event\RequestEvent;

class PrenomListener
{

    public function addPrenomToAttributes(RequestEvent $requestEvent)
    {
        $requestEvent->getRequest()->attributes->set('prenom', 'YEGA');
    }
    
    public function test1(){
        dump('test1');
    }  
    public function test2(){
        dump('test2');
    }
}
