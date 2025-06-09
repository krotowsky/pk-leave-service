<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250609131554 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE customfielddefinition ALTER COLUMN id TYPE UUID USING id::uuid
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE customfielddefinition ALTER COLUMN id TYPE UUID USING id::uuid
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE customfielddefinition ALTER type TYPE VARCHAR(255)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE customfielddefinition RENAME COLUMN validationrules TO config
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN customfielddefinition.id IS '(DC2Type:uuid)'
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE CustomFieldDefinition ALTER id TYPE VARCHAR(50)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE CustomFieldDefinition ALTER type TYPE VARCHAR(50)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE CustomFieldDefinition RENAME COLUMN config TO validationrules
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN CustomFieldDefinition.id IS NULL
        SQL);
    }
}
