<?php
declare(strict_types=1);

namespace App\Controller\Api\v1;

use App\Controller\AppController;
use App\Trait\ApiSecurityTrait;
use Cake\Event\EventInterface;

/**
 * ApiController
 *
 * Controller de gerenciamento dos endpoints
 *
 * @property \App\Model\Table\UsuariosTable $Usuarios
 */
class ApiController extends AppController
{
    use ApiSecurityTrait;

    /**
     * Propriedade actions
     *
     * Lista de ações dos endpoints
     *
     * @var array<string>
     */
    public $actions = ['adicionar', 'alterar', 'index', 'remover', 'visualizar'];

    /**
     * Propriedade entity
     *
     * Nome da entidade no singular
     *
     * @var string
     */
    public $entity = 'controller';

    /**
     * Propriedade entities
     *
     * Nome da entidade no plural
     *
     * @var string
     */
    public $entities = 'controllers';

    /**
     * Propriedade model
     *
     * Nome da model da entidade
     *
     * @var string
     */
    public $model = 'Model';

    /**
     * Propriedade indexOptions
     *
     * Lista de parâmetros do método index
     *
     * @var array
     */
    public $indexOptions = [];

    /**
     * Propriedade createOptions
     *
     * Lista de parâmetros do método adicionar
     *
     * @var array
     */
    public $createOptions = [];

    /**
     * Propriedade viewOptions
     *
     * Lista de parâmetros do método visualizar
     *
     * @var array
     */
    public $viewOptions = [];

    /**
     * Propriedade updateOptions
     *
     * Lista de parâmetros do método atualizar
     *
     * @var array
     */
    public $updateOptions = [];

    /**
     * Propriedade deleteOptions
     *
     * Lista de parâmetros do método remover
     *
     * @var array
     */
    public $deleteOptions = [];

    /**
     * Propriedade paginate
     *
     * Configurações de paginação
     *
     * @var array<string, mixed>
     */
    public $paginate = ['maxLimit' => 1000];

    /**
     * Initialization hook method.
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        // Configura a model
        $this->model = $this->request->getParam('controller');
    }

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
     * Método index
     *
     * Lista os dados das entidades
     *
     * @return void
     */
    public function index(): void
    {
        // Verifica a autorização
        $this->Authorization->authorize($this->{$this->model});

        // Configura a paginação
        if ($this->request->getQuery('limit')) {
            $this->paginate['limit'] = $this->request->getQuery('limit');
        }
        // Configura as associações
        if ($this->request->getQuery('contain')) {
            $this->paginate['contain'] = $this->filterData(
                (array)$this->request->getQuery('contain')
            );
        }
        // Busca as entidades
        $entities = $this->{$this->model}->find(
            $this->request->getQuery('finder', 'all'),
            $this->filterData(
                (array)$this->request->getQuery('options', [])
            ) + $this->indexOptions
        );
        // Aplica o scopo de autorização
        if ($this->Authentication->getIdentity()) {
            $this->Authorization->applyScope($entities);
        }
        // Envia os dados para a view
        $response = [
            $this->entities => $this->paginate($entities),
            'pagination' => $this->request->getAttribute('paging')[$this->model],
        ];
        $this->viewBuilder()->setOption('serialize', [
            'pagination', $this->entities,
        ]);
        $this->set($response);
    }

    /**
     * Método adicionar
     *
     * Adiciona a entidade
     *
     * @return void
     */
    public function adicionar(): void
    {
        // Verifica a autorização
        $this->Authorization->authorize($this->{$this->model});

        // Cria a entidade
        $entity = $this->{$this->model}->newEntity($this->request->getData());

        // Verifica a autorização da entidade
        $this->Authorization->authorize($entity);

        // Salva os dados
        if ($this->{$this->model}->save($entity)) {
            $response = [
                'message' => __('Entity added successfully'),
                $this->entity => $entity,
            ];
            $this->viewBuilder()->setOption('serialize', [
                'message', $this->entity,
            ]);
        } else {
            $this->response = $this->response->withStatus(400);
            $response = [
                'message' => __('Unable to add entity'),
                $this->entity => $entity,
                'errors' => $entity->getErrors(),
            ];
            $this->viewBuilder()->setOption('serialize', [
                'message', $this->entity, 'errors',
            ]);
        }
        // Envia os dados para a view
        $this->set($response);
    }

    /**
     * Método visualizar
     *
     * Retorna os dados da entidade
     *
     * @param int $id O ID da entidade
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     * @return void
     */
    public function visualizar(int $id): void
    {
        // Verifica a autorização
        $this->Authorization->authorize($this->{$this->model});

        // Configura as opções
        $options = (array)$this->request->getQuery('options', [])
            + $this->viewOptions;

        // Configura as associações
        if ($this->request->getQuery('contain')) {
            $options['contain'] = $this->filterData(
                (array)$this->request->getQuery('contain')
            );
        }
        // Configura o finder da pesquisa
        if ($this->request->getQuery('finder')) {
            $options['finder'] = $this->request->getQuery('finder');
        }
        // Busca a entidade
        $entity = $this->{$this->model}->get($id, $options);

        // Envia os dados para a view
        $response[$this->entity] = $entity;
        $this->viewBuilder()->setOption('serialize', [$this->entity]);
        $this->set($response);
    }

    /**
     * Método alterar
     *
     * Altera os dados da entidade
     *
     * @param int $id O ID da entidade
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     * @return void
     */
    public function alterar(int $id): void
    {
        // Verifica a autorização
        $this->Authorization->authorize($this->{$this->model});

        // Altera a entidade
        $entity = $this->{$this->model}->get(
            $id,
            (array)$this->filterData(
                (array)$this->request->getQuery('options', [])
            ) + $this->updateOptions
        );
        $entity = $this->{$this->model}->patchEntity(
            $entity,
            $this->request->getData()
        );
        // Verifica a autorização da entidade
        $this->Authorization->authorize($entity);

        // Salva os dados
        if ($this->{$this->model}->save($entity)) {
            $response = [
                'message' => __('Entity successfully changed'),
                $this->entity => $entity,
            ];
            $this->viewBuilder()->setOption('serialize', [
                'message', $this->entity,
            ]);
        } else {
            $this->response = $this->response->withStatus(400);
            $response = [
                'message' => __('Unable to change entity'),
                $this->entity => $entity,
                'errors' => $entity->getErrors(),
            ];
            $this->viewBuilder()->setOption('serialize', [
                'message', $this->entity, 'errors',
            ]);
        }
        // Envia os dados para a view
        $this->set($response);
    }

    /**
     * Método remover
     *
     * Remove os dados da entidade
     *
     * @param int $id O ID da entidade
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     * @return void
     */
    public function remover(int $id): void
    {
        // Verifica a autorização
        $this->Authorization->authorize($this->{$this->model});

        // Busca a entidade
        $entity = $this->{$this->model}->get(
            $id,
            (array)$this->filterData(
                (array)$this->request->getQuery('options', [])
            ) + $this->deleteOptions
        );
        // Verifica a autorização da entidade
        $this->Authorization->authorize($entity);

        // Remove os dados
        $this->{$this->model}->delete($entity);
        $response = [
            'message' => __('Entity removed successfully'),
            $this->entity => $entity,
        ];
        $this->viewBuilder()->setOption('serialize', [
            'message', $this->entity,
        ]);
        // Envia os dados para a view
        $this->set($response);
    }
}
