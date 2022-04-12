<?php

namespace App\EventDispatcher;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Email;
use App\Event\PurchaseSuccessEvent;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PurchaseSuccessEmailSubscriber implements EventSubscriberInterface
{
    protected $logger;
    protected $mailer;
    protected $security;

    public function __construct(LoggerInterface $logger, MailerInterface $mailer, Security $security)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            'purchase.success' => 'sendSuccessEmail'
        ];
    }
    public function sendSuccessEmail(PurchaseSuccessEvent $purchaseSuccessEvent)
    {


        // 1. get user connected - to get his email id. - as we are not in abstractcontroller thats why Security
        /**@var User */
        $currentUser = $this->security->getUser();
        if (!$currentUser) {
            return;
        }
        $userEmail = $currentUser->getEmail();

        //2. get the commande  PurchaseSuccessEvent has the purchase
        $purchase = $purchaseSuccessEvent->getPurchase();

        //3. write a mail nouveau templateEmail
        $email = new TemplatedEmail();
        $email->to(new Address($userEmail, $currentUser->getFullName()))
            ->from("contact@mail.com")
            ->text("You have a new notification of a reservation")
            ->htmlTemplate("emails/purchase_success.html.twig")
            ->context([
                'purchase' => $purchase,
                'user' => $currentUser
            ])
            ->subject("Bravo votre commande ({$purchase->getId()}) a été enregistrée");

        // 4. send a mail mailer Interface 
        $this->mailer->send($email);


        $this->logger->notice('Email envoyé pour la commande no ' . $purchaseSuccessEvent->getPurchase()->getId());
    }
}
