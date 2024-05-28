<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240528141043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE registration (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(15) NOT NULL, street VARCHAR(255) NOT NULL, streetnumber INT NOT NULL, town VARCHAR(255) NOT NULL, zipcode VARCHAR(10) NOT NULL, country VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_62A8A7A7E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE enregistrement');
        $this->addSql('ALTER TABLE panier_product MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON panier_product');
        $this->addSql('ALTER TABLE panier_product ADD panier_id INT NOT NULL, ADD product_id INT NOT NULL, DROP id');
        $this->addSql('ALTER TABLE panier_product ADD CONSTRAINT FK_29F0C02CF77D927C FOREIGN KEY (panier_id) REFERENCES panier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier_product ADD CONSTRAINT FK_29F0C02C4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_29F0C02CF77D927C ON panier_product (panier_id)');
        $this->addSql('CREATE INDEX IDX_29F0C02C4584665A ON panier_product (product_id)');
        $this->addSql('ALTER TABLE panier_product ADD PRIMARY KEY (panier_id, product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE enregistrement (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, lastname VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, telephone VARCHAR(15) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, street VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, streetnumber INT NOT NULL, town VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, zipcode VARCHAR(10) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, country VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, UNIQUE INDEX email (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE registration');
        $this->addSql('ALTER TABLE panier_product DROP FOREIGN KEY FK_29F0C02CF77D927C');
        $this->addSql('ALTER TABLE panier_product DROP FOREIGN KEY FK_29F0C02C4584665A');
        $this->addSql('DROP INDEX IDX_29F0C02CF77D927C ON panier_product');
        $this->addSql('DROP INDEX IDX_29F0C02C4584665A ON panier_product');
        $this->addSql('ALTER TABLE panier_product ADD id INT AUTO_INCREMENT NOT NULL, DROP panier_id, DROP product_id, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
    }
}
