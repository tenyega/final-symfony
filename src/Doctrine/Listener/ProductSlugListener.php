<?php

namespace App\Doctrine\Listener;

use App\Entity\Product;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductSlugListener
{

    protected $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Product $entity, LifecycleEventArgs $event)
    {
        //  dd("ca marche");
        $entity = $event->getObject();

        //checking entity if its an instance of Product is not needed for the entity listener 
        // if (!$entity instanceof Product) {
        //     return;
        // }
        if (empty($entity->getSlug())) {
            $entity->setSlug(strtolower($this->slugger->slug($entity->getName())));
        }
    }
}
