<div class="form-group">
    @if(isset($title))
        <label for="{{ $identify }}" class="form-label">{{ $title }}</label>
    @endif
    <select class="form-control" name="{{ $identify }}" id="{{ $identify }}">
        <option value="">انتخاب کاربر</option>
        @if ($users->isNotEmpty())
            @foreach ($users as $item)
                <option @if(!empty($old) && $item->id == $old) selected="selected" @endif value="{{ $item->id }}">{{ $item->mobile }} - ( {{ $item->first_name . ' ' . $item->last_name }} )</option>
            @endforeach
        @endif
    </select>
</div>
