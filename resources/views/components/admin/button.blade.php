<button @if ($identify) id="{{ $identify }}" @endif
        @if ($disabled) disabled="disabled" @endif
        @if ($onClick) onclick="{{$onClick}}" @endif
        type="{{ $type }}"
        class="btn btn-{{$color}} me-2 @if($hasLoading && $type === 'submit') has-spinner @endif">
    {{ $title }}
</button>
