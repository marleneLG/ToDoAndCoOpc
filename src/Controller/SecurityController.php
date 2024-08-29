<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function loginAction(AuthenticationUtils $authenticationUtils): Response
    {
        // 1. REDIRECT USER IF USER IS CONNECTED
        if ($this->getUser()) {
            return $this->redirectToRoute('homepage');
        }
        //2. get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        //3. last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));
    }

    /**
     * @Route("/logout", name="logout")
     */
    #[Route('/logout', name: 'logout')]
    public function logoutCheck(): Exception
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}
