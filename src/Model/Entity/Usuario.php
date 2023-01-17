<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * Usuario Entity
 *
 * @property int $id
 * @property string $nome
 * @property string $email
 * @property string $senha
 * @property int $status
 * @property \Cake\I18n\FrozenTime $criacao
 * @property \Cake\I18n\FrozenTime $modificacao
 *
 * @property \App\Model\Entity\Sessao[] $sessoes
 */
class Usuario extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'nome' => true,
        'email' => true,
        'senha' => true,
        'status' => true,
        'criacao' => true,
        'modificacao' => true,
        'sessoes' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array<string>
     */
    protected $_hidden = ['senha'];

    /**
     * Método setSenha
     *
     * Realiza a criptografia da senha
     *
     * @param string $password - A senha de acesso
     * @return string
     */
    protected function _setSenha(string $password): string
    {
        if (strlen($password) > 0) {
            $password = (new DefaultPasswordHasher())->hash($password);
        }

        return $password;
    }
}
