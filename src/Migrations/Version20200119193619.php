<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200119193619 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category (id INT NOT NULL, event_id INT DEFAULT NULL, name VARCHAR(180) NOT NULL, INDEX IDX_64C19C171F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE owner (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(250) DEFAULT NULL, lastname VARCHAR(250) DEFAULT NULL, email VARCHAR(250) NOT NULL, password VARCHAR(250) NOT NULL, UNIQUE INDEX UNIQ_CF60E67CE7927C74 (email), UNIQUE INDEX UNIQ_CF60E67C35C246D5 (password), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, name VARCHAR(180) NOT NULL, given_name VARCHAR(180) DEFAULT NULL, family_name VARCHAR(180) DEFAULT NULL, picture_url VARCHAR(999) DEFAULT NULL, locale VARCHAR(5) DEFAULT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parameter (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(180) NOT NULL, value JSON NOT NULL, UNIQUE INDEX UNIQ_2A9791105E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT NOT NULL, tournament_id INT DEFAULT NULL, team_name VARCHAR(250) NOT NULL, owner_last_name VARCHAR(250) NOT NULL, owner_first_name VARCHAR(250) NOT NULL, owner_email VARCHAR(250) NOT NULL, INDEX IDX_C4E0A61F33D1A3E7 (tournament_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournament (id INT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(250) NOT NULL, group_size INT NOT NULL, participants INT NOT NULL, quotas INT NOT NULL, date_start DATETIME DEFAULT NULL, date_end DATETIME DEFAULT NULL, custom_fields JSON DEFAULT NULL, INDEX IDX_BD5FB8D912469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id INT NOT NULL, team_id INT DEFAULT NULL, tournament_id INT DEFAULT NULL, firstname VARCHAR(250) DEFAULT NULL, lastname VARCHAR(250) DEFAULT NULL, email VARCHAR(250) NOT NULL, owner VARCHAR(250) NOT NULL, pseudo VARCHAR(250) DEFAULT NULL, birthdate DATE DEFAULT NULL, identifiant_compte VARCHAR(250) DEFAULT NULL, INDEX IDX_98197A65296CD8AE (team_id), INDEX IDX_98197A6533D1A3E7 (tournament_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE log (id INT AUTO_INCREMENT NOT NULL, created_id INT NOT NULL, created_email VARCHAR(180) NOT NULL, created_name VARCHAR(180) DEFAULT NULL, created_date DATETIME NOT NULL, deleted_id INT DEFAULT NULL, deleted_email VARCHAR(180) DEFAULT NULL, deleted_name VARCHAR(180) DEFAULT NULL, deleted_date DATETIME DEFAULT NULL, event_id INT NOT NULL, ticket_id INT NOT NULL, participantid INT NOT NULL, hash VARCHAR(64) DEFAULT NULL, UNIQUE INDEX UNIQ_8F3F68C536802B0F (participantid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT NOT NULL, name VARCHAR(180) NOT NULL, date_start DATETIME NOT NULL, date_end DATETIME NOT NULL, status INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C171F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tournament ADD CONSTRAINT FK_BD5FB8D912469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A6533D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tournament DROP FOREIGN KEY FK_BD5FB8D912469DE2');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A65296CD8AE');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F33D1A3E7');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A6533D1A3E7');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C171F7E88B');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE owner');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE parameter');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE tournament');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE log');
        $this->addSql('DROP TABLE event');
    }
}
