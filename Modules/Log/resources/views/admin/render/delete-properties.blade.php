@props(['oldProperties'])

@php
    $excludeAttributes = ['created_at', 'updated_at'];
@endphp

<table class="table">
    <thead>
    <tr>
        <th width="25%">ویژگی</th>
        <th>مقدار قبل</th>
    </tr>
    </thead>
    <tbody>
    @foreach($oldProperties as $attribute => $oldValue)
        @unless(in_array($attribute, $excludeAttributes) && isset($oldValue) && is_string($oldValue))
            <tr>
                <td>{{ formatAttributeName($attribute) }}</td>
                @if(!is_array($oldValue))
                    <td>{{ $oldValue }}</td>
                @else
                    <td>{{ json_encode($oldValue) }}</td>
                @endif

            </tr>
        @endunless
    @endforeach
    </tbody>
</table>
