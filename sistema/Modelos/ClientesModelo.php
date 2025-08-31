<?php

namespace sistema\Modelos;

use sistema\Nucleo\Modelo;

class ClientesModelo extends Modelo
{
    public function __construct()
    {
        parent::__construct("clientes");
    }

    public function buscaClientesPorIdUsuario(int $id_usuario): ?array
    {
        return $this->busca("id_usuario = {$id_usuario}")->resultado(true);
    }

    public function cadastraClientes(array $dados, int $id_usuario): ?int
    {
        $this->nome = $dados['nome_cliente'];
        $this->id_usuario = $id_usuario;
        $this->cpf_cnpj = str_replace(['.', '/', '-'], '', $dados['cpf_cnpj_cliente']);
        $this->email = $dados['email_cliente'];
        $this->telefone = str_replace(['(', ')', '-'], '', $dados['telefone_cliente']);
        $this->celular = str_replace(['(', ')', '-'], '', $dados['celular_cliente']);
        $this->cep = str_replace('-', '', $dados['cep_cliente']);
        $this->rua = $dados['rua_cliente'];
        $this->n_casa = $dados['n_casa_cliente'];
        $this->bairro = $dados['bairro_cliente'];
        $this->cidade = $dados['cidade_cliente'];
        $this->uf = $dados['uf_cliente'];
        $this->dt_hr_criacao = date('Y-m-d H:i:s');
        $this->salvar();

        return $this->ultimo_id;
    }


    public function editarCliente(array $dados, int $id_cliente): bool
    {
        $this->id = $id_cliente;
        $this->nome = $dados['nome_cliente'];
        $this->cpf_cnpj = str_replace(['.', '/', '-'], '', $dados['cpf_cnpj_cliente']);
        $this->email = $dados['email_cliente'];
        $this->telefone = str_replace(['(', ')', '-'], '', $dados['telefone_cliente']);
        $this->celular = str_replace(['(', ')', '-'], '', $dados['celular_cliente']);
        $this->cep = str_replace('-', '', $dados['cep_cliente']);
        $this->rua = $dados['rua_cliente'];
        $this->n_casa = $dados['n_casa_cliente'];
        $this->bairro = $dados['bairro_cliente'];
        $this->cidade = $dados['cidade_cliente'];
        $this->uf = $dados['uf_cliente'];
        $this->dt_hr_atualizacao = date('Y-m-d H:i:s');
        return $this->salvar();
    }

    public function excluirClientes(int $id_cliente): bool
    {
        return $this->apagar("id = {$id_cliente}");
    }

    public function VerificaNomeIdExiste(int $id_cliente, string $nome_cliente) : ?array
    {
        return $this->busca("id = {$id_cliente} AND nome = '{$nome_cliente}'")->resultado();
    }
}
