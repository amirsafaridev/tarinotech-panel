<?php

namespace Modules\Chat\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Storage;

class AttachmentStreamController extends Controller
{
    public function read($path)
    {
        try {

            $fullPath = str_replace('|', '/', $path);
            $file = Storage::disk('private')->get($fullPath);

            if (! $file) {
                abort(404);
            }

            return response($file, 200)
                ->header('Content-Type', Storage::disk('private')
                    ->mimeType($fullPath));
        } catch (Exception $exception) {
            report($exception);

            abort(404);
        }
    }
}
