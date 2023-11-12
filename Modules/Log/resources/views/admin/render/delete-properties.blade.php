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
        @unless(in_array($attribute, $excludeAttributes))
            <tr>
                <td>{{ formatAttributeName($attribute) }}</td>
                <td>{{ $oldValue }}</td>
            </tr>
        @endunless
    @endforeach
    </tbody>
</table>
