<div>
    <label for="{{ $identify }}" class="form-label">{{ $title }}</label>
    <select  class="form-control" name="{{ $identify }}" id="{{ $identify }}">
        @if($months->isNotEmpty())
            @foreach($months as $month)
                <option value="{{ $month[$value] }}">{{ $month['month_name'] }}</option>
            @endforeach
        @endif
    </select>
</div>