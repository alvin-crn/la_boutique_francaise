<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/inscription', name: 'register')]
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $notif = null;

        $user = new User ();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $search_email = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());

            if(!$search_email) {
                $password = password_hash($user->getPassword(),PASSWORD_DEFAULT);
                $user->setPassword($password);
                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $mail = new Mail();
                $content = "Bonjour ".$user->getFirstname()."<br>Bienvenue sur la premiere boutique 100% Made in France.";
                $mail->send($user->getEmail(), $user->getFirstname(), 'Bienvenue sur LBF', $content);

                $notif = "Votre inscription a bien été pris en compte. Vous pouvez desormais vous connecter à votre compte";
            } else {
                $notif = "Email déjà existante, veuillez en utiliser une autre.";
            }
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'notif' => $notif, 
        ]);
    }
}
