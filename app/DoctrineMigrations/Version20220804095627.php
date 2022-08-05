<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220804095627 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE card DROP INDEX IDX_161498D39395C3F3, ADD UNIQUE INDEX UNIQ_161498D39395C3F3 (customer_id)');
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D39395C3F3');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE card DROP INDEX UNIQ_161498D39395C3F3, ADD INDEX IDX_161498D39395C3F3 (customer_id)');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D39395C3F3 FOREIGN KEY (customer_id) REFERENCES dtb_customer (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
