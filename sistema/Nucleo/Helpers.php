<?php

namespace sistema\Nucleo;

use sistema\Nucleo\Sessao;

class Helpers
{

    /**
     * Redireciona o navegador para uma URL especificada.
     *
     * Este método envia um cabeçalho HTTP 302 para redirecionar o navegador
     * para a URL fornecida. Se nenhuma URL for passada, redireciona para a URL padrão.
     *
     * @param string|null $url A URL para redirecionar. Se nulo, redireciona para a URL padrão (404).
     *
     */
    public static function redirecionar(?string $url = null): void
    {
        header('HTTP/1.1 302 Found');
        $local = ($url ? self::url($url) : self::url());
        header("Location: {$local}");
        exit;
    }

    /**
     * Busca o ambiente de desenvolvimento 
     * @return boll retorna valor true ser for em localhost e false para ambiente de produção
     */
    public static function localhost(): bool
    {
        $servidor = filter_input(INPUT_SERVER, 'SERVER_NAME');

        if ($servidor == 'localhost') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Redireciona o usuário para a página anterior, utilizando o cabeçalho HTTP `Referer`.
     * Caso o cabeçalho `Referer` não esteja disponível, redireciona para uma página padrão (dashboard).
     *
     * @return void
     * @throws Exception Se o redirecionamento falhar (embora isso seja raro).
     */
    public static function voltar(): void
    {
        if (isset($_SERVER['HTTP_REFERER'])) {
            $pagina_anterior = $_SERVER['HTTP_REFERER'];
        } else {
            $pagina_anterior = 'home';
        }
        header("Location: $pagina_anterior");
        exit();
    }

    /**
     * Valida o email, exigindo um '@' e um '.' para poder ser aprovado
     * @param string email de entrada
     * @return bool retorno com verdadeiro ou falso para a validação do email
     */

    public static function validarEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Limpa a string, gerando um slug amigável para URLs.
     *
     * @param string $title Título do post.
     * @return string Slug sem símbolos, acentos, com letras minúsculas e traços.
     */
    public static function slug(string $title): string
    {
        // Mapeamento de caracteres acentuados para seus equivalentes
        $map = [
            'Á' => 'A',
            'À' => 'A',
            'Â' => 'A',
            'Ã' => 'A',
            'Ç' => 'C',
            'È' => 'E',
            'É' => 'E',
            'Ê' => 'E',
            'Ẽ' => 'E',
            'Ì' => 'I',
            'Í' => 'I',
            'Î' => 'I',
            'Ĩ' => 'I',
            'Ò' => 'O',
            'Ó' => 'O',
            'Ô' => 'O',
            'Õ' => 'O',
            'Ñ' => 'N',
            'Ù' => 'U',
            'Ú' => 'U',
            'Û' => 'U',
            'Ũ' => 'U',
            'á' => 'a',
            'à' => 'a',
            'â' => 'a',
            'ã' => 'a',
            'ç' => 'c',
            'è' => 'e',
            'é' => 'e',
            'ê' => 'e',
            'ẽ' => 'e',
            'ì' => 'i',
            'í' => 'i',
            'î' => 'i',
            'ĩ' => 'i',
            'ò' => 'o',
            'ó' => 'o',
            'ô' => 'o',
            'õ' => 'o',
            'ñ' => 'n',
            'ù' => 'u',
            'ú' => 'u',
            'û' => 'u',
            'ũ' => 'u'
        ];

        // Substitui caracteres mapeados
        $slug = strtr($title, $map);

        // Remove tags HTML e espaços extras
        $slug = strip_tags(trim($slug));

        // Substitui caracteres não permitidos por traços
        $slug = preg_replace('/[^a-zA-Z0-9]+/', '-', $slug);

        // Remove traços duplicados
        $slug = preg_replace('/-+/', '-', $slug);

        // Converte para minúsculas
        return strtolower($slug);
    }

    /**
     * Faz o decode do texto de entidades para HTML
     * @param string Texto com entidades
     * @return string Texto com tag HTML
     */

    public static function decodeHtml($texto): string
    {
        $texto_decode = html_entity_decode($texto);

        return $texto_decode;
    }


    /**
     * Monta a URL de acordo com o ambiente.
     * @param string $url Parte do caminho (ex.: 'admin' ou '/admin').
     * @return string URL completa do ambiente atual.
     */

    public static function url(?string $url = null): string
    {
        $servidor = filter_input(INPUT_SERVER, 'SERVER_NAME');
        $ambiente = ($servidor == 'localhost' ? DEVELOPMENT_URL : PRODUCTION_URL);

        if (str_starts_with($url, '/')) {
            return $ambiente . $url;
        } else {
            return $ambiente . '/' . $url;
        }
    }

    /**
     * Trunca um texto para caber dentro do card
     * @param string $text é o texto completo
     * @param int $limit é o tamanho do texto final
     * @param string valor padrão '...'
     * @return $textoResumido texto truncado concatenado com '...'
     */

    public static function textoResumido(string $text, int $limit, string $continues = '...'): string
    {
        $cleanText = strip_tags(trim($text));
        if (mb_strlen($cleanText) <= $limit) {
            return $cleanText;
        }

        $textoResumido = mb_substr($cleanText, 0, mb_strrpos(mb_substr($cleanText, 0, $limit), ''));

        return $textoResumido . $continues;
    }

    /**
     * Conta o tempo passado desde a publicação (ex.: há 1 minuto, há 3 dias, etc)
     * @param string $data data da publicação
     * @return string tempo passado desde a publicação
     */

    public static function contagemTempo(string $date): string
    {
        $now = strtotime(date('Y-m-d H:i:s'));
        $time = strtotime($date);
        $difference = $now - $time;

        $seconds = $difference;
        $minutes = round($difference / 60);
        $hours = round($difference / 3600);
        $days = round($difference / 86400);
        $weeks = round($difference / 604800);
        $months = round($difference / 2419200);
        $yers = round($difference / 29030400);

        if ($seconds <= 60) {
            return 'agora';
        } elseif ($minutes <= 60) {
            return $minutes == 1 ? 'há 1 minuto' : 'há ' . $minutes . ' minutos';
        } elseif ($hours <= 24) {
            return $hours == 1 ? 'há 1 hora' : 'há ' . $hours . ' horas';
        } elseif ($days <= 30) {
            return $days == 1 ? 'há 1 dia' : 'há ' . $days . 'dias ';
        } elseif ($weeks <= 4) {
            return $weeks == 1 ? 'há 1 semana' : 'há ' . $weeks . ' semanas';
        } elseif ($months <= 12) {
            return $months == 1 ? 'há 1 mês' : 'há ' . $months . ' meses';
        } else {
            return $yers == 1 ? 'há 1 ano' : 'há ' . $yers . ' anos';
        }
    }


    public static function flash(): ?string
    {
        $sessao = new Sessao();
        if ($flash = $sessao->flash()) {
            echo $flash;
        }

        return null;
    }

    /**
     * Gera uma hash aleatória de tamanho fixo.
     * * @param int $tamanho O número de dígitos da hash.
     * @return string A hash gerada.
     */
    public static function gerarHash(int $tamanho = 6): string
    {
        if ($tamanho <= 0) {
            return '';
        }

        $min = pow(36, $tamanho - 1);
        $max = pow(36, $tamanho) - 1;

        $numero_randomico = random_int($min, $max);
        $hash = base_convert($numero_randomico, 10, 36);

        return $hash;
    }

    /**
     * Adiciona o nome de todos os clientes a uma coleção de orçamentos,
     * ou a qualquer array de objetos com 'id_cliente' e que pode receber 'nome_cliente'.
     *
     * @param array $clientes Uma lista de objetos cliente (ou arrays associativos com 'id' e 'nome').
     * @param array $itens Uma lista de objetos (e.g., orçamentos) que contêm 'id_cliente'.
     * @return array Os itens com os nomes dos clientes adicionados.
     */
    public static function colocarTodosNomesClientesPeloId(?array $clientes, ?array $itens): ?array
    {
        if (empty($clientes) || empty($itens)) {
            return null;
        }

        // Para maior flexibilidade, podemos indexar os clientes por ID primeiro
        $clientes_map = [];
        foreach ($clientes as $cliente) {
            $clientes_map[$cliente->id ?? null] = $cliente->nome ?? null;
        }

        return array_map(function ($item) use ($clientes_map) {
            $id_cliente = $item->id_cliente ?? null;
            if ($id_cliente !== null && isset($clientes_map[$id_cliente])) {
                $item->nome_cliente = $clientes_map[$id_cliente];
            } else {
                $item->nome_cliente = 'Nome não encontrado';
            }
            return $item;
        }, $itens);
    }

    /**
     * Retorna o nome de um cliente com base no seu ID a partir de uma lista de clientes.
     *
     * @param array $clientes Uma lista de objetos cliente (ou arrays associativos com 'id' e 'nome').
     * @param mixed $id_cliente O ID do cliente a ser procurado.
     * @return string O nome do cliente ou uma string vazia se não encontrado.
     */
    public static function colocarNomeClientePeloId(?array $clientes, ?int $id_cliente): ?string
    {
        if (empty($clientes) || empty($itens)) {
            return null;
        }

        foreach ($clientes as $cliente) {
            if (($cliente->id ?? null) == $id_cliente) {
                return $cliente->nome ?? 'Nome não encontrado';
            }
        }
        return '';
    }
}
