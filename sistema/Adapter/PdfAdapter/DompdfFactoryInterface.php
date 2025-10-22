<?php

namespace sistema\Adapter\PdfAdapter;

use Dompdf\Dompdf;

interface DompdfFactoryInterface
{
    public function create(array $options): Dompdf;
}
