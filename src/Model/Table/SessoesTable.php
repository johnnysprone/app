<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\I18n\FrozenTime;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Sessoes Model
 *
 * @property \App\Model\Table\UsuariosTable&\Cake\ORM\Association\BelongsTo $Usuarios
 * @method \App\Model\Entity\Sessao newEmptyEntity()
 * @method \App\Model\Entity\Sessao newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Sessao[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Sessao get($primaryKey, $options = [])
 * @method \App\Model\Entity\Sessao findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Sessao patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Sessao[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Sessao|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Sessao saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Sessao[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Sessao[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Sessao[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Sessao[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SessoesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('sessoes');
        $this->setEntityClass('App\Model\Entity\Sessao');
        $this->setDisplayField('dados');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'criacao' => 'new',
                    'modificacao' => 'always',
                ],
            ],
        ]);

        $this->belongsTo('Usuarios', [
            'foreignKey' => 'usuario_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->dateTime('expiracao')
            ->notEmptyDateTime('expiracao');

        $validator
            ->nonNegativeInteger('usuario_id')
            ->notEmptyString('usuario_id');

        $validator
            ->scalar('dados')
            ->requirePresence('dados', 'create')
            ->notEmptyString('dados');

        $validator
            ->dateTime('criacao')
            ->notEmptyDateTime('criacao');

        $validator
            ->dateTime('modificacao')
            ->notEmptyDateTime('modificacao');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('usuario_id', 'Usuarios'), ['errorField' => 'usuario_id']);

        return $rules;
    }

    /**
     * beforeMarshal callback.
     *
     * @param \Cake\Event\EventInterface $event The beforeMarshal event that was fired.
     * @param \ArrayObject<int, mixed> $data The data to build an entity with.
     * @param \ArrayObject<int, string> $options A list of options for the objects hydration.
     * @return void
     */
    public function beforeMarshal(EventInterface $event, ArrayObject $data, ArrayObject $options): void
    {
        $data['expiracao'] = (new FrozenTime())->modify('+30 days');
        $data['dados'] = (string)json_encode([
            'ip' => env('REMOTE_ADDR'),
            'user_agent' => env('HTTP_USER_AGENT'),
        ]);
    }

    /**
     * beforeSave callback.
     *
     * @param \Cake\Event\EventInterface $event The beforeSave event that was fired.
     * @param \App\Model\Entity\Sessao $entity The entity that is going to be saved.
     * @param \ArrayObject<int, string> $options the options passed to the save method.
     * @return void
     */
    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        if ($entity->isNew()) {
            if (
                $this->exists([
                'usuario_id' => $entity->usuario_id,
                'dados' => $entity->dados,
                ])
            ) {
                $this->deleteAll([
                    'usuario_id' => $entity->usuario_id,
                    'dados' => $entity->dados,
                ]);
            }
        }
    }
}
