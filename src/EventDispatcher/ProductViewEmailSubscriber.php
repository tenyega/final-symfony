<?php

namespace App\EventDispatcher;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareTrait;
use App\Event\ProductViewEvent;
use Psr\Log\LoggerAwareInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class ProductViewEmailSubscriber implements EventSubscriberInterface
{

    protected $logger;
    protected $mailer;

    public function __construct(LoggerInterface $logger = null, MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            'product.view' => 'sendEmail'
        ];
    }

    public function sendEmail(ProductViewEvent $productViewEvent)
    {
        // $email = new Email();
        // $email->from(new Address("contact@mail.com", "information du mail"))
        //     ->to("admin@mail.com")
        //     ->text("Vous avez Un visiteur pour le produit no" . $productViewEvent->getProduct()->getId())
        //     ->html("<h1> Une Visite pour {$productViewEvent->getProduct()->getId()}</h1>")
        //     ->subject("un visite pour produit " . $productViewEvent->getProduct()->getId());


        // Working model of our mail in mailtrap.io
        // $email = new TemplatedEmail();
        // $email->from(new Address("contact@mail.com", "information du mail"))
        //     ->to("admin@mail.com")
        //     ->text("Vous avez Un visiteur pour le produit no" . $productViewEvent->getProduct()->getId())
        //     ->htmlTemplate('emails/product_view.html.twig')
        //     ->context([
        //         'product' => $productViewEvent->getProduct()
        //     ])
        //     ->subject("un visite pour produit " . $productViewEvent->getProduct()->getId());


        // $this->mailer->send($email);

        $this->logger->info('Product Event has been started for the product' . $productViewEvent->getProduct()->getId());
    }
}
