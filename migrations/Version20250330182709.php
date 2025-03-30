<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250330182709 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE summary ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE summary ADD CONSTRAINT FK_CE286663A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CE286663A76ED395 ON summary (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE summary DROP FOREIGN KEY FK_CE286663A76ED395');
        $this->addSql('DROP INDEX IDX_CE286663A76ED395 ON summary');
        $this->addSql('ALTER TABLE summary DROP user_id');
    }
}
