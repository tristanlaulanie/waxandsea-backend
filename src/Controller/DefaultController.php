<?php

namespace App\Controller;

// Importation du UserRepository pour accéder aux données des utilisateurs
use App\Repository\UserRepository;
// Importation des classes nécessaires pour le contrôleur
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    // Définition de la route principale ('/') avec le nom 'app_default'
    #[Route('/', name: 'app_default')]
    public function index(UserRepository $userRepository): Response
    {
        // Récupération de tous les utilisateurs inscrits
        $utilisateurInscrit = $userRepository->findAll();

        // Rendu de la vue 'default/index.html.twig' avec les données des utilisateurs
        return $this->render('default/index.html.twig', [
            // Passage du nom du contrôleur à la vue (utilisé pour l'affichage)
            'controller_name' => 'DefaultController',
            // Passage de la liste des utilisateurs inscrits à la vue
            'utilisateurInscrit' => $utilisateurInscrit
        ]);
    }

}