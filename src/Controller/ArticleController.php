<?php

namespace App\Controller;

// Importation des classes nécessaires
use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Définition de la route de base pour tous les chemins dans ce contrôleur
#[Route('/article')]
class ArticleController extends AbstractController
{
    // Route pour la page d'index des articles, accessible à l'URL /article/
    #[Route('/', name: 'app_article')]
    public function index(ArticleRepository $articleRepository): Response
    {
        // Récupère tous les articles et les passe à la vue
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    // Route pour afficher un article spécifique par son ID, accessible à l'URL /article/{id}
    // L'exigence '\d+' assure que l'ID est numérique
    #[Route('/{id}', name: 'app_article_show', requirements: ['id' => '\d+'])]
    public function show(Article $article): Response
    {
        // Passe l'article spécifique à la vue
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }
}