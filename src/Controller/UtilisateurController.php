<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Définit une route de base pour toutes les actions dans ce contrôleur
#[Route('/utilisateur')]
class UtilisateurController extends AbstractController
{
    // Affiche la liste des utilisateurs
    #[Route('/', name: 'app_utilisateur_index', methods: ['GET'])]
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        // Rend la vue avec la liste de tous les utilisateurs
        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    }

    // Crée un nouvel utilisateur
    #[Route('/new', name: 'app_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = new Utilisateur(); // Crée une nouvelle instance d'Utilisateur
        $form = $this->createForm(UtilisateurType::class, $utilisateur); // Crée le formulaire pour l'Utilisateur
        $form->handleRequest($request); // Gère la requête HTTP

        // Vérifie si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($utilisateur); // Prépare l'objet Utilisateur pour la persistance
            $entityManager->flush(); // Enregistre l'objet Utilisateur dans la base de données

            // Redirige vers la liste des utilisateurs
            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        // Rend la vue du formulaire pour créer un nouvel utilisateur
        return $this->render('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    // Affiche un utilisateur spécifique
    #[Route('/{id}', name: 'app_utilisateur_show', methods: ['GET'])]
    public function show(Utilisateur $utilisateur): Response
    {
        // Rend la vue de l'utilisateur spécifique
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    // Modifie un utilisateur existant
    #[Route('/{id}/edit', name: 'app_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UtilisateurType::class, $utilisateur); // Crée le formulaire pour l'Utilisateur
        $form->handleRequest($request); // Gère la requête HTTP

        // Vérifie si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush(); // Enregistre les modifications dans la base de données

            // Redirige vers la liste des utilisateurs
            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        // Rend la vue du formulaire pour modifier un utilisateur existant
        return $this->render('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    // Supprime un utilisateur
    #[Route('/{id}', name: 'app_utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        // Vérifie le jeton CSRF pour éviter les suppressions de formulaire cross-site
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($utilisateur); // Prépare l'objet Utilisateur pour la suppression
            $entityManager->flush(); // Supprime l'objet Utilisateur de la base de données
        }

        // Redirige vers la liste des utilisateurs
        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }
}