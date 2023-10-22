@php
    $_index = '__INDEX__';
    if (isset($index)) $_index = $index;
@endphp
<div class="card card-factor-item">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="card-title">ایتم فاکتور</div>
        <h4 class="factor-item-price p-0 m-0">0</h4>
    </div>
    <div class="card-body pb-4">
        <x-admin.input identify="item[{{ $_index }}][title]" title="عنوان" />

        <x-admin.select-simple identify="item[{{ $_index }}][transaction_category_id]" title="نوع واریزی"  :items="[]"/>

        <x-admin.input identify="item[{{ $_index }}][price]" title="مبلغ (ریال)" :add-class="['price-input','calc']" />

        <x-admin.input identify="item[{{ $_index }}][tax]" title="مالیات بر ارزش افزوده ۹ درصد (ریال)" :add-class="['tax-input','calc']" :read-only="true" />

        <x-admin.input identify="item[{{ $_index }}][discount]" title="تخفیف (ریال)" :add-class="['offer-input','calc']" />

        <button class="btn btn-danger btn-sm btn-remove" type="button">حذف ردیف</button>

    </div>
</div>
