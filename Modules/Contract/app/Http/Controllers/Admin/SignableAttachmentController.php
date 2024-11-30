<?php

namespace Modules\Contract\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Exception;
use Illuminate\Support\Facades\Storage;
use Modules\Admin\app\Models\Admin;
use Modules\Contract\app\Http\Requests\Admin\SignableAttachment\DestroyRequest;
use Modules\Contract\app\Http\Requests\Admin\SignableAttachment\UploadRequest;
use Modules\Contract\app\Models\SignableAttachment;

class SignableAttachmentController extends Controller
{
    use HasJsonCommonResponseTrait;

    public function upload(UploadRequest $request)
    {
        try {

            $uploadedFile = $request->file('file');

            $originalFileName = $uploadedFile->getClientOriginalName();
            $hashedFileName = $uploadedFile->hashName();
            $fileType = $uploadedFile->getMimeType();
            $fileSize = $uploadedFile->getSize();
            $fileExtension = $uploadedFile->getClientOriginalExtension();

            $pathPrefix = 'contract_attachments/';
            $filePath = $pathPrefix.$hashedFileName;

            $uploadedFile->storeAs($pathPrefix, $hashedFileName, 'private');

            $attachment = SignableAttachment::query()->create([
                'user_type' => Admin::class,
                'user_id' => auth()->id(),
                'file_type' => $fileType,
                'file_name' => $originalFileName,
                'file_extension' => $fileExtension,
                'file_size' => $fileSize,
                'file_path' => $filePath,
                'is_used' => false,
            ]);

            return response()->json([
                'message' => 'File uploaded successfully',
                'attachment' => $attachment,
            ]);
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(DestroyRequest $request)
    {
        try {
            $id = $request->input('id');

            $attachment = SignableAttachment::query()->findOrFail($id);

            Storage::disk('private')->delete($attachment->file_path);

            $attachment->delete();

            return response()->json(['success' => true, 'message' => 'فایل با موفقیت حذف شد.']);
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
