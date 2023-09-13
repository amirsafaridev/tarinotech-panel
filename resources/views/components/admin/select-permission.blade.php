<div class="form-group">
    <label for="{{ str_replace('[]','',$identify) }}" class="form-label">{{ $title }}</label>
    <select class="form-control" name="{{ $identify }}" id="{{ str_replace('[]','',$identify) }}" {{ $attributes }}>
        @if ($items->isNotEmpty())
            @foreach ($items as $item)
                <option @if(!empty($old) && $item->{$key} == $old) selected="selected"
                        @endif value="{{ $item->{$key} }}">{{ trans('permission.'.$item->{$value}) }}</option>
            @endforeach
        @endif
    </select>
</div>
