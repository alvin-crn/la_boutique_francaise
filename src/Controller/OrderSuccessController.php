<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderSuccessController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande/merci/{stripeSessionId}', name: 'order_success')]
    public function index(Cart $cart, $stripeSessionId): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        if(!$order || $order->getUser() != $this->getUser()) 
        {
            return $this->redirectToRoute('home');
        }
        
        if($order->getState() == 0)
        {
            //vider le panier
            $cart->remove();

            // Modifier le statut state  de notre commande à 1
            $order->setState(1);
            $this->entityManager->flush();

            // Envoyer un email à notre client pour lui confirmer sa commande
            $mail = new Mail();
            $content = "Bonjour ".$order->getUser()->getFirstname()."<br>Merci pour votre commande.";
            $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Confirmation de votre commande n°'.$order->getReference(), $content);

        }
        
        // Afficher les qlq info de l'utilisateur
        return $this->render('order_success/index.html.twig', [
            'order' => $order,
        ]);
    }
}
