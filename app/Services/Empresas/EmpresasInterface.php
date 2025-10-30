<?php

namespace App\Services\Empresas;

use App\Core\Modelo;

interface EmpresasInterface
{
    public function cadastrarEmpresaServico(array $dados, int $idUsuario, ?array $logo): bool;
    public function editarEmpresaServico(array $dados, int $idEmpresa, ?array $logo): bool;
    public function buscaEmpresaPorIdUsuarioServico(int $idUsuario): ?Modelo;
    public function excluirLogoServico(int $idEmpresa): bool;
}