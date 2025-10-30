<?php

namespace App\Models;

use App\Core\Modelo;

class EmpresasModelo extends Modelo
{
    public function __construct()
    {
        parent::__construct("empresas");
    }

    public function cadastrarEmpresa(array $dados, int $idUsuario, ?string $logo): bool
    {
        $this->id_usuario = $idUsuario;
        $this->nome = $dados['nome'];
        $this->email = $dados['email'] ?? null;
        $this->telefone = $dados['telefone'] ?? null;
        $this->celular = $dados['celular'] ?? null;
        $this->logo = $logo ?? null;
        $this->cep = $dados['cep'] ?? null;
        $this->rua = $dados['rua'] ?? null;
        $this->n_casa = $dados['n_casa'] ?? null;
        $this->bairro = $dados['bairro'] ?? null;
        $this->cidade = $dados['cidade'] ?? null;
        $this->uf = $dados['uf'] ?? null;
        $this->facebook = $dados['facebook'] ?? null;
        $this->instagram = $dados['instagram'] ?? null;
        $this->youtube = $dados['youtube'] ?? null;
        $this->linkedin = $dados['linkedin'] ?? null;
        $this->x = $dados['x'] ?? null;
        $this->tiktok = $dados['tiktok'] ?? null;

        return $this->salvar();
    }

    public function editarEmpresa(array $dados, int $idEmpresa, ?string $logo): bool
    {
        $this->id = $idEmpresa;
        $this->nome = $dados['nome'];
        $this->email = $dados['email'] ?? null;
        $this->telefone = $dados['telefone'] ?? null;
        $this->celular = $dados['celular'] ?? null;
        $this->cep = $dados['cep'] ?? null;
        $this->rua = $dados['rua'] ?? null;
        $this->n_casa = $dados['n_casa'] ?? null;
        $this->bairro = $dados['bairro'] ?? null;
        $this->cidade = $dados['cidade'] ?? null;
        $this->uf = $dados['uf'] ?? null;
        $this->facebook = $dados['facebook'] ?? null;
        $this->instagram = $dados['instagram'] ?? null;
        $this->youtube = $dados['youtube'] ?? null;
        $this->linkedin = $dados['linkedin'] ?? null;
        $this->x = $dados['x'] ?? null;
        $this->tiktok = $dados['tiktok'] ?? null;

        if (isset($logo)) {
            $this->logo = $logo;
        }

        return $this->salvar();
    }

    public function buscaEmpresaPorIdUsuario(int $idUsuario): ?Modelo
    {
        return $this->busca("id_usuario = {$idUsuario}")->resultado();
    }

    public function excluirLogo(int $idEmpresa): bool
    {
        $this->id = $idEmpresa;
        $this->logo = null;
        return $this->salvar();
    }
}
