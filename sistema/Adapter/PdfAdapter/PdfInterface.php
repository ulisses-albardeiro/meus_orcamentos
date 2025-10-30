<?php

namespace sistema\Adapter\PdfAdapter;

interface PdfInterface
{
    public function generate(string $content, array $options = []): string;
}
