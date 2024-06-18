<?php

namespace App\Controller;

// Importation des classes nécessaires
use App\Entity\Commande;
use App\Repository\ArticleRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CommandeController extends AbstractController
{
    // Route pour créer une commande, accessible via une requête POST
    #[Route('/commande', name: 'commande_create', methods: ['POST'])]
    public function createCommande(SessionInterface $session, ArticleRepository $articleRepository, EntityManagerInterface $entityManager, Request $request): Response
    {
        // Récupération du panier depuis la session
        $panier = $session->get('panier', []);

        // Vérification si le panier est vide
        if (empty($panier)) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('panier_show');
        }

        // Création d'une nouvelle commande
        $commande = new Commande();
        $commande->setUser($this->getUser()); // Associe l'utilisateur à la commande
        $total = 0;

        // Parcours des articles dans le panier pour les ajouter à la commande
        foreach ($panier as $item) {
            $article = $articleRepository->find($item['article']->getId());
            if ($article) {
                $commande->addArticle($article);
                $total += $article->getPrix() * $item['quantity'];
            }
        }

        // Définition du total de la commande et de la date de création
        $commande->setTotal($total);
        $commande->setCreatedAt(new \DateTime());
        // Persistance de la commande dans la base de données
        $entityManager->persist($commande);
        $entityManager->flush();

        // Suppression du panier de la session
        $session->remove('panier');

        // Notification de succès et redirection
        $this->addFlash('success', 'Votre commande a été passée avec succès!');
        return $this->redirectToRoute('panier_show');
    }

    // Route pour initier le paiement d'une commande, accessible via une requête POST
    #[Route('/commande/initier-paiement', name: 'commande_initiate_payment', methods: ['POST'])]
    public function initierPaiement(SessionInterface $session, ArticleRepository $articleRepository, EntityManagerInterface $entityManager): Response
    {
        // Récupération du panier depuis la session
        $panier = $session->get('panier', []);

        // Vérification si le panier est vide
        if (empty($panier)) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('panier_show');
        }

        // Création d'une nouvelle commande avec statut en attente
        $commande = new Commande();
        $commande->setUser($this->getUser());
        $commande->setStatut('pending');
        $total = 0;

        $lineItems = [];

        // Parcours des articles dans le panier pour les ajouter à la commande et préparer les données pour Stripe
        foreach ($panier as $item) {
            $article = $articleRepository->find($item['article']->getId());
            if ($article) {
                $commande->addArticle($article);
                $total += $article->getPrix() * $item['quantity'];

                // Préparation des données des articles pour Stripe
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $article->getTitre(),
                        ],
                        'unit_amount' => $article->getPrix() * 100,
                    ],
                    'quantity' => $item['quantity'],
                ];
            }
        }

        // Définition du total de la commande et de la date de création
        $commande->setTotal($total);
        $commande->setCreatedAt(new \DateTime());
        // Persistance de la commande dans la base de données
        $entityManager->persist($commande);
        $entityManager->flush();

        // Configuration de l'API Stripe avec la clé secrète
        Stripe::setApiKey('sk_test_51PL3AmCy78PEFla7afKmrvQvoSxdqD2mCK4PfQ7FwZmvNAqsIKqkSPqRHyZqVqaJxQgqm5mAQFp134Q2RfbxCgVb00Ppf1liFE');

        try {
            // Création de la session de paiement Stripe
            $checkoutSession = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                // URL de redirection en cas de succès ou d'annulation du paiement
                'success_url' => $this->generateUrl('payment_success', ['id' => $commande->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
                'cancel_url' => $this->generateUrl('payment_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
            ]);

            // Enregistrement de l'ID de session Stripe dans la commande
            $commande->setStripeSessionId($checkoutSession->id);
            $entityManager->flush();

            // Retourne l'ID de session Stripe pour le paiement
            return new JsonResponse(['id' => $checkoutSession->id]);
        } catch (\Exception $e) {
            // Gestion des erreurs lors de la création de la session de paiement
            $this->addFlash('error', 'Erreur lors de la création de la session de paiement: ' . $e->getMessage());
            return $this->redirectToRoute('panier_show');
        }
    }


    // Définition d'une route pour gérer le succès du paiement
    #[Route('/commande/success', name: 'payment_success')]
    public function paymentSuccess(EntityManagerInterface $entityManager, CommandeRepository $commandeRepository, Request $request): Response
    {
        // Récupération de l'ID de session Stripe depuis la requête
        $sessionId = $request->get('session_id');
        // Recherche de la commande associée à l'ID de session Stripe
        $commande = $commandeRepository->findOneBy(['stripeSessionId' => $sessionId]);
    
        // Si la commande est trouvée, mise à jour de son statut à 'paid' (payée)
        if ($commande) {
            $commande->setStatut('paid');
            // Enregistrement des modifications dans la base de données
            $entityManager->flush();
            // Notification de succès
            $this->addFlash('success', 'Votre paiement a été effectué avec succès!');
        } else {
            // Si la commande n'est pas trouvée, notification d'erreur
            $this->addFlash('error', 'La commande n\'a pas été trouvée.');
        }
    
        // Redirection vers l'affichage du panier
        return $this->redirectToRoute('panier_show');
    }
    
    // Définition d'une route pour gérer l'annulation du paiement
    #[Route('/commande/cancel', name: 'payment_cancel')]
    public function paymentCancel(): Response
    {
        // Notification d'erreur pour l'annulation du paiement
        $this->addFlash('error', 'Le paiement a été annulé.');
        // Redirection vers l'affichage du panier
        return $this->redirectToRoute('panier_show');
    }
}
