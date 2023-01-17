<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class Sessoes extends AbstractMigration
{
    /**
     * @inheritDoc
     */
    public $autoId = false;

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
        $table = $this->table('sessoes');

        $table
            ->addColumn('id', 'uuid', ['null' => false])
            ->addColumn('expiracao', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'null' => false,
            ])
            ->addColumn('usuario_id', 'integer', [
                'null' => false,
            ])
            ->addColumn('dados', 'text', ['null' => false])
            ->addColumn('criacao', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'null' => false,
            ])
            ->addColumn('modificacao', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'null' => false,
            ])
            ->addPrimaryKey('id')
            ->addForeignKey(['usuario_id'], 'usuarios')
            ->create();
    }
}
