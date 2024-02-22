<?php

namespace Modules\Stream\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Storage;

use function abort;
use function report;
use function response;

class StreamController extends Controller
{
    public function index($path)
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
