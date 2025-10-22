<?php

namespace sistema\Adapter;

use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Classe para geração de PDFs utilizando a biblioteca DOMPDF.
 *
 * @package sistema\Adapter
 */
final class Pdf
{
    /**
     * Instância da classe DOMPDF.
     *
     * @var Dompdf
     */
    private Dompdf $pdf;

    /**
     * Configuração do papel e da orientação.
     *
     * @var string
     */
    private string $papel = 'A4';

    private string $orientacao = 'portrait';

    /**
     * Construtor da classe. Configura o DOMPDF com valores padrão.
     */
    public function __construct()
    {
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isHtml5ParserEnabled', true);
        $options->setChroot(__DIR__);
        $options->set('isRemoteEnabled', true);
        $this->pdf = new Dompdf($options);
    }

    /**
     * Define o conteúdo do PDF.
     *
     * @param string $html Conteúdo HTML a ser convertido em PDF.
     * @return static Retorna a instância atual para encadeamento.
     */
    public function carregarHTML(string $html): static
    {
        $this->pdf->loadHtml($html);
        return $this;
    }

    /**
     * Define o tamanho do papel e a orientação.
     *
     * @param string $papel Tamanho do papel (ex: A4, Letter).
     * @param string $orientacao Orientação (portrait ou landscape).
     * @return static Retorna a instância atual para encadeamento.
     */
    public function configurarPapel(string $papel, string $orientacao = 'portrait'): static
    {
        $this->papel = $papel;
        $this->orientacao = $orientacao;
        return $this;
    }

    /**
     * Renderiza o PDF.
     *
     * @return static Retorna a instância atual para encadeamento.
     */
    public function renderizar(): static
    {
        $this->pdf->setPaper($this->papel, $this->orientacao);
        $this->pdf->render();
        return $this;
    }

    /**
     * Retorna o PDF gerado como string.
     *
     * @return string
     */
    public function obterSaida(): string
    {
        return $this->pdf->output();
    }

    /**
     * Exibe o PDF no navegador.
     *
     * @param string $nomeArquivo Nome do arquivo para exibição.
     */
    public function exibir(string $nomeArquivo = 'documento.pdf')
    {
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $nomeArquivo . '"');
        echo $this->obterSaida();
    }

    /**
     * Salva o PDF em um arquivo.
     *
     * @param string $caminho Caminho onde o arquivo será salvo.
     * @return bool Retorna true em caso de sucesso ou false em caso de falha.
     */
    public function salvarPDF(string $caminho_diretorio, string $nome_arquivo): bool
{
    // Concatena o caminho do diretório com o nome do arquivo
    $caminho_completo = rtrim($caminho_diretorio, '/') . '/' . $nome_arquivo;

    // Garante que o diretório exista antes de tentar salvar o arquivo
    if (!is_dir($caminho_diretorio)) {
        mkdir($caminho_diretorio, 0755, true);
    }

    return file_put_contents($caminho_completo, $this->obterSaida()) !== false;
}

    /**
     * Faz o download do PDF.
     *
     * @param string $nomeArquivo Nome do arquivo para download.
     */
    public function baixar(string $nomeArquivo = 'documento.pdf')
    {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $nomeArquivo . '"');
        echo $this->obterSaida();
    }
}


