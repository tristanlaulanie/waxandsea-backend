<?php

namespace App\Controller\Admin;

// Importation des entités nécessaires
use App\Entity\Article;
use App\Entity\Product;
use App\Entity\Utilisateur;
use App\Entity\Commande;
// Importation des composants EasyAdmin pour la configuration du tableau de bord
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Définition de la classe du contrôleur du tableau de bord administratif
class DashboardController extends AbstractDashboardController
{
    // Définition de la route principale de l'administration
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Génération de l'URL pour le contrôleur CRUD de l'article
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(ArticleCrudController::class)->generateUrl());
    }

    // Configuration du tableau de bord
    public function configureDashboard(): Dashboard
    {
        // Définition du titre du tableau de bord
        return Dashboard::new()
            ->setTitle('Siteconnexion');
    }

    // Configuration des éléments du menu
    public function configureMenuItems(): iterable
    {
        // Lien vers le tableau de bord
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // Liens vers les CRUD des différentes entités
        yield MenuItem::linkToCrud('Article', 'fas fa-list', Article::class);
        yield MenuItem::linkToCrud('Products', 'fas fa-box', Product::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', Utilisateur::class);
        yield MenuItem::linkToCrud('Commandes', 'fas fa-shopping-cart', Commande::class);
    }
}