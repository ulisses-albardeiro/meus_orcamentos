<?php

namespace App\Models;

use App\Core\Model;

class ReciboModelo extends Model
{
    public function __construct()
    {
        parent::__construct("recibos");
    }

    public function cadastrarRecibo(array $dados, int $usuario): bool
    {
        $this->id_usuario = $usuario;
        $this->cliente = $dados['nome-cliente'];
        $this->dt_hr_criacao = date('Y-m-d H:i:s');
        $this->recibo_completo = json_encode($dados);      
        $this->valor = (int) preg_replace('/[^\d]/u', '', $dados['valor-recibo']);
        
        if ($this->salvar()) {
            return true;
        }
        return false;
    }

    public function getRecibos(int $id_usuario) : array
    {
        return $this->busca("id_usuario = {$id_usuario}")->resultado(true) ?? [];
    }

    public function excluirRecibo(int $id_recibo) : bool
    {
        if ($this->apagar("id = '{$id_recibo}'")) {
            return true;
        }
        return false;
            
    }
}
