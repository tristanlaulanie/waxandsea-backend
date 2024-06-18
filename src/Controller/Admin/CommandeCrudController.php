<?php

// Définition de l'espace de noms pour ce contrôleur, situé dans le dossier Admin
namespace App\Controller\Admin;

// Importation des classes nécessaires depuis leurs espaces de noms respectifs
use App\Entity\Commande;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

// Déclaration de la classe CommandeCrudController qui étend AbstractCrudController pour bénéficier de ses fonctionnalités CRUD
class CommandeCrudController extends AbstractCrudController
{
    // Méthode statique retournant le nom complet de la classe de l'entité associée à ce contrôleur CRUD
    public static function getEntityFqcn(): string
    {
        return Commande::class;
    }

    // Méthode pour configurer les champs à afficher dans les formulaires CRUD générés par EasyAdmin
    public function configureFields(string $pageName): iterable
    {
        return [
            // Champ 'id' caché dans le formulaire pour éviter les modifications manuelles
            IdField::new('id')->hideOnForm(),
            // Champ 'user' représentant une association avec une autre entité, étiqueté 'Utilisateur'
            AssociationField::new('user')->setLabel('Utilisateur'),
            // Champ 'articles' également pour une association, caché dans l'index pour une meilleure lisibilité
            AssociationField::new('articles')->setLabel('Articles')->hideOnIndex(),
            // Champ 'total' représentant un montant d'argent, avec la devise EUR et un formatage personnalisé
            MoneyField::new('total')
                ->setCurrency('EUR')
                ->setLabel('Total (€)')
                ->formatValue(function ($value) {
                    return $value;
                }),
            // Champ 'createdAt' pour la date de création, avec un libellé personnalisé
            DateTimeField::new('createdAt')->setLabel('Date de création'),
        ];
    }
}