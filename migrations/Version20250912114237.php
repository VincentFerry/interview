<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250912114237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__recipe AS SELECT id, name, content, calories FROM recipe');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('CREATE TABLE recipe (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, content CLOB NOT NULL, calories DOUBLE PRECISION DEFAULT NULL)');
        $this->addSql('INSERT INTO recipe (id, name, content, calories) SELECT id, name, content, calories FROM __temp__recipe');
        $this->addSql('DROP TABLE __temp__recipe');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__recipe AS SELECT id, name, content, calories FROM recipe');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('CREATE TABLE recipe (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, content CLOB NOT NULL, calories INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO recipe (id, name, content, calories) SELECT id, name, content, calories FROM __temp__recipe');
        $this->addSql('DROP TABLE __temp__recipe');
    }
}
