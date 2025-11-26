<?php

namespace App\Models;

use App\Core\Model;

class ListModel extends Model
{
    public function __construct()
    {
        parent::__construct("listas");
    } 

    public function findListsByUserId(int $userId) : ?array
    {
        $listas = $this->busca("id_usuario = {$userId}")->resultado(true);   
        return $listas; 
    }

    public function findListByHash(string $hash): array
    {
        $lista = $this->busca("hash = '{$hash}'", null, 'id_usuario, lista_completa')->resultado(true);
        return $lista;
    }

     public function findListById($id): ?array
    {
        $orcamentos = $this->busca("id = {$id}")->resultado(true);
        return $orcamentos;    
    }

    public function registerList(int $clientId, array $data, int $userId, string $template, string $hash) : bool
    {
        $this->id_cliente = $clientId;
        $this->hash = $hash;
        $this->modelo = $template;
        $this->lista_completa = json_encode($data);
        $this->dt_hr_criacao = date('Y-m-d H:i:s');
        $this->id_usuario = $userId;
        return $this->salvar();
    }

    public function destroyList(string $hash) : bool
    {
        return $this->apagar("hash = '{$hash}'");
    }
}