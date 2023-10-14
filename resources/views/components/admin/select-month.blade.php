<div class="form-group">
    <label for="{{ $id ?? $identify }}" class="form-label">{{ $title }}</label>
    <select class="form-control" name="{{ $identify }}" id="{{ $id ?? $identify }}">
        @if ($items->isNotEmpty())
            @foreach ($items as $item)
                @php
                    $itemKey = isset($item['key']) ? $item[$key] : $item->{$key};
                @endphp
                <option @if (!empty($old) && $itemKey == $old) selected="selected" @endif value="{{ $itemKey }}">
                    {{ isset($item['key']) ? $item[$value] : $item->{$value} }}
                </option>
            @endforeach
        @endif
    </select>
</div>
