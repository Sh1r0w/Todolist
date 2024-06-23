<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
   /**
    * The function "login" in PHP retrieves the last authentication error and last username entered by
    * the user for rendering in a login template.
    * 
    * @param AuthenticationUtils authenticationUtils The `AuthenticationUtils` service in Symfony is
    * used to help with common authentication tasks. In the provided code snippet, the
    * `AuthenticationUtils` service is injected into the `login` method as a parameter.
    * 
    * @return Response The `login` method is returning a Response object that renders the
    * `login.html.twig` template with the last username entered by the user and any login error that
    * may have occurred. The last username and error are passed to the template as variables
    * `last_username` and `error`, respectively.
    */
    #[Route(path: '/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * The function logout() is a placeholder method that throws a LogicException and is intended to be
     * intercepted by the logout key on the firewall.
     */
    #[Route(path: '/logout', name: 'logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
