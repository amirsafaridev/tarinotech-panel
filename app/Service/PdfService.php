<?php

namespace App\Service;

use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;
use Mpdf\MpdfException;

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

    /**
     * @throws MpdfException
     */
    public function setFont($family, $style = '', $size = 0): void
    {
        $this->mpdf->SetFont($family, $style, $size);
    }

    public function setMargins($left, $right, $top): void
    {
        $this->mpdf->SetMargins($left, $right, $top);
    }

    /**
     * @throws MpdfException
     */
    public function writeHtml($html): void
    {
        $this->mpdf->WriteHTML($html);
    }

    /**
     * @throws MpdfException
     */
    public function output($filename = '', $dest = 'I'): ?string
    {
        return $this->mpdf->Output($filename, $dest);
    }

    /**
     * @throws MpdfException
     */
    public function outputFile($filename = ''): ?string
    {
        return $this->mpdf->Output($filename, 'S');
    }

    public function getMpdfInstance(): Mpdf
    {
        return $this->mpdf;
    }
}
