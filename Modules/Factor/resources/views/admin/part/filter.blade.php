<form class="row mb-4" action="{{ route('admin.factor.index') }}">

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.input
                identify="project"
                title="پروژه"
                />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.input
                identify="admin"
                title="کارشناس"
                />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.select-enum
                identify="gateway"
                title="درگاه"
                :enum-class="\Modules\Factor\app\Enums\PaymentGateway::class"
        />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.input
                identify="price_from"
                title="قیمت از (ریال)"
                />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.select-enum
                identify="project_type"
                title="نوع پروژه"
                :enum-class="\Modules\Project\app\Enums\ProjectBase::class"
        />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        @php
            $dateTypeItems = [
                'created_at'=>'تاریخ ایجاد',
                'paid_at'=>'تاریخ پرداخت',
            ];
        @endphp
        <x-admin.select-simple
                identify="date_column"
                title="فیلد تاریخ"
                :items="$dateTypeItems"

        />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.input
                identify="from_date"
                title="از تاریخ"
                :is-date-picker="true"
        />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.input
                identify="to_date"
                title="تا تاریخ"
                :is-date-picker="true"
        />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.input
                identify="price_to"
                title="قیمت تا (ریال)"
                />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.select-enum
                identify="status"
                title="وضعیت"
                :enum-class="\Modules\Factor\app\Enums\FactorStatus::class"
                />
    </div>

</form>