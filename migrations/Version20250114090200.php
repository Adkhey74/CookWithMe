<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250114090200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE step (id INT AUTO_INCREMENT NOT NULL, recipe_id_id INT NOT NULL, number VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, content VARCHAR(255) DEFAULT NULL, INDEX IDX_43B9FE3C69574A48 (recipe_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE step ADD CONSTRAINT FK_43B9FE3C69574A48 FOREIGN KEY (recipe_id_id) REFERENCES recipe (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE step DROP FOREIGN KEY FK_43B9FE3C69574A48');
        $this->addSql('DROP TABLE step');
    }
}
