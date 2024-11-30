<?php

namespace Modules\Contract\App\Traits;

use Exception;
use Illuminate\Support\Facades\Storage;
use Throwable;

trait SavesPdfToDiskTrait
{
    /**
     * @throws Throwable
     */
    public function saveToDisk(int $id, string $prefixNameFile): string
    {
        try {
            $model = $this->findModel($id);
            $this->getPreparedHtml($model);

            $pdfContent = $this->pdfService->outputFile('save_contact');

            $timestamp = now()->format('Y-m-d_H-i-s');
            $fileName = sprintf('%s_contract_project_%s_contract_%s.pdf', $prefixNameFile, $model->id, $timestamp);
            $filePath = 'contracts/'.$fileName;

            Storage::disk('private')->put($filePath, $pdfContent);

            return $filePath;
        } catch (Exception $e) {
            report($e);

            return 'An error occurred: '.$e->getMessage();
        }
    }
}
