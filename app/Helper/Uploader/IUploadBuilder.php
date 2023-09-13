<?php

namespace App\Helper\Uploader;

interface IUploadBuilder
{
    public function oldDelete($filename): IUploadBuilder;

    public function path(string $path): IUploadBuilder;

    public function field(string $field): IUploadBuilder;

    public function thumb(int $width, int $height): IUploadBuilder;

    public function fit(int $width, int $height): IUploadBuilder;

    public function resize(int $width, int $height): IUploadBuilder;
}
