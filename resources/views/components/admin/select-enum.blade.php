<div class="form-group mb-3">
    <label for="{{ str_replace('[]','',$identify) }}" class="form-label">{{ $title }}</label>
    <select class="form-control" name="{{ $identify }}" id="{{ str_replace('[]','',$identify) }}" @if($multiple) multiple @endif>
        @if($withOption)
            <option value="">انتخاب گزینه</option>
        @endif
        @foreach ($enumClass::asSelectArray() as $key=>$value)
            <option @if(!empty($old) && $key == $old) selected="selected" @endif value="{{ $key }}">{{ $value }}</option>
        @endforeach
    </select>
    @if($description)
        <p class="form-help">{{ $description }}</p>
    @endif
</div>
