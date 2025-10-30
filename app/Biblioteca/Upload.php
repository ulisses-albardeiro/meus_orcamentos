<?php

namespace App\Biblioteca;

use GdImage;

/**
 * Classe independente do sistema.
 *
 * Esta classe faz upload de arquivos diversos e que são validados
 * pela extenção, pelo tipo (definidos internamente) e pelo tamanho (definido no metodo). 
 * Métodos Disponíveis:
 * @method __construct(string|null $diretorio)
 * -arquivo(array $arquivo, string|null $nome, string|null $diretorioFilho, int|null $tamanho): void
 * -getResultado(): ?string
 * -getErro(): ?string
 *
 * @author Ulisses Albardeiro
 * @since 28/12/2024
 */

class Upload
{

    private ?string $diretorio;
    private array $arquivo;
    private ?string $nome;
    private ?string $diretorioFilho;
    private ?int $tamanho;
    public  ?string $larguraImagem;
    private ?string $resultado = null;
    private ?string $erro;

    /**
     * Cria uma instância da classe Upload.
     *
     * Caso o diretório pai não seja especificado, o valor padrão será 'uploads'.
     * Se o diretório não existir, ele será criado automaticamente com permissões 0755.
     *
     * @param string|null $diretorio Diretório pai onde os arquivos serão movidos. 
     * Caso não seja fornecido, o valor padrão será 'uploads'.
     */

    public function __construct(?string $diretorio = null)
    {
        $this->diretorio = $diretorio ?? 'uploads';

        if (!file_exists($this->diretorio) && !is_dir($this->diretorio)) {
            mkdir($this->diretorio, 0755);
        }
    }

    /**
     * Retorna o resultado com o nome salvo do arquivo da operação em caso de sucesso.
     *
     * @return string|null Nome do arquivo ou null em caso de falha.
     */

    public function getResultado(): ?string
    {
        return $this->resultado;
    }

    /**
     * Retorna o erro da operação, caso haja.
     *
     * @return string|null Mensagem de erro ou null se não houver erro.
     */

    public function getErro(): ?string
    {
        return $this->erro;
    }

    /**
     * Realiza o upload de um arquivo.
     *
     * Valida a extensão, tipo e tamanho do arquivo. Caso o arquivo seja válido, ele será movido
     * para o diretório de destino.
     *
     * @param array $arquivo Arquivo enviado pelo formulário ($_FILES).
     * @param string|null $nome Nome para o arquivo, caso não seja fornecido, será usado o nome original.
     * @param string|null $diretorioFilho Diretório filho onde o arquivo será movido. O valor padrão é 'arquivos'.
     * @param int|null $tamanho Tamanho máximo permitido para o arquivo em megabytes. O valor padrão é 1MB.
     */


    public function arquivo(array $arquivo, ?string $nome = null,  ?string $diretorioFilho = null, ?int $tamanho = null): void
    {
        $this->arquivo = $arquivo;
        $this->nome = $nome ?? pathinfo($this->arquivo['name'], PATHINFO_FILENAME);
        $this->diretorioFilho = $diretorioFilho ?? 'arquivos';
        $this->tamanho = $tamanho ?? 1;

        $extencaoArquivo = pathinfo($this->arquivo['name'], PATHINFO_EXTENSION);
        $extencoesPermitidas = ['pdf', 'png', 'jpeg', 'jpg'];
        $mimetypeArquivo = $this->arquivo['type'];
        $mimetypesPermitidas = ['application/pdf', 'image/png', 'image/jpeg', 'image/jpg'];

        if (!in_array($extencaoArquivo, $extencoesPermitidas)) {
            $this->erro = 'exteção de arquivo não permitida, somente ' . implode(" .", $extencoesPermitidas);
        } elseif (!in_array($mimetypeArquivo, $mimetypesPermitidas)) {
            $this->erro = 'Tipo de arquivo não permitido';
        } elseif ($this->arquivo['size'] > $this->tamanho * (1024 * 1024)) {
            $this->erro = 'Arquivo maior que o permitido, tamanho máximo de ' . $this->tamanho * (1024 * 1024) . 'MB e o seu arquivo tem ' . $this->arquivo['size'] . 'MB';
        } else {
            $this->criarDiretorioFilho();
            $this->renomearArquivo();
            $this->moverArquivo();
        }
    }

    private function renomearArquivo(): void
    {
        $nomeSemExtensao = pathinfo($this->arquivo['name'], PATHINFO_FILENAME);
        $extensao = strrchr($this->arquivo['name'], '.');

        $this->nome = $this->nome ?? $nomeSemExtensao;
        $this->nome = pathinfo($this->nome, PATHINFO_FILENAME);
        
        $arquivo = $this->nome . $extensao;
        $verificacaoNome = $this->diretorio . DIRECTORY_SEPARATOR . $this->diretorioFilho . DIRECTORY_SEPARATOR . $arquivo;

        $i = 1;

        while (file_exists($verificacaoNome)) {
            $arquivo = $this->nome . "($i)" . $extensao;
            $verificacaoNome = $this->diretorio . DIRECTORY_SEPARATOR . $this->diretorioFilho . DIRECTORY_SEPARATOR . $arquivo;
            $i++;
        }

        $this->nome = $arquivo;
    }

    private function criarDiretorioFilho(): void
    {
        if (!file_exists($this->diretorio . DIRECTORY_SEPARATOR . $this->diretorioFilho) && !is_dir($this->diretorio . DIRECTORY_SEPARATOR . $this->diretorioFilho)) {

            mkdir($this->diretorio . DIRECTORY_SEPARATOR . $this->diretorioFilho, 0755);
        }
    }

    private function moverArquivo(): void
    {
        if (move_uploaded_file($this->arquivo['tmp_name'], $this->diretorio . DIRECTORY_SEPARATOR . $this->diretorioFilho . DIRECTORY_SEPARATOR . $this->nome)) {
            $this->resultado = $this->nome;
        } else {
            $this->resultado = null;
            $this->erro = 'erro ao mover arquivo' . $this->arquivo['error'];
        }
    }
}
