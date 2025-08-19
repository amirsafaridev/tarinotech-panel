<div class="form-group">
    @if (isset($title))
        <label for="{{ sanitizedIdentify($identify) }}" class="form-label">{{ $title }}</label>
    @endif

    <select class="form-control @if ($isSmall) form-control-sm @endif"
            @if ($multiple) multiple @endif
            @if ($disabled) disabled @endif
            name="{{ $identify }}"
            id="{{ sanitizedIdentify($identify) }}">

        @if($hasChoiceOption)
            <option value="">انتخاب گزینه</option>
        @endif

        {{ $items }}
        @if ($items->isNotEmpty())
            @foreach ($items as $item)
                @php
                    $itemKey = isset($item['key']) ? $item[$key] : $item->{$key};
                    $isSelected = !empty($old) && (is_array($old) ? in_array($itemKey, $old) : $itemKey == $old);
                @endphp
                <option @if ($isSelected) selected="selected" @endif
               
                  value="{{ $itemKey }}">
                    {{ isset($item['key']) ? $item[$value] : $item->{$value} }}
                </option>
            @endforeach
        @endif
    </select>
</div>
