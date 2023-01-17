<?php
declare(strict_types=1);

namespace App\Trait;

use Cake\Utility\Hash;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use stdClass;

/**
 * ApiSecurityTrait
 *
 * Trait de segurança dos dados
 */
trait ApiSecurityTrait
{
    /**
     * Propriedade injections
     *
     * Lista de injeções bloqueadas
     *
     * @var array<string>
     */
    public $injections = [
        'condition',
        'limit',
        'offset',
        'order',
        'contain',
        ' ',
        '(',
        ')',
        '"',
        "'",
        '>',
        '=',
        '<',
    ];

    /**
     * Método filterData
     *
     * Filtra os dados de entrada
     *
     * @param array $data Os dados de entrada
     * @return array
     */
    public function filterData(array $data): array
    {
        $data = Hash::flatten($data);

        // Filtra os dados
        foreach ($data as $key => $value) {
            if (strlen((string)$key) != strlen(str_replace($this->injections, '', (string)$key))) {
                unset($contain[$key]);
            }
        }

        return Hash::expand($data);
    }

    /**
     * Método jwtEncode
     *
     * Cria o Json Web Token
     *
     * @param string $jti A fingerprint
     * @param int $sub O assunto
     * @param string $timeout O tempo de duração
     * @return string
     */
    public function jwtEncode($jti, $sub, $timeout = '+1 minutes'): string
    {
        $privateKey = file_get_contents(CONFIG . '/jwt.key');
        $claims = [
            'iss' => env('APP_NAME'),
            'jti' => $jti,
            'sub' => $sub,
            'iat' => strtotime('now'),
            'exp' => strtotime($timeout),
        ];

        return JWT::encode($claims, (string)$privateKey, 'RS256');
    }

    /**
     * Método jwtDecode
     *
     * Decodifica o Json Web Token
     *
     * @param string $jwt O Json Web Token
     * @return \stdClass
     */
    public function jwtDecode($jwt): stdClass
    {
        $publicKey = (string)file_get_contents(CONFIG . '/jwt.pem');

        return JWT::decode($jwt, new Key($publicKey, 'RS256'));
    }
}
