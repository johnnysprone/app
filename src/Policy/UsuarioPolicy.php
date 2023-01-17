<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Usuario;
use Authorization\IdentityInterface;
use Authorization\Policy\Result;

/**
 * UsuarioPolicy
 *
 * Políticas da entidade de usuários
 */
class UsuarioPolicy
{
    /**
     * Método canIndex
     *
     * Configura a autorização do método index
     *
     * @param \Authorization\IdentityInterface $identity O usuário logado
     * @param \App\Model\Entity\Usuario $entity A entidade manipulada
     * @return \Authorization\Policy\Result
     */
    public function canIndex(IdentityInterface $identity, Usuario $entity): Result
    {
        return new Result(true);
    }

    /**
     * Método canAdicionar
     *
     * Configura a autorização do método adicionar
     *
     * @param \Authorization\IdentityInterface $identity O usuário logado
     * @param \App\Model\Entity\Usuario $entity A entidade manipulada
     * @return \Authorization\Policy\Result
     */
    public function canAdicionar(IdentityInterface $identity, Usuario $entity): Result
    {
        return new Result(true);
    }

    /**
     * Método canVisualizar
     *
     * Configura a autorização do método visualizar
     *
     * @param \Authorization\IdentityInterface $identity O usuário logado
     * @param \App\Model\Entity\Usuario $entity A entidade manipulada
     * @return \Authorization\Policy\Result
     */
    public function canVisualizar(IdentityInterface $identity, Usuario $entity): Result
    {
        return new Result(true);
    }

    /**
     * Método canAlterar
     *
     * Configura a autorização do método alterar
     *
     * @param \Authorization\IdentityInterface $identity O usuário logado
     * @param \App\Model\Entity\Usuario $entity A entidade manipulada
     * @return \Authorization\Policy\Result
     */
    public function canAlterar(IdentityInterface $identity, Usuario $entity): Result
    {
        return new Result(true);
    }

    /**
     * Método canRemover
     *
     * Configura a autorização do método remover
     *
     * @param \Authorization\IdentityInterface $identity O usuário logado
     * @param \App\Model\Entity\Usuario $entity A entidade manipulada
     * @return \Authorization\Policy\Result
     */
    public function canRemover(IdentityInterface $identity, Usuario $entity): Result
    {
        return new Result(true);
    }
}
