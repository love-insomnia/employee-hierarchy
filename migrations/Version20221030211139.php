<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221030211139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Миграция создания таблицы сотрудников';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
        CREATE TABLE employee (
            id SERIAL PRIMARY KEY,
            parent_id INT, 
            name VARCHAR(255) NOT NULL 
        )
        ');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('employee');
    }
}
