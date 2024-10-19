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
        <x-admin.select-model
                identify="type"
                title="نوع"
                key="id"
                value="path"
                :items="$types"
                :is-small="true"
                :old="request('type')"
            />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.select-model
                identify="status"
                title="وضعیت"
                key="id"
                value="path"
                :items="$statuses"
                :is-small="true"
                :old="request('status')"
                />
    </div>

    @php
        $signItems=[
            \Modules\Contract\app\Enums\SignableStatus::Signed=>'امضاء شده',
            \Modules\Contract\app\Enums\SignableStatus::Pending=>'امضاء نشده',
        ];
    @endphp

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.select-simple
                identify="is_signed"
                title="وضعیت امضاء"
                :items="$signItems"
                :is-small="true"
                :old="request('is_signed')"
                />
    </div>

    @php
        $signItems=[
            \Modules\Contract\app\Enums\UserSignableStatus::Accepted=>'امضاء شده',
            \Modules\Contract\app\Enums\UserSignableStatus::Pending=>'امضاء نشده',
        ];
    @endphp

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.select-simple
                identify="is_signed_user"
                title="وضعیت امضاء کارفرما"
                :items="$signItems"
                :is-small="true"
                :old="request('is_signed_user')"
        />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        @php
            $dateTypeItems = [
                'projects.created_at'=>'تاریخ ایجاد',
                'projects.agreement_at'=>'تاریخ قرارداد',
            ];
        @endphp
        <x-admin.select-simple
                identify="date_column"
                title="فیلد تاریخ"
                :items="$dateTypeItems"
                :old="request('date_column')"
                :is-small="true"

        />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.input
                identify="from_date"
                title="از تاریخ"
                :is-date-picker="true"
                :old="request('from_date')"
                :is-small="true"
        />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.input
                identify="to_date"
                title="تا تاریخ"
                :is-date-picker="true"
                :old="request('to_date')"
                :is-small="true"
        />
    </div>

    @php
        $orderItems=[
            'projects.created_at-desc'=>'جدیدترین ها',
            'projects.created_at-asc'=>'قدیمی ترین ها',
            'projects.price-desc'=>'قیمت صعودی',
            'projects.price-asc'=>'قیمت نزولی',
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
