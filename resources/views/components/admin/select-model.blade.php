<div class="form-group">
    @if(isset($title))
        <label for="{{ $identify }}" class="form-label">{{ $title }}</label>
    @endif
    <select class="form-control" name="{{ $identify }}" id="{{ $identify }}">
        <option value="">انتخاب گزینه</option>
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
