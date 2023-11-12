@props([ 'newProperties'])

@php
    $excludeAttributes = ['created_at', 'updated_at'];
@endphp

<table class="table">
    <thead>
    <tr>
        <th width="25%">ویژگی</th>
        <th>مقدار جدید</th>
    </tr>
    </thead>
    <tbody>
    @foreach($newProperties as $attribute => $oldValue)
        @unless(in_array($attribute, $excludeAttributes))
            <tr>
                <td>{{ formatAttributeName($attribute) }}</td>
                <td>{{ $newProperties[$attribute] ?? 'Not set' }}</td>
            </tr>
        @endunless
    @endforeach
    </tbody>
</table>
