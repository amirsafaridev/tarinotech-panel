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

            $file = Storage::disk('private')->get($path);

            if (! $file) {
                abort(404);
            }

            return response($file, 200)
                ->header('Content-Type', Storage::disk('private')
                    ->mimeType($file));
        } catch (Exception $exception) {
            report($exception);

            abort(404);
        }
    }
}
