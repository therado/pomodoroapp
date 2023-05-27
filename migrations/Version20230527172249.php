<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230527172249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pomodoro_session ADD author_id INT NOT NULL');
        $this->addSql('ALTER TABLE pomodoro_session ADD CONSTRAINT FK_6FFF4BB2F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6FFF4BB2F675F31B ON pomodoro_session (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pomodoro_session DROP FOREIGN KEY FK_6FFF4BB2F675F31B');
        $this->addSql('DROP INDEX IDX_6FFF4BB2F675F31B ON pomodoro_session');
        $this->addSql('ALTER TABLE pomodoro_session DROP author_id');
    }
}
