@props(['oldProperties', 'newProperties'])

@php
    $excludeAttributes = ['created_at', 'updated_at'];
@endphp

<table class="table">
    <thead>
    <tr>
        <th width="30%">ویژگی</th>
        <th>مقدار قبل</th>
        <th>مقدار جدید</th>
    </tr>
    </thead>
    <tbody>
    @foreach($oldProperties as $attribute => $oldValue)
        @unless(in_array($attribute, $excludeAttributes))
            <tr>
                <td>{{ formatAttributeName($attribute) }}</td>
                <td>{{ $oldValue }}</td>
                <td>{{ $newProperties[$attribute] ?? 'Not set' }}</td>
            </tr>
        @endunless
    @endforeach
    </tbody>
</table>
