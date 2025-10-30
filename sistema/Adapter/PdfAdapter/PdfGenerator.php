<?php

namespace sistema\Adapter\PdfAdapter;

class PdfGenerator implements PdfInterface
{
    public function __construct(private DompdfFactoryInterface $dompdfFactory) {}

    /**
     * Generate a PDF from HTML content.
     *
     * @param string $content The HTML content to render in the PDF.
     * @param array $options Optional configuration options:
     *                       - 'size' (string): Paper size, e.g., 'A4'. Default 'A4'.
     *                       - 'orientation' (string): Page orientation, 'portrait' or 'landscape'. Default 'portrait'.
     *
     * @return string The raw PDF output as a string.
     */
    public function generate(string $content, array $options = []): string
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
