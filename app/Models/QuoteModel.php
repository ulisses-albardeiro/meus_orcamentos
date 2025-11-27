<?php

namespace App\Models;

use App\Core\Model;

class QuoteModel extends Model
{
    public function __construct()
    {
        parent::__construct("orcamentos");
    }

    public function findsQuotesByUserId(int $userId): ?array
    {
        return  $this->busca("id_usuario = {$userId}")->resultado(true);
    }

    public function findQuoteById(int $id): ?array
    {
        return $this->busca("id = {$id}")->resultado(true);
    }

    public function findQuoteByHash(string $hash): array
    {
        return $this->busca("hash = '{$hash}'")->resultado(true);
    }

    public function registerQuote(int $clientId, string $vl_total, array $data, int $userId, string $template, string $hash): ?int
    {
        $this->id_cliente = $clientId;
        $this->hash = $hash;
        $this->vl_total = $vl_total / 100;
        $this->dt_hr_criacao = date('Y-m-d H:i:s');
        $this->orcamento_completo = json_encode($data);
        $this->id_usuario = $userId;
        $this->modelo = $template;
        if ($this->salvar()) {
            return $this->getUltimoId();
        }

        return null;
    }

    public function destroyQuote(string $hash): bool
    {
        return $this->apagar("hash = '{$hash}'");
    }
}
