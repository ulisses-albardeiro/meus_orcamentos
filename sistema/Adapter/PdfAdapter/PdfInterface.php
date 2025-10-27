<?php

namespace sistema\Adapter\PdfAdapter;

interface PdfInterface
{
    public function generatePDF(string $content, array $options = []): string;
}
