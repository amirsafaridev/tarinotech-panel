<div class="form-group">
    @if (isset($title))
        <label for="{{ $identify }}" class="form-label">{{ $title }}</label>
    @endif
    <select class="form-control" name="{{ $identify }}" id="{{ $identify }}">
        <option value="">انتخاب کاربر</option>
        @if ($users->isNotEmpty())
            @foreach ($users as $user)
                @php
                    $isSelected = !empty($old) && $user->id == $old;
                    $fullName = $user->first_name . ' ' . $user->last_name;
                @endphp
                <option {{ $isSelected ? 'selected' : '' }} value="{{ $user->id }}">{{ $user->mobile }} - ( {{ $fullName }} )</option>
            @endforeach
        @endif
    </select>
</div>
