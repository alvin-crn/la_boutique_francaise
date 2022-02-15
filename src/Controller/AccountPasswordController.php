<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountPasswordController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/compte/modifier-mon-mdp', name: 'account_password')]
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response
    {

        $notif = null;

        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $old_psw = $form->get('old_password')->getData();

            if ($encoder->isPasswordValid($user, $old_psw)) {
                $new_psw = $form->get('new_password')->getData();
                $password = password_hash($new_psw, PASSWORD_DEFAULT);
                $user->setPassword($password);
                $this->entityManager->flush();
                $notif = 'Votre mot de passe a bien été mise à jour.';
            } else {
                $notif = 'Votre mot de passe actuel n\'est pas le bon';
            }
        }


        $param = [
            'form' => $form->createView(),
            'notif' => $notif
        ];
        return $this->render('account/password.html.twig', $param);
    }
}
