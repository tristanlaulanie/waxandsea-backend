<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    public function index(UserRepository $userRepository): Response
    {
        $utilisateurInscrit = $userRepository->findAll();

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'utilisateurInscrit' => $utilisateurInscrit
        ]);
    }

    #[Route('/produit', name: 'app_product')]
    public function product(ProductRepository $productRepository): Response
    {
        // Récupérer tous les produits depuis la base de données
        $products = $productRepository->findAll();

        // Retourner la vue avec les produits
        return $this->render('default/product.html.twig', [
            'products' => $products,
        ]);
    }
}
