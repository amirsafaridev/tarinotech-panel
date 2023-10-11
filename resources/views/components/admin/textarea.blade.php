<div class="mb-3">
    <label for="{{ $identify }}" class="form-label">{{ $title }}</label>
    <textarea rows="{{ $rows }}" class="form-control" name="{{ $identify }}" id="{{ $identify }}">{{ $old ?? '' }}</textarea>
    @if($description)
        <p class="form-help">{{ $description }}</p>
    @endif
</div>
