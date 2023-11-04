<div class="card">
    <div class="card-header">
        <h3 class="card-title">اطلاعات شرکتی</h3>
        <div class="card-options">
            <a href="javascript:void(0)" class="card-options-collapse"
               data-bs-toggle="card-collapse"><i class="fal fa-chevron-up"></i></a>
        </div>
    </div>
    <div class="card-body">

        <x-admin.input identify="mobile_company" title="شماره همراه شرکتی" />

        <x-admin.select-enum identify="work_location"
                             title="محل انجام کار"
                             :with-option="false"
                             :enum-class="\App\Enums\Database\Admin\WorkLocation::class"/>

        <x-admin.select-enum identify="type_insurance"
                             title="نوع بیمه"
                             :with-option="false"
                             :enum-class="\App\Enums\Database\Admin\TypeInsurance::class"/>

        <x-admin.input identify="promissory" title="سفته"/>

        <x-admin.checkbox identify="has_contract" description="قرارداد دارد؟"/>

        <x-admin.input identify="number_company" title="شماره داخلی"/>

        <x-admin.input identify="start_cooperation" title="شروع همکاری"/>

        <x-admin.input identify="start_last_contract" title="تاریخ شروع آخرین قرارداد"/>

        <x-admin.input identify="end_last_contract" title="تاریج پایان آخرین قرارداد"/>

        <x-admin.textarea identify="description" title="توضیحات"/>

        <x-admin.checkbox identify="has_access" description="بلاک شود"/>

        <x-admin.button-submit/>
    </div>
</div>