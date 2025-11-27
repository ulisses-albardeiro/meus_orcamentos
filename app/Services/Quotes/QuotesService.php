<?php

namespace App\Services\Quotes;

use App\Models\QuoteModel;

class QuotesService implements QuotesInterface
{
    public function __construct(private QuoteModel $quoteModel) {}

    public function calculateTotalQuote(array $data): int
    {
        if (isset($data['valor_orcamento'])) {
            $total =  (int) str_replace(['R$', '.', ',', "\xC2\xA0", ' '], ['', '', '', '', ''], $data['valor_orcamento']);
            return $total;
        }

        $total = 0;
        foreach ($data['itens'] as $item) {
            $value = (int) str_replace(['R$', '.', ',', "\xC2\xA0", ' '], ['', '', '', '', ''], $item['valor']);
            $value = str_replace(',', '.', $value);
            $total += (int)$value * (int)$item['qtd'];
        }

        return $total;
    }

    public function separateDataUser(array $data): array
    {
        $fields = [
            'nome_empresa',
            'email_empresa',
            'telefone_empresa',
            'celular_empresa',
            'cnpj_empresa',
            'facebook',
            'instagram',
            'linkedin',
            'x',
            'youtube',
            'tiktok',
            'cep_empresa',
            'rua_empresa',
            'n_casa_empresa',
            'bairro_empresa',
            'cidade_empresa',
            'uf_empresa'
        ];

        $dataUser = [];

        foreach ($data as $key => $value) {
            if (in_array($key, $fields)) {
                $dataUser[$key] = $value;
            }
        }

        return $dataUser;
    }

    public function separateDataClient(array $data): array
    {
        $fields = [
            'nome_cliente',
            'documento_cliente',
            'telefone_cliente',
            'email_cliente',
            'endereco_cliente',
            'celular_cliente',
            'cep_cliente',
            'rua_cliente',
            'n_casa_cliente',
            'bairro_cliente',
            'cidade_cliente',
            'uf_cliente',
        ];

        $dataClient = [];

        foreach ($data as $key => $value) {
            if (in_array($key, $fields)) {
                $dataClient[$key] = $value;
            }
        }

        return $dataClient;
    }

    public function processesItemsForView(array $data): array
    {
        $processedItems = [];
        foreach ($data['itens'] as $item) {

            if (!isset($item['valor'])) {
                $item['valor'] = 0;
            }

            $cleanValue = (int) round($this->converterValueToFloat($item['valor']) * 100);

            $processItem = $item;
            $processItem['valor_limpo'] = $cleanValue;
            $processItem['valor_float'] = $cleanValue / 100;

            $processedItems[] = $processItem;
        }

        return $processedItems;
    }

    public function converterValueToFloat(string $formattedValue): float
    {
        $cleanValue = preg_replace('/[^\d,\.]/', '', $formattedValue);

        if (preg_match('/^\d{1,3}(?:\.\d{3})*,\d{2}$/', $cleanValue)) {
            $cleanValue = str_replace('.', '', $cleanValue);
            $cleanValue = str_replace(',', '.', $cleanValue);
        }
    
        elseif (preg_match('/^\d{1,3}(?:,\d{3})*\.\d{2}$/', $cleanValue)) {
            $cleanValue = str_replace(',', '', $cleanValue);
        }
    
        elseif (preg_match('/^\d+,\d{2}$/', $cleanValue)) {
            $cleanValue = str_replace(',', '.', $cleanValue);
        }

        return (float) $cleanValue;
    }

    public function findsQuotesByUserId(int $userId): ?array
    {
        return $this->quoteModel->findsQuotesByUserId($userId);
    }

    public function findQuoteByHash(string $hash): ?array
    {
        $dataObj = $this->quoteModel->findQuoteByHash($hash)[0];

        $dataQuoteObj = json_decode($dataObj->orcamento_completo, true);
        $fullData = array_merge((array) $dataObj, $dataQuoteObj);
        return $fullData;
    }

    public function destroyQuote(string $hash): bool
    {
        return $this->quoteModel->destroyQuote($hash);
    }

    public function findQuoteById(int $idOrcamento): ?array
    {
        $data = $this->quoteModel->findQuoteById($idOrcamento)[0];
        $dataQuoteObj = json_decode($data->orcamento_completo, true);
        $fullData = array_merge((array) $data, $dataQuoteObj);

        return $fullData;
    }
}
