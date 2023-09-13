<div class="form-group">
    <label for="{{ $id ?? $identify }}" class="form-label">{{ $title }}</label>
    <select class="form-control" name="{{ $identify }}" id="{{ $id ?? $identify }}" {{ $attributes }}>
        @if ($items->isNotEmpty())
            @foreach ($items as $item)
                @if(isset($item['key']))
                    <option @if(!empty($old) && $item[$key] == $old) selected="selected" @endif value="{{ $item[$key] }}">{{ $item[$value] }}</option>
                @else
                    <option @if(!empty($old) && $item->{$key} == $old) selected="selected" @endif value="{{ $item->{$key} }}">{{ $item->{$value} }}</option>
                @endif
            @endforeach
        @endif
    </select>
</div>
