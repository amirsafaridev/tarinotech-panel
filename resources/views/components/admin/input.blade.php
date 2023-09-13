<div class="mb-3">
    @if($type != 'hidden')
        <label for="{{ $identify }}" class="form-label">{{ $title }}</label>
    @endif
    <input type="{{ $type }}" class="form-control" name="{{ $identify }}" id="{{ $identify }}" value="{{ $old ?? '' }}"  {{ $attributes }}>
</div>
