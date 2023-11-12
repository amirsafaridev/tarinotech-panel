<?php

namespace App\Helpers\Uploader;

class PhotoUploaderResult
{
    private string $fileName;

    private string $fileThumbName;

    private string $uniqueFileName;

    private string $extension;

    private string $path;

    private string $pathThumb;

    private int $size;

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): PhotoUploaderResult
    {
        $this->path = $path;

        return $this;
    }

    public function getPathThumb(): string
    {
        return $this->pathThumb;
    }

    public function setPathThumb(string $pathThumb): PhotoUploaderResult
    {
        $this->pathThumb = $pathThumb;

        return $this;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): PhotoUploaderResult
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getFileThumbName(): string
    {
        return $this->fileThumbName;
    }

    public function setFileThumbName(string $fileThumbName): PhotoUploaderResult
    {
        $this->fileThumbName = $fileThumbName;

        return $this;
    }

    public function getUniqueFileName(): string
    {
        return $this->uniqueFileName;
    }

    public function setUniqueFileName(string $uniqueFileName): PhotoUploaderResult
    {
        $this->uniqueFileName = $uniqueFileName;

        return $this;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): PhotoUploaderResult
    {
        $this->extension = $extension;

        return $this;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $size): PhotoUploaderResult
    {
        $this->size = $size;

        return $this;
    }
}
