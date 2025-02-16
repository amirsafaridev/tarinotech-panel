<?php

namespace App\Helpers\Uploader;

interface IPhotoUploadBuilder
{
    public function oldDelete($filename): IPhotoUploadBuilder;

    public function path(string $path): IPhotoUploadBuilder;

    public function field(string $field): IPhotoUploadBuilder;

    public function thumb(int $width, int $height): IPhotoUploadBuilder;

    public function fit(int $width, int $height): IPhotoUploadBuilder;

    public function resize(int $width, int $height): IPhotoUploadBuilder;
}
