<?php

namespace app\Adapter;

use PHPMailer\PHPMailer\PHPMailer;

/**
 * Classe para envio de e-mails utilizando a biblioteca PHPMailer.
 * 
 * @package app\Adapter
 */
final class Email
{
    /**necessário
     * Instância da classe PHPMailer.
     *
     * @var PHPMailer
     */
    private PHPMailer $email;

    /**
     * Lista de anexos a serem enviados no e-mail.
     *
     * @var array
     */
    private array $anexo;

    /**
     * Lista pega os erros
     *
     * @var string
     * @access private
     */
    private string $erro;

    public function getErro(): string
    {
        return $this->erro;
    }

    /**
     * Construtor da classe. Configura o PHPMailer com valores padrão.
     * 
     * Configura os parâmetros de conexão SMTP.
     *
     * @param string $host     Endereço do servidor SMTP.
     * @param string $usuario  Nome de usuário para autenticação.
     * @param string $senha    Senha para autenticação.
     * @param int $porta       Porta do servidor SMTP.
     * @return static Retorna a instância atual para encadeamento.
     */
    public function __construct(string $host, string $usuario, string $senha, int $porta)
    {
        $this->email = new PHPMailer(true);
        $this->email->Host       = $host;
        $this->email->Username   = $usuario;
        $this->email->Password   = $senha;
        $this->email->Port       = $porta;
        $this->email->isSMTP();
        $this->email->SMTPAuth   = true;
        $this->email->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->email->CharSet    = 'utf-8';
        $this->email->setLanguage('pt_br');
        $this->email->isHTML(true);
        $this->anexo = [];
        $this->email->SMTPDebug = 0;

    }

    /**
     * Cria o e-mail com assunto, conteúdo e destinatário.
     *
     * @param string $assunto           Assunto do e-mail.
     * @param string $conteudo          Conteúdo do e-mail (aceita HTML).
     * @param string $destinatario_email E-mail do destinatário.
     * @param string $destinatario_nome  Nome do destinatário.
     * @param string|null $email_resposta E-mail para resposta (opcional).
     * @param string|null $nome_resposta  Nome para resposta (opcional).
     * @return static Retorna a instância atual para encadeamento.
     */
    public function criar(
        string $assunto,
        string $conteudo,
        string $destinatario_email,
        string $destinatario_nome,
        ?string $email_resposta = null,
        ?string $nome_resposta = null
    ): static {
        $this->email->Subject = $assunto;
        $this->email->Body = $conteudo;
        $this->email->isHTML(true);
        $this->email->addAddress($destinatario_email, $destinatario_nome);

        if (isset($email_resposta) && isset($nome_resposta)) {
            $this->email->addReplyTo($email_resposta, $nome_resposta);
        }

        return $this;
    }

    /**
     * Envia o e-mail configurado.
     *
     * @param string $remetente_email E-mail do remetente.
     * @param string $remetente_nome  Nome do remetente.
     * @return bool Retorna true em caso de sucesso ou false em caso de falha.
     */
    public function enviar(string $remetente_email, string $remetente_nome): bool
    {
        try {
            $this->email->setFrom($remetente_email, $remetente_nome);

            foreach ($this->anexo as $anexo) {
                $this->email->addAttachment($anexo['caminho'], $anexo['nome']);
            }
            $this->email->send();
            return true;
        } catch (\Throwable $th) {
            $this->erro =  "Erro do email: ".$this->email->ErrorInfo."<br>". "Exception: ".$th->getMessage();
            return false;
        }
    }

    /**
     * Adiciona um anexo ao e-mail.
     *
     * @param string $caminho Caminho do arquivo a ser anexado.
     * @param string|null $nome Nome do anexo (opcional). Caso não seja informado, será utilizado o nome do arquivo.
     * @return static Retorna a instância atual para encadeamento.
     */
    public function anexar(string $caminho, ?string $nome = null): static
    {
        $nome = $nome ?? basename($caminho);
        $this->anexo[] = [
            'caminho' => $caminho,
            'nome' => $nome
        ];
        return $this;
    }

}
