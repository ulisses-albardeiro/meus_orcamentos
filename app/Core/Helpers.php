<?php

namespace App\Core;

use DateTime;
use IntlDateFormatter;
use App\Core\Session;

class Helpers
{
    /**
     * Redirects the browser to a specified URL.
     *
     * This method sends an HTTP 302 header to redirect the browser
     * to the provided URL. If no URL is given, it redirects to the default URL.
     *
     * @param string|null $url The URL to redirect to. If null, redirects to the default URL (404).
     *
     */
    public static function redirect(?string $url = null): void
    {
        header('HTTP/1.1 302 Found');
        $local = ($url ? self::url($url) : self::url());
        header("Location: {$local}");
        exit;
    }

    /**
     * Retrieves the current environment.
     *
     * @return bool Returns true if running on localhost, and false if running in production.
     */
    public static function localhost(): bool
    {
        $server = filter_input(INPUT_SERVER, 'SERVER_NAME');

        if ($server == 'localhost') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Redirects the user back to the previous page using the HTTP `Referer` header.
     * If the `Referer` header is not available, it redirects to a default page (dashboard).
     *
     * @return void
     * @throws Exception If the redirect fails (although this is unlikely).
     */
    public static function back(): void
    {
        if (isset($_SERVER['HTTP_REFERER'])) {
            $lastePage = $_SERVER['HTTP_REFERER'];
        } else {
            $lastePage = 'home';
        }
        header("Location: $lastePage");
        exit();
    }

    /**
     *Validates the email, requiring an '@' and a '.' to be approved
     * @param string email
     * @return bool return with true or false
     */

    public static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Sanitizes a string and generates a URL-friendly slug.
     *
     * @param string $title The post title.
     * @return string A slug without symbols or accents, using lowercase letters and hyphens.
     */
    public static function slug(string $title): string
    {
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

        $slug = strtr($title, $map);

        $slug = strip_tags(trim($slug));
        $slug = preg_replace('/[^a-zA-Z0-9]+/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);

        return strtolower($slug);
    }

    /**
     * Builds the URL according to the current environment.
     *
     * @param string $url A path segment (e.g., 'admin' or '/admin').
     * @return string The full URL for the current environment.
     */
    public static function url(?string $url = null): string
    {
        $server = filter_input(INPUT_SERVER, 'SERVER_NAME');
        $enviroment = ($server == 'localhost' ? DEVELOPMENT_URL : PRODUCTION_URL);

        if (str_starts_with($url, '/')) {
            return $enviroment . $url;
        } else {
            return $enviroment . '/' . $url;
        }
    }

    /**
     * Truncates text to fit inside a card.
     *
     * @param string $text The full text.
     * @param int $limit The maximum length of the final text.
     * @param string $continues The suffix to append (default: '...').
     * @return string The truncated text concatenated with the suffix.
     */
    public static function truncateText(string $text, int $limit, string $continues = '...'): string
    {
        $cleanText = strip_tags(trim($text));
        if (mb_strlen($cleanText) <= $limit) {
            return $cleanText;
        }

        $truncateText = mb_substr($cleanText, 0, mb_strrpos(mb_substr($cleanText, 0, $limit), ''));

        return $truncateText . $continues;
    }

    /**
     * Calculates the time elapsed since publication (e.g., "1 minute ago", "3 days ago").
     *
     * @param string $date The publication date.
     * @return string The time elapsed since publication.
     */
    public static function countTime(string $date): string
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
        $sessao = new Session();
        if ($flash = $sessao->flash()) {
            echo $flash;
        }

        return null;
    }

    /**
     * Generates a random hash with a fixed length.
     *
     * @param int $length The number of characters in the hash.
     * @return string The generated hash.
     */
    public static function hash(int $size = 6): string
    {
        if ($size <= 0) {
            return '';
        }

        $min = pow(36, $size - 1);
        $max = pow(36, $size) - 1;

        $random = random_int($min, $max);
        $hash = base_convert($random, 10, 36);

        return $hash;
    }

    /**
     * Returns the month and year of a given date formatted in Portuguese (e.g. "Outubro de 2025").
     *
     * This method uses the IntlDateFormatter class instead of strftime(), which is deprecated since PHP 8.1.
     * It supports proper locale-based month names with accents for the 'pt_BR' locale.
     *
     * @param string $date A date string in a format recognized by DateTime (e.g. '2025-10-01').
     * @return string The formatted month and year in Portuguese, e.g. "Outubro de 2025".
     */
    public static function monthInPortuguese(string $date): string
    {
        $dateObj = new DateTime($date);
        $formatter = new IntlDateFormatter(
            'pt_BR',
            IntlDateFormatter::LONG,
            IntlDateFormatter::NONE,
            'America/Sao_Paulo',
            IntlDateFormatter::GREGORIAN,
            "LLLL 'de' y"
        );

        return ucfirst($formatter->format($dateObj));
    }

    /**
     * Enriches an array of items with related data from another array, similar to a SQL JOIN.
     *
     * @param array $items         Array of main objects (e.g. despesas)
     * @param array $related       Array of related objects (e.g. categorias)
     * @param string $localKey     Property in $items that matches $foreignKey (e.g. 'id_categoria')
     * @param string $foreignKey   Property in $related that corresponds to the local key (e.g. 'id')
     * @param string $targetField  Property name to add in $items with related data (e.g. 'categoria')
     * @param string $relatedField Property from related object to copy (e.g. 'nome')
     * @return array
     */
    public static function attachRelated(
        ?array $items,
        ?array $related,
        string $localKey,
        string $foreignKey,
        string $targetField,
        string $relatedField
    ): ?array {

        if (empty($items) || empty($related)) {
            return $items;
        }

        $map = [];
        foreach ($related as $rel) {
            $map[$rel->$foreignKey] = $rel->$relatedField ?? null;
        }

        foreach ($items as $item) {
            $key = $item->$localKey ?? null;
            $item->$targetField = $key && isset($map[$key]) ? $map[$key] : null;
        }

        return $items;
    }

    /**
     * Converts an array of items with a field in cents to float values in reais.
     *
     * @param array $items Array of objects or associative arrays.
     * @param string $field The field name that contains the value in cents.
     * @return array The same array with the field converted to float in reais.
     */
    public static function centsToReais(array $items, string $field): array
    {
        return array_map(function ($item) use ($field) {
            if (is_object($item) && isset($item->$field)) {
                $item->$field = $item->$field / 100;
            } elseif (is_array($item) && isset($item[$field])) {
                $item[$field] = $item[$field] / 100;
            }
            return $item;
        }, $items);
    }

    /**
     * Converts a monetary field in Brazilian format (e.g. "1.250,00") to cents (e.g. 12500)
     * for an array of items (arrays or objects).
     *
     * @param array $items Array of arrays or objects containing the field to convert
     * @param string $field Name of the field/property to convert
     * @return array Array with the specified field converted to cents
     */
    public static function ReaisToCents(array $items, string $field): array
    {
        return array_map(function ($item) use ($field) {
            if (is_object($item) && isset($item->$field)) {
                $item->$field = self::ReaisToCentsSingle($item->$field);
            } elseif (is_array($item) && isset($item[$field])) {
                $item[$field] = self::ReaisToCentsSingle($item[$field]);
            }
            return $item;
        }, $items);
    }

    /**
     * Converts a Brazilian-formatted monetary string (e.g. "1.250,00") to cents (e.g. 125000).
     *
     * @param string $value Monetary value as a string
     * @return int Value in cents
     */
    public static function ReaisToCentsSingle(string $value): int
    {
        $value = str_replace(['R$', ' '], '', $value);
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);

        $floatValue = (float) $value;
        return (int) round($floatValue * 100);
    }
}
