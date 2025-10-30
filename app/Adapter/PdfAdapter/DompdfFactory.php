<?php

namespace App\Adapter\PdfAdapter;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Adapter\PdfAdapter\DompdfFactoryInterface;

final class DompdfFactory implements DompdfFactoryInterface
{
    public function create(array $options): Dompdf
    {
        $dompdfOptions = new Options();
        
        $dompdfOptions->set('defaultFont', 'DejaVu Sans');
        $dompdfOptions->set('isHtml5ParserEnabled', true);
        $dompdfOptions->setChroot($options['chroot'] ?? __DIR__); 
        $dompdfOptions->set('isRemoteEnabled', true);
        
        if (isset($options['defaultFont'])) {
            $dompdfOptions->setDefaultFont($options['defaultFont']);
        }
        if (isset($options['isRemoteEnabled'])) {
            $dompdfOptions->setIsRemoteEnabled((bool) $options['isRemoteEnabled']);
        }

        $dompdf = new Dompdf($dompdfOptions);
        
        return $dompdf;
    }
}