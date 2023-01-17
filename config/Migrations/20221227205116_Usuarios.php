<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class Usuarios extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('usuarios');

        $table
            ->addColumn('nome', 'string', ['null' => false])
            ->addColumn('email', 'string', ['null' => false])
            ->addIndex(['email'], [
                'name' => 'unique_email',
                'unique' => true,
            ])
            ->addColumn('senha', 'string', [
                'default' => '$2y$10$E2m6q6s65lSaQYHWjMlbHe4FUhD3eCV.GtLwq.rHompVh/c9j7qGG',
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'comment' => '0- Inativo | 1- Ativo',
                'default' => 0,
                'null' => false,
            ])
            ->addColumn('criacao', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'null' => false,
            ])
            ->addColumn('modificacao', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'null' => false,
            ])
            ->addIndex(['nome', 'email'], [
                'name' => 'fulltext_search',
                'type' => 'fulltext',
            ])
            ->create();
    }
}
