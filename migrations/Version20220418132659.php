<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220418132659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course ADD classe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB98F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id)');
        $this->addSql('CREATE INDEX IDX_169E6FB98F5EA509 ON course (classe_id)');
        $this->addSql('ALTER TABLE students_grades ADD classe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE students_grades ADD CONSTRAINT FK_803F44728F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id)');
        $this->addSql('CREATE INDEX IDX_803F44728F5EA509 ON students_grades (classe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB98F5EA509');
        $this->addSql('DROP INDEX IDX_169E6FB98F5EA509 ON course');
        $this->addSql('ALTER TABLE course DROP classe_id');
        $this->addSql('ALTER TABLE students_grades DROP FOREIGN KEY FK_803F44728F5EA509');
        $this->addSql('DROP INDEX IDX_803F44728F5EA509 ON students_grades');
        $this->addSql('ALTER TABLE students_grades DROP classe_id');
    }
}
