<div class="d-flex justify-content-between align-items-center item mb-3" data-id="{{ $file->id }}">
    <input type="hidden" name="files[]" value="{{ $file->id }}">
    <div class="d-flex align-items-center gap-2">
        <span class="fal fa-file text-primary"></span>
        <span class="mt-1 font-weight-bold">{{ $file->file_name }}</span>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="mt-1 font-weight-bold">{{ formatFileSize($file->file_size) }}</span>
        <button class="btn btn-sm btn-outline-danger">
            <span class="fal fa-trash"></span>
        </button>
    </div>
</div>