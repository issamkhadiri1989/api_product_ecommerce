<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250501161832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE account_product (account_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_1A6110E59B6B5FBA (account_id), INDEX IDX_1A6110E54584665A (product_id), PRIMARY KEY(account_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE account_product ADD CONSTRAINT FK_1A6110E59B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE account_product ADD CONSTRAINT FK_1A6110E54584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE account_product DROP FOREIGN KEY FK_1A6110E59B6B5FBA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE account_product DROP FOREIGN KEY FK_1A6110E54584665A
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE account_product
        SQL);
    }
}
