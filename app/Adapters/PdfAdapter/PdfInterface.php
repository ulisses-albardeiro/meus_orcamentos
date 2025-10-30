<?php

namespace App\Adapters\PdfAdapter;

interface PdfInterface
{
    public function generate(string $content, array $options = []): string;
}
