<div class="form-group">
    @if (isset($title))
        <label for="{{ sanitizedIdentify($identify) }}" class="form-label">{{ $title }}</label>
    @endif
    <select class="form-control @if ($isSmall) form-control-sm @endif"
            @if ($multiple) multiple @endif
            @if ($disabled) disabled @endif
            name="{{ $identify }}"
            id="{{ sanitizedIdentify($identify) }}">
        <option value="">انتخاب گزینه</option>

        @if (!empty($items))
            @foreach ($items as $itemKey => $itemValue)
                @php
                    $isSelected = !empty($old) && (is_array($old) ? in_array($itemKey, $old) : $itemKey == $old);
                @endphp
                <option @if ($isSelected) selected="selected" @endif value="{{ $itemKey }}">
                    {{ $itemValue }}
                </option>
            @endforeach
        @endif

    </select>
</div>
