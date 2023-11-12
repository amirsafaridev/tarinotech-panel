<?php

namespace App\Helpers\Uploader;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use stdClass;

class PhotoUploader implements IPhotoUploadBuilder
{
    protected stdClass $config;

    public function __construct()
    {
        $this->reset();
    }

    public function reset(): void
    {
        $this->config = new stdClass();
    }

    public function oldDelete($filename): IPhotoUploadBuilder
    {
        $this->config->delete = true;
        $this->config->delete_filename = $filename;

        return $this;
    }

    public function path(string $path): IPhotoUploadBuilder
    {
        $this->config->path = 'uploads/'.$path.'/';

        return $this;
    }

    public function field(string $field): IPhotoUploadBuilder
    {
        $this->config->field = $field;

        return $this;
    }

    public function thumb(int $width, int $height): IPhotoUploadBuilder
    {
        $this->config->thumb = true;
        $this->config->thumbHeight = $height;
        $this->config->thumbWidth = $width;

        return $this;
    }

    public function fit(int $width, int $height): IPhotoUploadBuilder
    {
        $this->config->fit = true;
        $this->config->fitHeight = $height;
        $this->config->fitWidth = $width;

        return $this;
    }

    public function resize(int $width, int $height): IPhotoUploadBuilder
    {
        $this->config->resize = true;
        $this->config->resizeHeight = $height;
        $this->config->resizeWidth = $width;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function upload(): PhotoUploaderResult
    {
        try {
            $photoUploaderResult = new PhotoUploaderResult();

            $fileUploadInstance = $this->makeFileUploaderInstance();
            $fileSize = $fileUploadInstance->getSize();

            $this->setPath();

            $uniqueName = $this->generateUniqueName();

            $pathSaveOriginal = $this->saveOriginalPhoto($uniqueName, $fileUploadInstance);

            $pathSaveThumb = $this->saveThumbImage($uniqueName, $fileUploadInstance);

            $photoUploaderResult
                ->setPath($pathSaveOriginal)
                ->setPathThumb($pathSaveThumb)
                ->setFileName($fileUploadInstance->getClientOriginalName())
                ->setUniqueFileName($uniqueName)
                ->setExtension($fileUploadInstance->getExtension())
                ->setSize($fileSize);

            return $photoUploaderResult;

        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    private function makeFileUploaderInstance(): UploadedFile
    {

        if (! isset($this->config->field)) {
            throw new Exception('Please Select Field Name');
        }

        $fieldName = $this->config->field;

        if (! request()->hasFile($fieldName)) {
            throw new Exception('Dont Send Image File');
        }

        return request($fieldName);
    }

    private function setPath(): void
    {
        if (! isset($this->config->path)) {
            $this->config->path = 'uploads/';
        }
    }

    private function generateUniqueName(): string
    {
        return uniqid(md5(Str::random(20)));
    }

    private function saveOriginalPhoto(string $uniqueName, UploadedFile $fileUploadInstance): string
    {

        $extension = $fileUploadInstance->extension();
        $path = $this->config->path.$uniqueName.'.'.$extension;

        $image = Image::make($fileUploadInstance);
        $this->applyImageManipulations($image);
        $image->save($path);

        return $path;
    }

    private function applyImageManipulations(\Intervention\Image\Image $image): void
    {
        if (isset($this->config->fit)) {
            $image->fit($this->config->fitWidth, $this->config->fitHeight);
        }

        if (isset($this->config->resize)) {
            $image->resize($this->config->resizeWidth, $this->config->resizeHeight);
        }
    }

    private function saveThumbImage(string $uniqueName, UploadedFile $fileUploadInstance): string
    {
        if (isset($this->config->thumb)) {
            $path = $this->config->path.'thumb/'.$uniqueName.'.'.$fileUploadInstance->extension();
            $image = Image::make($fileUploadInstance);
            $image->fit($this->config->thumbWidth, $this->config->thumbHeight);
            $image->save($path);

            return $path;
        }

        return '';
    }
}
