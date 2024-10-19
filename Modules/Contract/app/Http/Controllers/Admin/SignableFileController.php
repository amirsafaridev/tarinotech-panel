<?php

namespace Modules\Contract\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponse;
use Exception;
use Illuminate\Support\Facades\Storage;
use Modules\Contract\App\Models\SignableFile;

class SignableFileController extends Controller
{
    use HasJsonCommonResponse;

    public function download(SignableFile $signableFile)
    {
        try {

            $path = $signableFile->file_path;
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

    public function destroy(SignableFile $signableFile)
    {
        try {

            Storage::disk('private')->delete($signableFile->file_path);

            $signableFile->delete();

            return back()->with('success', 'File successfully deleted');
        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }
}
