<?php

namespace App\Service;

use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;

class PdfService
{
    protected Mpdf $mpdf;

    public function __construct(array $config = [])
    {
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $this->mpdf = new Mpdf(array_merge([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_bottom' => 40,
            'margin_top' => 50,
            'fontDir' => array_merge($fontDirs, [
                base_path('public/fonts'),
            ]),
            'fontdata' => $fontData + [
                'iransanse' => [
                    'R' => 'IRANSansWeb(FaNum).ttf',
                    'B' => 'IRANSansWeb(FaNum)_Bold.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ],
            ],
            'default_font' => 'iransanse',
        ], $config));
    }

    public function setFont($family, $style = '', $size = 0)
    {
        $this->mpdf->SetFont($family, $style, $size);
    }

    public function setMargins($left, $right, $top)
    {
        $this->mpdf->SetMargins($left, $right, $top);
    }

    public function writeHtml($html)
    {
        $this->mpdf->WriteHTML($html);
    }

    public function output($filename = '', $dest = 'I')
    {
        return $this->mpdf->Output($filename, $dest);
    }

    public function getMpdfInstance(): Mpdf
    {
        return $this->mpdf;
    }
}
