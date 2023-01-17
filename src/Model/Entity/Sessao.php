<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Trait\ApiSecurityTrait;
use Cake\ORM\Entity;

/**
 * Sessao Entity
 *
 * @property string $id
 * @property \Cake\I18n\FrozenTime $expiracao
 * @property int $usuario_id
 * @property string $dados
 * @property \Cake\I18n\FrozenTime $criacao
 * @property \Cake\I18n\FrozenTime $modificacao
 *
 * @property \App\Model\Entity\Usuario $usuario
 * @property string $access_token
 * @property string $fingerprint
 */
class Sessao extends Entity
{
    use ApiSecurityTrait;

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
        'expiracao' => true,
        'usuario_id' => true,
        'dados' => true,
        'criacao' => true,
        'modificacao' => true,
        'usuario' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array<string>
     */
    protected $_hidden = ['id', 'expiracao'];

    /**
     * Método _getAccessToken
     *
     * Retorna o token de acesso
     *
     * @return string
     */
    protected function _getAccessToken()
    {
        $accessToken = $this->jwtEncode(
            md5($this->fingerprint),
            $this->usuario_id,
            '+1 minutes'
        );

        return $accessToken;
    }

    /**
     * Método _getFingerprint
     *
     * Retorna a impressão digital
     *
     * @return string
     */
    protected function _getFingerprint()
    {
        $fingerprint = $this->id .
            $this->expiracao->format('Y-m-d H:i:s') .
            $this->usuario_id .
            $this->dados;

        return $fingerprint;
    }
}
