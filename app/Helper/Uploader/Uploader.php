<?php

namespace App\Helper\Uploader;

use Exception;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use stdClass;

class Uploader implements IUploadBuilder
{
    protected $config;

    public function __construct()
    {
        $this->reset();
    }

    public function reset(): void
    {
        $this->config = new stdClass();
    }

    public function oldDelete($filename): IUploadBuilder
    {
        $this->config->delete = true;
        $this->config->delete_filename = $filename;

        return $this;
    }

    public function path(string $path): IUploadBuilder
    {
        $this->config->path = 'uploads/'.$path.'/';

        return $this;
    }

    public function field(string $field): IUploadBuilder
    {
        $this->config->field = $field;

        return $this;
    }

    public function thumb(int $width, int $height): IUploadBuilder
    {
        $this->config->thumb = true;
        $this->config->thumb_height = $height;
        $this->config->thumb_width = $width;

        return $this;
    }

    public function fit(int $width, int $height): IUploadBuilder
    {
        $this->config->fit = true;
        $this->config->fit_height = $height;
        $this->config->fit_width = $width;

        return $this;
    }

    public function resize(int $width, int $height): IUploadBuilder
    {
        $this->config->resize = true;
        $this->config->resize_height = $height;
        $this->config->resize_width = $width;

        return $this;
    }

    /**
     * @return array|string
     */
    public function upload()
    {
        try {
            $result = [];
            if (isset($this->config->delete) && ! Str::contains($this->config->delete_filename, 'default')) {
                if (file_exists($this->config->delete_filename)) {
                    unlink($this->config->delete_filename);
                }
                if (isset($this->config->path)) {
                    $thumbPathDelete = str_replace($this->config->path, $this->config->path.'thumb/', $this->config->delete_filename);
                    if (file_exists($thumbPathDelete)) {
                        unlink($thumbPathDelete);
                    }
                }
            }

            if (! isset($this->config->field)) {
                throw new Exception('Please Select Field Name');
            }

            if (! request()->hasFile($this->config->field)) {
                throw new Exception('Dont Send Image File');
            }

            if (! isset($this->config->path)) {
                $this->config->path = 'uploads/';
            }

            $uniqid = uniqid(md5(Str::random(20)));

            $file = request()->file($this->config->field);
            $path = $this->config->path.$uniqid.'.'.$file->extension();
            $image = Image::make($file);

            if (isset($this->config->fit)) {
                $image->fit($this->config->fit_width, $this->config->fit_height);
            }

            if (isset($this->config->resize)) {
                $image->resize($this->config->resize_width, $this->config->resize_height);
            }
            $image->save($path, 100);
            $result['photo'] = $path;

            if (isset($this->config->thumb)) {
                $path = $this->config->path.'thumb/'.$uniqid.'.'.$file->extension();
                $image = Image::make($file);
                $image->fit($this->config->thumb_width, $this->config->thumb_height);
                $image->save($path);
                $result['photo_thumb'] = $path;
            }

            $result['file_extension'] = $file->extension();
            $result['file_size'] = $file->getSize();
            $result['file_name'] = $file->getClientOriginalName();
            $result['file_type'] = $file->getType();

            return $result;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
