<?php

namespace App\Helpers\Uploader;

interface IFileUploaderBuilder
{
    public function field(string $field): IFileUploaderBuilder;

    public function path(string $path): IFileUploaderBuilder;

    public function userDirectory(int $userId): IFileUploaderBuilder;
}
