<?php

namespace sistema\Adapter\PdfAdapter;

class PdfService implements PdfInterface
{
    private DompdfFactoryInterface $dompdfFactory;

    public function __construct(DompdfFactoryInterface $dompdfFactory)
    {
        $this->dompdfFactory = $dompdfFactory;
    }

    public function generatePDF(string $content, array $options = []): string
    {
        $dompdf = $this->dompdfFactory->create($options);
        $paperSize = $options['size'] ?? 'A4';
        $orientation = $options['orientation'] ?? 'portrait';
        
        $dompdf->setPaper($paperSize, $orientation);
        $dompdf->loadHtml($content);
        $dompdf->render();
        
        return $dompdf->output();
    }
}
