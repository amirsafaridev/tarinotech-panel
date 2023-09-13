<div class="mb-3">
    <label for="{{ $identify }}" class="form-label">{{ $title }}</label>
    <textarea rows="8" class="form-control" name="{{ $identify }}" id="{{ $identify }}"  {{ $attributes }}>{{ $old ?? '' }}</textarea>
</div>
