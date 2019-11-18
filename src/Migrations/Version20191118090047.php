<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191118090047 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sport_place (sport_id INT NOT NULL, place_id INT NOT NULL, INDEX IDX_C06132D4AC78BCF8 (sport_id), INDEX IDX_C06132D4DA6A219 (place_id), PRIMARY KEY(sport_id, place_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sport_place ADD CONSTRAINT FK_C06132D4AC78BCF8 FOREIGN KEY (sport_id) REFERENCES sport (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sport_place ADD CONSTRAINT FK_C06132D4DA6A219 FOREIGN KEY (place_id) REFERENCES place (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE place_sport');
        $this->addSql('ALTER TABLE review DROP updated_at, CHANGE rate rate INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE place_sport (place_id INT NOT NULL, sport_id INT NOT NULL, INDEX IDX_B3F3F4F1DA6A219 (place_id), INDEX IDX_B3F3F4F1AC78BCF8 (sport_id), PRIMARY KEY(place_id, sport_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE place_sport ADD CONSTRAINT FK_B3F3F4F1AC78BCF8 FOREIGN KEY (sport_id) REFERENCES sport (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE place_sport ADD CONSTRAINT FK_B3F3F4F1DA6A219 FOREIGN KEY (place_id) REFERENCES place (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE sport_place');
        $this->addSql('ALTER TABLE review ADD updated_at DATETIME DEFAULT NULL, CHANGE rate rate INT DEFAULT NULL');
    }
}
