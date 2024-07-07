<?php

namespace Modules\Chat\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponse;
use DB;
use Exception;
use Illuminate\Support\Facades\Storage;
use Modules\Chat\app\Http\Requests\Admin\Attachment\DestroyRequest;
use Modules\Chat\app\Http\Requests\Admin\Attachment\StoreRequest;
use Modules\Support\app\Models\ChatMessageAttachment;
use View;

class AttachmentController extends Controller
{
    use HasJsonCommonResponse;

    public function upload(StoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $uploadedFile = $request->file('file');

            $originalFileName = $uploadedFile->getClientOriginalName();
            $hashedFileName = $uploadedFile->hashName();
            $fileType = $uploadedFile->getMimeType();
            $fileSize = $uploadedFile->getSize();
            $fileExtension = $uploadedFile->getClientOriginalExtension();

            $pathPrefix = 'chat_files/';
            $filePath = $pathPrefix.$hashedFileName;

            $uploadedFile->storeAs($pathPrefix, $hashedFileName, 'private');

            $file = ChatMessageAttachment::query()
                ->create([
                    'chat_message_id' => null,
                    //TODO Need Work
                    'type' => 1,
                    'file_type' => $fileType,
                    'file_name' => $originalFileName,
                    'file_extension' => $fileExtension,
                    'file_size' => $fileSize,
                    'file_path' => $filePath,
                ]);
            DB::commit();

            $htmlRender = compressHtml(View::make('support::admin.part.row-file-attachment', ['file' => $file]));

            return response()->json([
                'htmlRender' => $htmlRender,
                'result' => 'success',
                'message' => trans('panel.success_store'),
            ]);

        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }

    }

    public function destroy(DestroyRequest $request)
    {
        try {
            $attachment = ChatMessageAttachment::query()
                ->where('id', $request->input('file_id'))
                ->firstOrFail();

            Storage::disk('private')->delete($attachment->file_path);

            $attachment->forceDelete();

            return response()->json([
                'result' => 'success',
                'message' => trans('panel.success_delete'),
            ]);

        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }
}
