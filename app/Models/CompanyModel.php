<?php

namespace App\Models;

use App\Core\Model;

class CompanyModel extends Model
{
    public function __construct()
    {
        parent::__construct("empresas");
    }

    public function registerCompany(array $data, int $userId, ?string $logo): bool
    {
        $this->id_usuario = $userId;
        $this->nome = $data['nome'];
        $this->email = $data['email'] ?? null;
        $this->telefone = $data['telefone'] ?? null;
        $this->celular = $data['celular'] ?? null;
        $this->logo = $logo ?? null;
        $this->cep = $data['cep'] ?? null;
        $this->rua = $data['rua'] ?? null;
        $this->n_casa = $data['n_casa'] ?? null;
        $this->bairro = $data['bairro'] ?? null;
        $this->cidade = $data['cidade'] ?? null;
        $this->uf = $data['uf'] ?? null;
        $this->facebook = $data['facebook'] ?? null;
        $this->instagram = $data['instagram'] ?? null;
        $this->youtube = $data['youtube'] ?? null;
        $this->linkedin = $data['linkedin'] ?? null;
        $this->x = $data['x'] ?? null;
        $this->tiktok = $data['tiktok'] ?? null;

        return $this->salvar();
    }

    public function updateCompany(array $data, int $userId, ?string $logo): bool
    {
        $this->id = $userId;
        $this->nome = $data['nome'];
        $this->email = $data['email'] ?? null;
        $this->telefone = $data['telefone'] ?? null;
        $this->celular = $data['celular'] ?? null;
        $this->cep = $data['cep'] ?? null;
        $this->rua = $data['rua'] ?? null;
        $this->n_casa = $data['n_casa'] ?? null;
        $this->bairro = $data['bairro'] ?? null;
        $this->cidade = $data['cidade'] ?? null;
        $this->uf = $data['uf'] ?? null;
        $this->facebook = $data['facebook'] ?? null;
        $this->instagram = $data['instagram'] ?? null;
        $this->youtube = $data['youtube'] ?? null;
        $this->linkedin = $data['linkedin'] ?? null;
        $this->x = $data['x'] ?? null;
        $this->tiktok = $data['tiktok'] ?? null;

        if (isset($logo)) {
            $this->logo = $logo;
        }

        return $this->salvar();
    }

    public function findCompanyByUserId(int $idUsuario): ?CompanyModel
    {
        return $this->busca("id_usuario = {$idUsuario}")->resultado();
    }

    public function destroyLogo(int $companyId): bool
    {
        $this->id = $companyId;
        $this->logo = null;
        return $this->salvar();
    }
}
