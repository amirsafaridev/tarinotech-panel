<?php

namespace App\Foundation\Contracts;

use Illuminate\Database\Eloquent\Model;

interface PrintControllerInterface
{
    public function index(int $id);

    public function findModel(int $id): Model;

    public function fillData(string $view, Model $model): string;

    public function getViewPath(): string;

    public function setupPdfService(): void;

    public function fileName(Model $model): string;
}
