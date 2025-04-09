<?php

namespace sistema\Suporte;

use GuzzleHttp\Client;

class Api
{
    private Client $cliente;
    private string $erro;

    public function __construct()
    {
        $this->cliente = new Client();
    }

    public function get(string $url, array $queryParams = []): array|null
    {
        try {
            $resposta = $this->cliente->request('GET', $url, [
                'query' => $queryParams
            ]);

            if ($resposta->getStatusCode() == 200) {
                return json_decode($resposta->getBody(), true);
            } else {
                $this->erro = $resposta->getStatusCode();

                return null;
            }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $this->erro = $e->getMessage();
            return null;
        }
    }

    public function post(string $url, array $dados, array $headers = []): array|null
    {
        try {
            $resposta = $this->cliente->request('POST', $url, [
                'json' => $dados,
                'headers' => array_merge([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ], $headers)
            ]);

            if ($resposta->getStatusCode() == 200) {
                return json_decode($resposta->getBody(), true);
            } else {
                $this->erro = $resposta->getStatusCode();
                return null;
            }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $this->erro = $e->getMessage();
            
            return null;
        }
    }

    public function getErro()
    {
        return $this->erro;
    }
}
