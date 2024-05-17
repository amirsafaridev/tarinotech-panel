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