<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * SistemaController
 *
 * Controller de gerenciamento de rotas
 */
class SistemaController extends AppController
{
    /**
     * beforeFilter callback.
     *
     * @param \Cake\Event\EventInterface $event Event.
     * @return \Cake\Http\Response|null|void
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Authentication->allowUnauthenticated(['index']);
    }

    /**
     * Método index
     *
     * Redireciona as requisições do sistema
     *
     * @return void
     */
    public function index(): void
    {
        // Ignora a autorização
        $this->Authorization->skipAuthorization();
    }
}
