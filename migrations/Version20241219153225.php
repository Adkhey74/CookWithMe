<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241219153225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient DROP FOREIGN KEY FK_6BAF78703CAF64A');
        $this->addSql('DROP INDEX IDX_6BAF78703CAF64A ON ingredient');
        $this->addSql('ALTER TABLE ingredient DROP recipe_ingredient_id');
        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B1373CAF64A');
        $this->addSql('DROP INDEX IDX_DA88B1373CAF64A ON recipe');
        $this->addSql('ALTER TABLE recipe DROP recipe_ingredient_id');
        $this->addSql('ALTER TABLE recipe_ingredient ADD recipe_id_id INT NOT NULL, ADD ingredient_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE recipe_ingredient ADD CONSTRAINT FK_22D1FE1369574A48 FOREIGN KEY (recipe_id_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE recipe_ingredient ADD CONSTRAINT FK_22D1FE136676F996 FOREIGN KEY (ingredient_id_id) REFERENCES ingredient (id)');
        $this->addSql('CREATE INDEX IDX_22D1FE1369574A48 ON recipe_ingredient (recipe_id_id)');
        $this->addSql('CREATE INDEX IDX_22D1FE136676F996 ON recipe_ingredient (ingredient_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient ADD recipe_ingredient_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF78703CAF64A FOREIGN KEY (recipe_ingredient_id) REFERENCES recipe_ingredient (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_6BAF78703CAF64A ON ingredient (recipe_ingredient_id)');
        $this->addSql('ALTER TABLE recipe_ingredient DROP FOREIGN KEY FK_22D1FE1369574A48');
        $this->addSql('ALTER TABLE recipe_ingredient DROP FOREIGN KEY FK_22D1FE136676F996');
        $this->addSql('DROP INDEX IDX_22D1FE1369574A48 ON recipe_ingredient');
        $this->addSql('DROP INDEX IDX_22D1FE136676F996 ON recipe_ingredient');
        $this->addSql('ALTER TABLE recipe_ingredient DROP recipe_id_id, DROP ingredient_id_id');
        $this->addSql('ALTER TABLE recipe ADD recipe_ingredient_id INT NOT NULL');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B1373CAF64A FOREIGN KEY (recipe_ingredient_id) REFERENCES recipe_ingredient (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_DA88B1373CAF64A ON recipe (recipe_ingredient_id)');
    }
}
