<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * Usuarios seed.
 */
class UsuariosSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     *
     * @return void
     */
    public function run(): void
    {
        $data = [
            [
                'nome' => 'Johnny Alberto Sprone',
                'email' => 'johnnysprone@hotmail.com',
                'status' => 1,
            ],
        ];

        $table = $this->table('usuarios');
        $table->insert($data)->save();
    }
}
