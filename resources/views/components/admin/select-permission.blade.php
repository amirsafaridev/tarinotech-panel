<div class="form-group">
    <label for="{{ sanitizedIdentify($identify) }}" class="form-label">{{ $title }}</label>
    <select class="form-control" name="{{ $identify }}" id="{{ sanitizedIdentify($identify) }}" {{ $attributes }}>
        @if ($items->isNotEmpty())
            @foreach ($items as $item)
                @php
                    $itemKey = $item->{$key};
                    $isSelected = !empty($old) && $itemKey == $old;
                @endphp
                <option {{ $isSelected ? 'selected' : '' }} value="{{ $itemKey }}">{{ trans('permission.'.$item->{$value}) }}</option>
            @endforeach
        @endif
    </select>
</div>
