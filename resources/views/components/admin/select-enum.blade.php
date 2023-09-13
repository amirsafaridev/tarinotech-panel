<div class="form-group">
    <label for="{{ $identify }}" class="form-label">{{ $title }}</label>
    <select class="form-control" name="{{ $identify }}" id="{{ $identify }}" {{ $attributes }}>
        @foreach ($enumClass::asSelectArray() as $key=>$value)
            <option @if(!empty($old) && $key == $old) selected="selected" @endif value="{{ $key }}">{{ $value }}</option>
        @endforeach
    </select>
</div>
