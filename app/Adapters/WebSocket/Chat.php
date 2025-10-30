<?php
namespace App\Adapters\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use PDO;

class Chat implements MessageComponentInterface
{
    protected $clientes;
    protected $pdo;

    public function __construct()
    {
        $this->clientes = new \SplObjectStorage;
        
        // Conectar ao banco de dados (usar uma conexão de BD para cada chat!)
        $dsn = "mysql:host=127.0.0.1;dbname=consult;charset=utf8mb4";
        $usuario = "root";
        $senha = "";
        $this->pdo = new PDO($dsn, $usuario, $senha, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clientes->attach($conn);
        echo "Nova conexão ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        // Decodificar a mensagem recebida
        $data = json_decode($msg, true);
        if (!isset($data['mensagem']) || !isset($data['id'])) {
            return;
        }

        $idAta = $data['id'];
        $mensagem = $data['mensagem'];
        $usuarioId = $data['usuario_id'];
        $hora = date("Y-m-d H:i:s");

        // Buscar informações do usuário
        $stmt = $this->pdo->prepare("SELECT nome, img_perfil FROM sistema_usuarios WHERE id = ?");
        $stmt->execute([$usuarioId]);
        $usuario = $stmt->fetch();

        if (!$usuario) {
            return;
        }

        // Salvar no banco de dados
        $stmt = $this->pdo->prepare("INSERT INTO sistema_atas_mensagens (id_ata, usuario_id, texto_ata_usu, hora_data_msg) VALUES (?, ?, ?, ?)");
        $stmt->execute([$idAta, $usuarioId, $mensagem, $hora]);

        $mensagemId = $this->pdo->lastInsertId();

        // Criar o formato de resposta
        $response = [
            "id" => $mensagemId,
            "id_ata" => $idAta,
            "nome_usuario" => $usuario['nome'],
            "foto_usuario" => $usuario['foto'] ?? 'avatar_padrao.png',
            "hora_data_msg" => $hora,
            "texto_ata_usu" => htmlspecialchars($mensagem)
        ];

        // Enviar para todos os clientes conectados
        foreach ($this->clientes as $cliente) {
            $cliente->send(json_encode($response));
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clientes->detach($conn);
        echo "Conexão {$conn->resourceId} desconectada\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Erro: {$e->getMessage()}\n";
        $conn->close();
    }
}
