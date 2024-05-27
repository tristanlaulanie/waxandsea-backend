<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'commande_create', methods: ['POST'])]
    public function createCommande(SessionInterface $session, ArticleRepository $articleRepository, EntityManagerInterface $entityManager, Request $request): Response
    {
        $panier = $session->get('panier', []);
        
        if (empty($panier)) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('panier_show');
        }

        $commande = new Commande();
        $commande->setUser($this->getUser());
        $total = 0;

        foreach ($panier as $item) {
            $article = $articleRepository->find($item['article']->getId());
            if ($article) {
                $commande->addArticle($article);
                $total += $article->getPrix() * $item['quantity'];
            }
        }

        $commande->setTotal($total);
        $commande->setCreatedAt(new \DateTime());
        $entityManager->persist($commande);
        $entityManager->flush();

        $session->remove('panier');

        $this->addFlash('success', 'Votre commande a été passée avec succès!');
        return $this->redirectToRoute('panier_show');
    }
}
