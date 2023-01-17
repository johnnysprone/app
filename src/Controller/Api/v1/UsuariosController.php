<?php
declare(strict_types=1);

namespace App\Controller\Api\v1;

use Cake\Event\EventInterface;

/**
 * UsuariosController
 *
 * Controller de gerenciamento dos usuários
 *
 * @property \App\Model\Table\UsuariosTable $Usuarios
 */
class UsuariosController extends ApiController
{
    /**
     * @inheritDoc
     */
    public $entity = 'usuario';

    /**
     * @inheritDoc
     */
    public $entities = 'usuarios';

    /**
     * beforeFilter callback.
     *
     * @param \Cake\Event\EventInterface $event Event.
     * @return \Cake\Http\Response|null|void
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
    }

    /**
     * Método perfil
     *
     * Altera os dados do usuário
     *
     * @return void
     */
    public function perfil(): void
    {
        $this->Authorization->skipAuthorization();
        $this->request->allowMethod('put');

        $usuario = $this->Usuarios->get(
            $this->Authentication->getIdentityData('id'),
            $this->filterData(
                (array)$this->request->getQuery('options', [])
            ) + $this->updateOptions
        );
        $usuario = $this->Usuarios->patchEntity($usuario, $this->request->getData());
        if ($this->Usuarios->save($usuario)) {
            $response = [
                'message' => __('Entity successfully changed'),
                $this->entity => $usuario,
            ];
            $this->viewBuilder()->setOption('serialize', [
                'message', $this->entity,
            ]);
        } else {
            $this->response = $this->response->withStatus(400);
            $response = [
                'message' => __('Unable to change entity'),
                $this->entity => $usuario,
                'errors' => $usuario->getErrors(),
            ];
            $this->viewBuilder()->setOption('serialize', [
                'message', $this->entity, 'errors',
            ]);
        }
        $this->set($response);
    }
}
