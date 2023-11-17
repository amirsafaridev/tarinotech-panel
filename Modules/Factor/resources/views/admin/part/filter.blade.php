<form class="row mb-4" action="{{ route('admin.factor.index') }}">
    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.input
                identify="id"
                title="شناسه"
                :old="request('id')"/>
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.input
                identify="title"
                title="عنوان"
                :old="request('title')"/>
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.input
                identify="price_from"
                title="قیمت از (ریال)"
                :old="request('price_from')"/>
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.input
                identify="price_to"
                title="قیمت تا (ریال)"
                :old="request('price_to')"/>
    </div>

    <div class="col-12 col-md-3 col-xl-2">
       <x-admin.select-simple identify="sort"
                               title="مرتب سازی"
                               :items="$sortItems"
                               :old="request('sort')"/>
    </div>

    <div class="col-12 col-md-3 col-xl-2 d-flex align-items-end justify-content-end">
        <button class="btn btn-block btn-primary mb-4">اعمال</button>
    </div>

</form>