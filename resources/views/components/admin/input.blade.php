<div class="mb-3">
    @if ($type !== 'hidden')
        <label for="{{ $identify }}" class="form-label">{{ $title }}</label>
    @endif
    <input type="{{ $type }}"
           class="form-control @if ($isSmall) form-control-sm @endif @if(count($addClass)){{ implode(' ',$addClass) }}@endif"
           name="{{ $identify }}"
           id="{{ $identify }}"
           value="{{ $old ?? '' }}"
           @if ($disabled) disabled @endif
           @if ($readOnly) readonly @endif
    />
    @if ($description)
        <p class="form-help">{{ $description }}</p>
    @endif
</div>
