<?php
declare(strict_types=1);

namespace App\Controller\Api\v1;

use Cake\Event\EventInterface;
use Cake\I18n\FrozenTime;

/**
 * SessoesController
 *
 * Controller de gerenciamento das sessões
 *
 * @property \App\Model\Table\SessoesTable $Sessoes
 */
class SessoesController extends ApiController
{
    /**
     * @inheritDoc
     */
    public $entity = 'sessao';

    /**
     * @inheritDoc
     */
    public $entities = 'sessoes';

    /**
     * beforeFilter callback.
     *
     * @param \Cake\Event\EventInterface $event Event.
     * @return \Cake\Http\Response|null|void
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Authentication->allowUnauthenticated(['login', 'logout', 'token']);
    }

    /**
     * Método login
     *
     * Inicia a sessão do usuário
     *
     * @return void
     */
    public function login(): void
    {
        $this->Authorization->skipAuthorization();
        $this->request->allowMethod('post');

        $identity = $this->Authentication->getResult();

        /** @var \App\Model\Entity\Usuario $usuario */
        $usuario = $identity ? $identity->getData() : false;

        if ($identity && $identity->isValid() && $usuario->get('status')) {

            /** @var \App\Model\Entity\Usuario $usuario */
            $usuario = $identity->getData();
            $sessao = $this->Sessoes->newEntity([
                'usuario_id' => $usuario->id,
            ]);
            $this->Sessoes->save($sessao);
            $response = [
                'message' => __('Session started successfully'),
                'expires' => $sessao->expiracao,
                'usuario' => $usuario,
                'accessToken' => $sessao->access_token,
                'refreshToken' => $sessao->id,
            ];
            $this->viewBuilder()->setOption('serialize', [
                'message', 'expires', 'usuario', 'accessToken', 'refreshToken',
            ]);
        } else {
            $this->response = $this->response->withStatus(401);
            $response = [
                'message' => __('Could not start session'),
                'url' => env('REQUEST_URI'),
                'code' => $this->response->getStatusCode(),
            ];
            $this->viewBuilder()->setOption('serialize', ['message', 'url', 'code']);
        }
        $this->set($response);
    }

    /**
     * Método token
     *
     * Atualiza os tokens da sessão
     *
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     * @return void
     */
    public function token(): void
    {
        $this->Authorization->skipAuthorization();
        $this->request->allowMethod('post');

        $jwt = explode('.', $this->request->getData('accessToken'))[1];
        $jwt = json_decode(base64_decode($jwt));

        $sessao = $this->Sessoes->get($this->request->getData('refreshToken'));

        if (
            $jwt->jti === md5($sessao->fingerprint) &&
            $jwt->sub === $sessao->usuario_id &&
            $sessao->expiracao > new FrozenTime()
        ) {
            $sessao = $this->Sessoes->newEntity([
                'usuario_id' => $sessao->usuario_id,
            ]);
            $this->Sessoes->save($sessao);
            $this->Sessoes->loadInto($sessao, ['Usuarios']);
            $response = [
                'message' => __('Session updated successfully'),
                'expires' => $sessao->expiracao,
                'usuario' => $sessao->usuario,
                'accessToken' => $sessao->access_token,
                'refreshToken' => $sessao->id,
            ];
            $this->viewBuilder()->setOption('serialize', [
                'message', 'expires', 'usuario', 'accessToken', 'refreshToken',
            ]);
        } else {
            $this->response = $this->response->withStatus(400);
            $response = [
                'message' => __('Session has expired, please login again'),
                'url' => env('REQUEST_URI'),
                'code' => $this->response->getStatusCode(),
            ];
            $this->viewBuilder()->setOption('serialize', ['message', 'url', 'code']);
        }
        $this->set($response);
    }

    /**
     * Método logout
     *
     * Remove a sessão do usuário
     *
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     * @return void
     */
    public function logout(): void
    {
        $this->Authorization->skipAuthorization();
        $this->request->allowMethod('post');

        $sessao = $this->Sessoes->get($this->request->getData('refreshToken'));
        $this->Sessoes->delete($sessao);
        $response = [
            'message' => __('Session ended successfully'),
            'url' => env('REQUEST_URI'),
            'code' => $this->response->getStatusCode(),
        ];
        $this->viewBuilder()->setOption('serialize', ['message', 'url', 'code']);
        $this->set($response);
    }
}
