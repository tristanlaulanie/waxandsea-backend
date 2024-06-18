<?php

// Définition de l'espace de noms pour ce contrôleur, situé dans le dossier Admin
namespace App\Controller\Admin;

// Importation des classes nécessaires depuis leurs espaces de noms respectifs
use App\Entity\Utilisateur; // Entité Utilisateur
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController; // Classe de base pour les contrôleurs CRUD
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField; // Champ pour les textes
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField; // Champ pour les emails
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField; // Champ pour les tableaux
use Symfony\Component\Form\Extension\Core\Type\PasswordType; // Type de champ pour les mots de passe
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface; // Interface pour le hachage des mots de passe
use Doctrine\ORM\EntityManagerInterface; // Gestionnaire d'entités

// Déclaration de la classe UtilisateurCrudController qui étend AbstractCrudController pour bénéficier de ses fonctionnalités CRUD
class UtilisateurCrudController extends AbstractCrudController
{
    private $passwordHasher; // Variable pour stocker le service de hachage des mots de passe

    // Constructeur pour injecter le service de hachage des mots de passe
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    // Méthode statique retournant le nom complet de la classe de l'entité associée à ce contrôleur CRUD
    public static function getEntityFqcn(): string
    {
        return Utilisateur::class;
    }

    // Méthode pour configurer les champs à afficher dans les formulaires CRUD générés par EasyAdmin
    public function configureFields(string $pageName): iterable
    {
        return [
            // Configuration des champs avec leurs libellés respectifs
            TextField::new('firstname', 'Prénom'),
            TextField::new('lastname', 'Nom de famille'),
            EmailField::new('email', 'Email'),
            TextField::new('telephone', 'Téléphone'),
            TextField::new('street', 'Rue'),
            TextField::new('streetnumber', 'Numéro de rue'),
            TextField::new('town', 'Ville'),
            TextField::new('zipcode', 'Code postal'),
            TextField::new('country', 'Pays'),
            ArrayField::new('roles', 'Rôles'),
            // Champ mot de passe configuré pour utiliser le type PasswordType et être affiché uniquement lors de la création
            TextField::new('password', 'Mot de passe')
                ->setFormType(PasswordType::class)
                ->onlyWhenCreating(),
        ];
    }

    // Méthode pour persister une nouvelle entité, avec hachage du mot de passe si défini
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Utilisateur) return;

        // Hachage du mot de passe avant la persistance si un mot de passe en clair est défini
        if ($entityInstance->getPlainPassword()) {
            $hashedPassword = $this->passwordHasher->hashPassword(
                $entityInstance,
                $entityInstance->getPlainPassword()
            );
            $entityInstance->setPassword($hashedPassword);
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    // Méthode pour mettre à jour une entité, avec hachage du mot de passe si défini
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Utilisateur) return;

        // Hachage du mot de passe avant la mise à jour si un mot de passe en clair est défini
        if ($entityInstance->getPlainPassword()) {
            $hashedPassword = $this->passwordHasher->hashPassword(
                $entityInstance,
                $entityInstance->getPlainPassword()
            );
            $entityInstance->setPassword($hashedPassword);
        }

        parent::updateEntity($entityManager, $entityInstance);
    }
}