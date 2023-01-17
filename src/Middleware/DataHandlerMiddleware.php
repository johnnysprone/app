<?php
declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * DataHandlerMiddleware
 *
 * Middleware de configurações dos dados
 */
class DataHandlerMiddleware implements MiddlewareInterface
{
    /**
     * Método clearData
     *
     * Limpa os dados de entrada
     *
     * @param mixed $data Os dados de entrada
     * @return mixed
     */
    public function clearData(mixed $data): mixed
    {
        if (is_string($data)) {
            $input = explode(' ', $data);
            $output = implode(' ', array_filter($input));

            return $output;
        }

        return $data;
    }

    /**
     * Método process
     *
     * Realiza o processamento dos dados
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request O objeto da requisição
     * @param \Psr\Http\Server\RequestHandlerInterface $handler O manipulador de requisições
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Configura os dados
        if (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH'])) {
            $data = [];

            foreach ((array)$request->getParsedBody() as $field => $value) {
                $data[$field] = $this->clearData($value);
                if ($data[$field] === 'null') {
                    $data[$field] = null;
                }
            }
            $request = $request->withParsedBody($data);
        }

        return $handler->handle($request);
    }
}
