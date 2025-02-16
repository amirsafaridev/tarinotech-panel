<?php

namespace App\Helpers\Uploader;

use App\Helper\Helper;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use stdClass;

class FileUploader implements IFileUploaderBuilder
{
    private stdClass $config;

    private string $fileHashName = '';

    private string $fileExtension = '';

    private string $fileOrgName = '';

    private int $fileSize = 0;

    private string $fileType = '';

    private string $pathStore = '';

    private string $pathWithAssets = '';

    private Carbon $createdAt;

    public function __construct()
    {
        $this->reset();
    }

    public function reset(): void
    {
        $this->config = new stdClass();
    }

    public function field(string $field): IFileUploaderBuilder
    {
        $this->config->field = $field;

        return $this;
    }

    public function path(string $path): IFileUploaderBuilder
    {
        $this->config->path = $path;

        return $this;
    }

    public function userDirectory(int $userId): IFileUploaderBuilder
    {
        $this->config->path = Helper::userDirectory($userId);

        return $this;
    }

    public function upload()
    {
        try {
            if (! isset($this->config->field)) {
                throw new Exception('Please Select Field Name');
            }

            if (! request()->hasFile($this->config->field)) {
                throw new Exception('Dont Send Image File');
            }

            if (! isset($this->config->path)) {
                $this->config->path = 'uploads/';
            }

            $file = request()->file($this->config->field);

            $this->fileHashName = uniqid(md5(Str::random(20))).'.'.$file->extension();
            $this->fileExtension = $file->extension();
            $this->fileOrgName = $file->getClientOriginalName();
            $this->fileSize = $file->getSize();
            $this->fileType = $file->getType();
            $this->pathStore = $this->config->path.'/'.$this->fileHashName;
            $this->pathWithAssets = asset($this->pathStore);
            $this->createdAt = now();
            $file->move($this->config->path, $this->fileHashName);

            return true;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function makeFileUploaderFormJson(array $object)
    {
        $this->pathWithAssets = $object['path'];
        $this->fileExtension = $object['extension'];
        $this->fileHashName = $object['hash_name'];
        $this->fileOrgName = $object['name'];
        $this->fileSize = $object['size'];
        $this->fileType = $object['type'];
    }

    public function getConfig(): stdClass
    {
        return $this->config;
    }

    public function getFileHashName(): string
    {
        return $this->fileHashName;
    }

    public function getFileExtension(): string
    {
        return $this->fileExtension;
    }

    public function getFileOrgName(): string
    {
        return $this->fileOrgName;
    }

    public function getFileSize(): int
    {
        return $this->fileSize;
    }

    public function getFileType(): string
    {
        return $this->fileType;
    }

    public function getPathStore(): string
    {
        return $this->pathStore;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    public function getPathWithAssets(): string
    {
        return $this->pathWithAssets;
    }
}
