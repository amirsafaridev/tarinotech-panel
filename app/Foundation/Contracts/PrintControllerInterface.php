<?php

namespace App\Foundation\Contracts;

use Illuminate\Database\Eloquent\Model;

interface PrintControllerInterface
{
    /**
     * Save the generated PDF to disk.
     */
    public function saveToDisk(int $id, string $prefixNameFile): string;

    /**
     * Find the project model by ID.
     */
    public function findModel(int $id): Model;

    /**
     * Fill the placeholders in the view with project data.
     */
    public function fillData(string $view, Model $model): string;

    /**
     * Get the path to the view used for generating the PDF.
     */
    public function getViewPath(Model $model): string;

    /**
     * Configure the PDF service with headers, footers, and other settings.
     */
    public function setupPdfService(?Model $model = null): void;

    /**
     * Get the name of the generated file.
     */
    public function fileName(Model $model): string;

    /**
     * Prepare the HTML content for the PDF.
     */
    public function getPreparedHtml(Model $model): string;
}
