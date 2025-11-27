<?php

namespace App\Services\Quotes;

interface QuotesInterface
{
    public function calculateTotalQuote(array $data): int;
    public function separateDataUser(array $data): array;
    public function separateDataClient(array $data): array;
    public function processesItemsForView(array $data): array;
    public function converterValueToFloat(string $formattedValue): float;
    public function findsQuotesByUserId(int $userId): ?array;
    public function findQuoteByHash(string $hash): ?array;
    public function findQuoteById(int $quoteId): ?array;
    public function destroyQuote(string $hash): bool;
}
