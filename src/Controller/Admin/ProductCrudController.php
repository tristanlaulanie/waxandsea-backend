<?php

// Définition de l'espace de noms pour ce contrôleur, situé dans le dossier Admin
namespace App\Controller\Admin;

// Importation des classes nécessaires depuis leurs espaces de noms respectifs
use App\Entity\Product; // Entité Product
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController; // Classe de base pour les contrôleurs CRUD
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField; // Champ pour l'ID
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField; // Champ pour les textes
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField; // Champ pour les montants monétaires
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField; // Champ pour les dates

// Déclaration de la classe ProductCrudController qui étend AbstractCrudController pour bénéficier de ses fonctionnalités CRUD
class ProductCrudController extends AbstractCrudController
{
    // Méthode statique retournant le nom complet de la classe de l'entité associée à ce contrôleur CRUD
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    // Méthode pour configurer les champs à afficher dans les formulaires CRUD générés par EasyAdmin
    public function configureFields(string $pageName): iterable
    {
        return [
            // Champ 'id' caché dans le formulaire pour éviter les modifications manuelles
            IdField::new('id')->hideOnForm(),
            // Champ 'name' pour le nom du produit, avec un libellé 'Name'
            TextField::new('name', 'Name'),
            // Champ 'description' pour la description du produit, avec un libellé 'Description'
            TextField::new('description', 'Description'),
            // Champ 'price' pour le prix du produit, avec la devise EUR et un libellé 'Price'
            MoneyField::new('price', 'Price')->setCurrency('EUR'),
            // Champ 'imageUrl' pour l'URL de l'image du produit, utilisant un champ TextField avec un libellé 'Image URL'
            TextField::new('imageUrl', 'Image URL'),
            // Champ 'createdAt' pour la date de création du produit, caché dans le formulaire et avec un libellé 'Created At'
            DateTimeField::new('createdAt', 'Created At')->hideOnForm(),
        ];
    }
}