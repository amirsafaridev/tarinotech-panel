<div class="form-group">
    <label for="{{ $identify }}" class="form-label">{{ $title }}</label>
    <select class="form-control" name="{{ $identify }}" id="{{ $identify }}" {{ $attributes }}>
        @if ($items->isNotEmpty())
            @foreach ($items as $item)
                <option @if($item->id == $old) selected="selected" @endif value="{{ $item->id }}">{{ $item->province?->name }} - {{ $item->name }}</option>
            @endforeach
        @endif
    </select>
</div>
