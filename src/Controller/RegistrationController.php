<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\RegistrationFormType;
use App\Security\UsersAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

// Le contrôleur gère l'enregistrement des utilisateurs et la vérification des e-mails
class RegistrationController extends AbstractController
{
    // Route pour l'enregistrement d'un nouvel utilisateur
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = new User(); // Crée une nouvelle instance de l'entité User
        $form = $this->createForm(RegistrationFormType::class, $user); // Crée le formulaire d'enregistrement
        $form->handleRequest($request); // Gère la requête HTTP

        // Vérifie si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Encode le mot de passe en clair
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData() // Récupère le mot de passe en clair depuis le formulaire
                )
            );

            $entityManager->persist($user); // Prépare l'entité User pour la persistance
            $entityManager->flush(); // Enregistre l'entité User dans la base de données

            // Redirige vers la route 'app_default' après l'enregistrement
            return $this->redirectToRoute('app_default');

            // Code pour connecter l'utilisateur directement après l'enregistrement (non atteignable en raison du return précédent)
            return $security->login($user, UsersAuthenticator::class, 'main');
        }

        // Rendu du formulaire d'enregistrement
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    // Route pour la vérification de l'e-mail d'un utilisateur
    #[Route('/verify-email/{token}', name: 'app_verify_email')]
    public function verifyEmail(string $token, UserRepository $userRepository): Response
    {
        // Recherche de l'utilisateur par son token de vérification
        $user = $userRepository->findOneBy(['verificationToken' => $token]);

        // Si aucun utilisateur n'est trouvé avec ce token, renvoie une erreur 404
        if (!$user) {
            throw $this->createNotFoundException('No user found for verification token.');
        }

        // Marque l'utilisateur comme vérifié et supprime le token de vérification
        $user->setIsVerified(true);
        $user->setVerificationToken(null);

        // Ajoute un message flash de succès
        $this->addFlash('success', 'Your email has been verified! You can now log in.');

        // Redirige vers la route de connexion
        return $this->redirectToRoute('app_login');
    }
}