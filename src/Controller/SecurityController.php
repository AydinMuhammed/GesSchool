<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SecurityController extends AbstractController
{
 
    // pour rediriger la route apres la connexion de pas oublier 
    // default_target_path: app_ecole 
    // Dans security.yaml

    // pour rediriger la route apres la déconnexion de pas oublier
    // target: app_login
    // Dans security.yaml
    //Code suivant en commentaire 

    //firewalls:
    //    main:
    //        lazy: true
    //        provider: app_user_provider
    //        form_login:
    //            login_path: app_login
    //            check_path: app_login
    //            default_target_path: app_ecole
    //        logout:
    //            path: app_logout  
    //            target: app_login



    #[Route('/connexion', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils, UrlGeneratorInterface $urlGenerator): Response
    {
        // Récupérer l'utilisateur actuellement connecté
        $user = $this->getUser();

        // Vérifier si l'utilisateur est connecté
        if ($user) {
            // Rediriger vers la page "/ecole"
            return new RedirectResponse($urlGenerator->generate('app_ecole'));
        }

        return $this->render('security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error'=> $authenticationUtils->getLastAuthenticationError()

        ]);
    }

    #[Route('/deconnexion', name: 'app_logout')]
    public function logout(UrlGeneratorInterface $urlGenerator): RedirectResponse
    {
        // Redirige vers la page d'accueil
        return $this->redirectToRoute('app_accueil');
    }

}    
