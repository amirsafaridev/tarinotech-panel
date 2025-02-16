<div class="row mb-4">

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.input
                identify="search"
                title="جستجو"
                :old="request('search')"
                :is-small="true"
        />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.input
                identify="admin"
                title="کارشناس"
                :is-small="true"
                :old="request('admin')"
                />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.select-enum
                identify="status"
                title="وضعیت امضاء"
                :enum-class="\Modules\Contract\app\Enums\SignableStatus::class"
                :old="request('status')"
                :is-small="true"
        />
    </div>

    @php
        $orderItems=[
            'created_at|desc'=>'جدیدترین ها',
            'created_at|asc'=>'قدیمی ترین ها',
        ];
    @endphp

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.select-simple
                identify="sort"
                title="مرتب سازی"
                :items="$orderItems"
                :old="request('sort')"
                :is-small="true"
        />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <button class="btn btn-primary btn-sm">فیلتر</button>
    </div>

</div>
