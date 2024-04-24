@php
    $_index = '__INDEX__';
    if (isset($index)) $_index = $index;
    $id = null;
    $title = '';
    $price = '';
    $taxAmount = 0;
    $discount = 0;
    $finalPrice = 0;
    $transactionCategoryId = 0;
    if (isset($item)){
        $id = $item->id;
        $title = $item->title;
        $price = $item->price;
        $taxAmount = $item->tax_amount;
        $discount = $item->discount;
        $finalPrice = $item->final_price;
        $transactionCategoryId = $item->transaction_category_id;
    }
@endphp
<div class="card card-factor-item">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex gap-2">
            <div class="card-title">ایتم فاکتور</div>
            <button class="btn btn-danger btn-sm btn-remove" type="button">حذف ردیف</button>
        </div>
        <h4 class="factor-item-price p-0 m-0">{{ number_format($finalPrice) }}</h4>
    </div>
    <div class="card-body pb-4">
        @if($id)
            <x-admin.input identify="item[{{ $_index }}][id]" :old="$id" type="hidden"/>
            <x-admin.input identify="item[{{ $_index }}][action]" old="update" type="hidden"/>
        @else
            <x-admin.input identify="item[{{ $_index }}][action]" old="store" type="hidden"/>
        @endif

        <div class="row">
            <div class="col-12 col-md-4 col-lg-2">
                <x-admin.input identify="item[{{ $_index }}][title]" title="عنوان" :old="$title"/>
            </div>
            <div class="col-12 col-md-4 col-lg-2">
                <x-admin.select-model identify="item[{{ $_index }}][transaction_category_id]" title="نوع واریزی"  :items="$categories" key="id" value="title" :old="$transactionCategoryId"/>
            </div>
            <div class="col-12 col-md-4 col-lg-2">
                <x-admin.input identify="item[{{ $_index }}][price]" title="مبلغ (ریال)" :add-class="['price-input','calc']" :old="$price"/>
            </div>
            <div class="col-12 col-md-4 col-lg-2">
                <x-admin.input identify="item[{{ $_index }}][tax]" title=" مالیات بر ارزش افزوده {{ config('factor.tax') }} درصد (ریال)" :add-class="['tax-input','calc']" :read-only="true" :old="$taxAmount"/>
            </div>
            <div class="col-12 col-md-4 col-lg-2">
                <x-admin.input identify="item[{{ $_index }}][discount]" title="تخفیف (ریال)" :add-class="['offer-input','calc']" :old="$discount"/>
            </div>
        </div>
    </div>
</div>
