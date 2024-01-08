<div class="d-flex justify-content-between align-items-center item mb-3" id="attachment-{{ $file->id }}" data-id="{{ $file->id }}">
    <input type="hidden" name="files[]" value="{{ $file->id }}">
    <div class="d-flex align-items-center gap-2">
        <span class="fal fa-file text-primary"></span>
        <span class="mt-1 font-weight-bold">{{ $file->file_name }}</span>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="mt-1 font-weight-bold">{{ formatFileSize($file->file_size) }}</span>
        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-attachment"
                data-id="{{ $file->id }}"
                data-chat-message-id="{{ $file->chat_message_id }}">
            <span class="fal fa-trash"></span>
        </button>
    </div>
</div>