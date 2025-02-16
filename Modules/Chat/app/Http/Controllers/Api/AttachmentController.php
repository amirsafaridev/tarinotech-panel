<?php

namespace Modules\Chat\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use DB;
use Exception;
use Illuminate\Support\Facades\Storage;
use Modules\Chat\app\Http\Requests\Api\Attachment\DestroyRequest;
use Modules\Chat\app\Http\Requests\Api\Attachment\StoreRequest;
use Modules\Chat\app\Resources\Attachment\AttachmentResource;
use Modules\Support\app\Models\ChatMessageAttachment;

class AttachmentController extends Controller
{
    use HasApiResponse;

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

            $pathPrefix = 'chat-files/';
            $filePath = $pathPrefix.$hashedFileName;

            $uploadedFile->storeAs('chat-files', $hashedFileName, 'private');

            $attachment = ChatMessageAttachment::query()
                ->create([
                    'chat_message_id' => null,
                    'type' => 1,
                    'file_type' => $fileType,
                    'file_name' => $originalFileName,
                    'file_extension' => $fileExtension,
                    'file_size' => $fileSize,
                    'file_path' => $filePath,
                ]);
            DB::commit();

            $data = new AttachmentResource($attachment);

            return $this->successResponse($data);

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

            return $this->successResponse(null, 'Attachment Deleted');

        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }
}
