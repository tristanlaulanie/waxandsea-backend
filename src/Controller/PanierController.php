<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'panier_show')]
    public function showCart(SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);

        return $this->render('panier/show.html.twig', [
            'panier' => $panier,
        ]);
    }

    #[Route('/panier/add/{id}', name: 'panier_add', methods: ['POST'])]
    public function addToCart(SessionInterface $session, ArticleRepository $articleRepository, int $id, Request $request): Response
    {
        // Récupérer l'article à partir de l'ID
        $article = $articleRepository->find($id);

        if (!$article) {
            $message = 'L\'article n\'existe pas';
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(['message' => $message], Response::HTTP_NOT_FOUND);
            } else {
                $this->addFlash('error', $message);
                return $this->redirectToRoute('panier_show');
            }
        }

        // Récupérer la quantité
        $quantity = $request->request->get('quantity', 1);

        // Récupérer le panier de la session ou créer un nouveau panier
        $panier = $session->get('panier', []);

        // Ajouter l'article au panier ou mettre à jour la quantité s'il y est déjà
        if (isset($panier[$id])) {
            $panier[$id]['quantity'] += $quantity;
        } else {
            $panier[$id] = [
                'article' => $article,
                'quantity' => $quantity
            ];
        }

        // Mettre à jour la session
        $session->set('panier', $panier);

        $message = 'Article ajouté au panier!';
        if ($request->isXmlHttpRequest()) {
            // Retourner une réponse JSON pour les requêtes AJAX
            return new JsonResponse(['message' => $message], Response::HTTP_OK);
        } else {
            $this->addFlash('success', $message);
            return $this->redirectToRoute('panier_show');
        }
    }

    #[Route('/panier/vider', name: 'panier_vider', methods: ['POST'])]
    public function viderPanier(SessionInterface $session, Request $request): Response
    {
        $session->set('panier', []);
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(['message' => 'Panier vidé!'], Response::HTTP_OK);
        } else {
            $this->addFlash('success', 'Panier vidé!');
            return $this->redirectToRoute('panier_show');
        }
    }

    #[Route('/panier/remove/{id}', name: 'panier_remove', methods: ['POST'])]
    public function removeFromCart(SessionInterface $session, int $id, Request $request): Response
    {
        $panier = $session->get('panier', []);

        if (!isset($panier[$id])) {
            $message = 'L\'article n\'existe pas dans le panier';
            return $request->isXmlHttpRequest()
                ? new JsonResponse(['message' => $message], Response::HTTP_NOT_FOUND)
                : $this->redirectToRoute('panier_show', ['message' => $message]);
        }

        unset($panier[$id]);
        $session->set('panier', $panier);

        $total = array_reduce($panier, function ($carry, $item) {
            return $carry + ($item['article']->getPrix() * $item['quantity']);
        }, 0);

        $message = 'Article supprimé du panier!';
        return $request->isXmlHttpRequest()
            ? new JsonResponse(['message' => $message, 'total' => $total], Response::HTTP_OK)
            : $this->redirectToRoute('panier_show', ['message' => $message]);
    }

    #[Route('/panier/increase/{id}', name: 'panier_increase', methods: ['POST'])]
    public function increaseQuantity(SessionInterface $session, int $id): JsonResponse
    {
        $panier = $session->get('panier', []);
        if (isset($panier[$id]) && $panier[$id]['quantity'] < 10) {
            $panier[$id]['quantity']++;
            $session->set('panier', $panier);
            $totalItem = $panier[$id]['article']->getPrix() * $panier[$id]['quantity'];
            $totalPanier = array_reduce($panier, fn($carry, $item) => $carry + ($item['article']->getPrix() * $item['quantity']), 0);
            return new JsonResponse(['quantity' => $panier[$id]['quantity'], 'totalItem' => $totalItem, 'totalPanier' => $totalPanier]);
        }
        return new JsonResponse(['message' => 'Maximum quantity reached'], Response::HTTP_BAD_REQUEST);
    }

    #[Route('/panier/decrease/{id}', name: 'panier_decrease', methods: ['POST'])]
    public function decreaseQuantity(SessionInterface $session, int $id): JsonResponse
    {
        $panier = $session->get('panier', []);
        if (isset($panier[$id]) && $panier[$id]['quantity'] > 1) {
            $panier[$id]['quantity']--;
            $session->set('panier', $panier);
            $totalItem = $panier[$id]['article']->getPrix() * $panier[$id]['quantity'];
            $totalPanier = array_reduce($panier, fn($carry, $item) => $carry + ($item['article']->getPrix() * $item['quantity']), 0);
            return new JsonResponse(['quantity' => $panier[$id]['quantity'], 'totalItem' => $totalItem, 'totalPanier' => $totalPanier]);
        }
        return new JsonResponse(['message' => 'Minimum quantity reached'], Response::HTTP_BAD_REQUEST);
    }

    
}
