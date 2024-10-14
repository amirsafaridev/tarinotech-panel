<div class="row mb-4">

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.input
                identify="search"
                title="عنوان"
                :old="request('search')"
                :is-small="true"
        />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.input
                identify="project"
                title="پروژه"
                :old="request('project')"
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
                identify="gateway"
                title="درگاه"
                :enum-class="\Modules\Factor\app\Enums\PaymentGateway::class"
                :old="request('gateway')"
                :is-small="true"
        />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.input
                identify="price_from"
                title="قیمت از (ریال)"
                :old="request('price_from')"
                :is-small="true"
                />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.input
                identify="price_to"
                title="قیمت تا (ریال)"
                :old="request('price_to')"
                :is-small="true"
        />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.select-enum
                identify="project_type"
                title="نوع پروژه"
                :enum-class="\Modules\Project\app\Enums\ProjectBase::class"
                :old="request('project_type')"
                :is-small="true"
        />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        @php
            $dateTypeItems = [
                'factors.created_at'=>'تاریخ ایجاد',
                'factors.paid_at'=>'تاریخ پرداخت',
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

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.select-enum
                identify="status"
                title="وضعیت"
                :enum-class="\Modules\Factor\app\Enums\FactorStatus::class"
                :old="request('status')"
                :is-small="true"
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
                identify="project_is_signed"
                title="وضعیت امضاء"
                :items="$signItems"
                :old="request('project_is_signed')"
                :is-small="true"
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
                identify="project_is_signed_user"
                title="وضعیت امضاء کارفرما"
                :items="$signItems"
                :old="request('project_is_signed_user')"
                :is-small="true"
        />
    </div>

    @php
        $orderItems=[
            'factors.created_at-desc'=>'جدیدترین ها',
            'factors.created_at-asc'=>'قدیمی ترین ها',
            'factors.final_price-desc'=>'قیمت صعودی',
            'factors.final_price-asc'=>'قیمت نزولی',
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