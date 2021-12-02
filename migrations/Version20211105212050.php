<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211105212050 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE borrow ADD books_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE borrow ADD users_id INT NOT NULL');
        $this->addSql('ALTER TABLE borrow ADD CONSTRAINT FK_55DBA8B07DD8AC20 FOREIGN KEY (books_id) REFERENCES book (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE borrow ADD CONSTRAINT FK_55DBA8B067B3B43D FOREIGN KEY (users_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_55DBA8B07DD8AC20 ON borrow (books_id)');
        $this->addSql('CREATE INDEX IDX_55DBA8B067B3B43D ON borrow (users_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE borrow DROP CONSTRAINT FK_55DBA8B07DD8AC20');
        $this->addSql('ALTER TABLE borrow DROP CONSTRAINT FK_55DBA8B067B3B43D');
        $this->addSql('DROP INDEX IDX_55DBA8B07DD8AC20');
        $this->addSql('DROP INDEX IDX_55DBA8B067B3B43D');
        $this->addSql('ALTER TABLE borrow DROP books_id');
        $this->addSql('ALTER TABLE borrow DROP users_id');
    }
}
