<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SessoesFixture
 */
class SessoesFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => '932d51c6-ac9e-432d-8c1f-46929f9f1b12',
                'expiracao' => '2022-12-27 18:33:05',
                'usuario_id' => 1,
                'dados' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'criacao' => '2022-12-27 18:33:05',
                'modificacao' => '2022-12-27 18:33:05',
            ],
        ];
        parent::init();
    }
}
