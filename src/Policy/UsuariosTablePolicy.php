<?php
declare(strict_types=1);

namespace App\Policy;

use Authorization\IdentityInterface;
use Authorization\Policy\Result;
use Cake\ORM\Query;

/**
 * UsuariosTablePolicy
 *
 * Políticas da model de usuários
 */
class UsuariosTablePolicy
{
    /**
     * Método canIndex
     *
     * Configura a autorização do método index
     *
     * @param \Authorization\IdentityInterface $identity O usuário logado
     * @return \Authorization\Policy\Result
     */
    public function canIndex(IdentityInterface $identity): Result
    {
        return new Result(true);
    }

    /**
     * Método canAdicionar
     *
     * Configura a autorização do método adicionar
     *
     * @param \Authorization\IdentityInterface $identity O usuário logado
     * @return \Authorization\Policy\Result
     */
    public function canAdicionar(IdentityInterface $identity): Result
    {
        return new Result(true);
    }

    /**
     * Método canVisualizar
     *
     * Configura a autorização do método visualizar
     *
     * @param \Authorization\IdentityInterface $identity O usuário logado
     * @return \Authorization\Policy\Result
     */
    public function canVisualizar(IdentityInterface $identity): Result
    {
        return new Result(true);
    }

    /**
     * Método canAlterar
     *
     * Configura a autorização do método alterar
     *
     * @param \Authorization\IdentityInterface $identity O usuário logado
     * @return \Authorization\Policy\Result
     */
    public function canAlterar(IdentityInterface $identity): Result
    {
        return new Result(true);
    }

    /**
     * Método canRemover
     *
     * Configura a autorização do método remover
     *
     * @param \Authorization\IdentityInterface $identity O usuário logado
     * @return \Authorization\Policy\Result
     */
    public function canRemover(IdentityInterface $identity): Result
    {
        return new Result(true);
    }

    /**
     * Método scopeIndex
     *
     * Configura o escopo de autorização do método index
     *
     * @param \Authorization\IdentityInterface $identity O usuário logado
     * @param \Cake\ORM\Query $query A query executada
     * @return \Cake\ORM\Query
     */
    public function scopeIndex(IdentityInterface $identity, Query $query): Query
    {
        return $query;
        // return $query->where(['id' => $identity->id]);
    }
}
