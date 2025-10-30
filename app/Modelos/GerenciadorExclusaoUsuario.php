<?php

namespace app\Modelos;

use app\Nucleo\Modelo;
use app\Nucleo\Conexao;

/**
 * Gerencia a exclusão de registros de um usuário em múltiplas tabelas de forma dinâmica.
 */
class GerenciadorExclusaoUsuario extends Modelo
{
    public function __construct()
    {
        parent::__construct('gerenciador_exclusao');
    }

    /**
     * Exclui todos os registros associados a um id_usuario em todas as tabelas que contêm a coluna 'id_usuario'.
     * Esta operação é realizada dentro de uma transação para garantir atomicidade.
     *
     * @param int $id_usuario O ID do usuário cujos registros devem ser excluídos.
     * @return bool TRUE se todos os registros foram excluídos com sucesso, FALSE caso contrário.
     */
    public function apagarRegistrosPorUsuarioGeral(int $id_usuario): bool
    {
        $conexao = Conexao::getInstancia();
        $sucessoTotal = true;

        try {
            $conexao->beginTransaction();

            $queryTabelas = "
                SELECT TABLE_NAME
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE COLUMN_NAME = 'id_usuario'
                  AND TABLE_SCHEMA = DATABASE()
            ";

            $stmtTabelas = $conexao->prepare($queryTabelas);
            $stmtTabelas->execute();
            $tabelasComUsuario = $stmtTabelas->fetchAll(\PDO::FETCH_COLUMN);

            if (empty($tabelasComUsuario)) {

                $conexao->commit();
                return true;
            }

            foreach ($tabelasComUsuario as $tabela) {

                $queryExclusao = "DELETE FROM `{$tabela}` WHERE `id_usuario` = :id_usuario";
                $stmtExclusao = $conexao->prepare($queryExclusao);
                $stmtExclusao->bindValue(':id_usuario', $id_usuario, \PDO::PARAM_INT);

                if (!$stmtExclusao->execute()) {

                    throw new \PDOException("Falha ao excluir da tabela {$tabela}.");
                }
            }

            $conexao->commit();
            $sucessoTotal = true;
        } catch (\Throwable $th) {
            if ($conexao->inTransaction()) {
                $conexao->rollBack();
            }

            $this->erro = "Erro ao excluir registros para o usuário {$id_usuario}: " . $th->getMessage();

            $sucessoTotal = false;
        }

        return $sucessoTotal;
    }

    /**
     * Método para obter o último erro ocorrido.
     * Herdado da classe Modelo.
     * @return \Throwable|string|null
     */
    public function getErro()
    {
        return $this->erro;
    }
}
