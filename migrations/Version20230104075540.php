<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230104075540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blogtag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blogtag_blog (blogtag_id INT NOT NULL, blog_id INT NOT NULL, INDEX IDX_B6A4B20BC005CC45 (blogtag_id), INDEX IDX_B6A4B20BDAE07E97 (blog_id), PRIMARY KEY(blogtag_id, blog_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blogtag_blog ADD CONSTRAINT FK_B6A4B20BC005CC45 FOREIGN KEY (blogtag_id) REFERENCES blogtag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blogtag_blog ADD CONSTRAINT FK_B6A4B20BDAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blogtag_blog DROP FOREIGN KEY FK_B6A4B20BC005CC45');
        $this->addSql('ALTER TABLE blogtag_blog DROP FOREIGN KEY FK_B6A4B20BDAE07E97');
        $this->addSql('DROP TABLE blogtag');
        $this->addSql('DROP TABLE blogtag_blog');
    }
}
