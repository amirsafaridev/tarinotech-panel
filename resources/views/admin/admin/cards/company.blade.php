@php
    $editMode = isset($admin);
    $id = $admin->id ?? null;
    $mobileCompany = $admin->mobile_company ?? null;
    $workLocation = $admin->work_location ?? null;
    $typeInsurance = $admin->type_insurance ?? null;
    $promissory = $admin->promissory ?? null;
    $hasContract = $admin->has_contract ?? false;
    $hasAccess = $admin->has_access ?? false;
    $numberCompany = $admin->number_company ?? null;
    $description = $admin->description ?? null;

    $startCooperation = isset($admin->start_cooperation) ? $admin->start_cooperation->toJalali()->format('Y/m/d') : null;
    $startLastContract =  isset($admin->start_last_contract) ? $admin->start_last_contract->toJalali()->format('Y/m/d') : null;
    $endLastContract =  isset($admin->end_last_contract) ? $admin->end_last_contract->toJalali()->format('Y/m/d') : null;

@endphp

<div class="card">
    <div class="card-header">
        <h3 class="card-title">اطلاعات شرکتی</h3>
        <div class="card-options">
            <a href="javascript:void(0)" class="card-options-collapse"
               data-bs-toggle="card-collapse"><i class="fal fa-chevron-up"></i></a>
        </div>
    </div>
    <div class="card-body">

        <x-admin.input identify="mobile_company"
                       title="شماره همراه شرکتی"
                       :old="$mobileCompany"/>

        <x-admin.select-enum identify="work_location"
                             title="محل انجام کار"
                             :with-option="false"
                             :enum-class="\App\Enums\Database\Admin\WorkLocation::class"
                             :old="$workLocation"/>

        <x-admin.select-enum identify="type_insurance"
                             title="نوع بیمه"
                             :with-option="false"
                             :enum-class="\App\Enums\Database\Admin\TypeInsurance::class"
                             :old="$typeInsurance"/>

        <x-admin.input identify="promissory"
                       title="سفته"
                       :old="$promissory"/>

        <x-admin.checkbox identify="has_contract"
                          description="قرارداد دارد؟"
                          :old="$hasContract"/>

        <x-admin.input identify="number_company"
                       title="شماره داخلی"
                       :old="$numberCompany"/>

        <x-admin.input identify="start_cooperation"
                       title="شروع همکاری"
                       :is-date-picker="true"
                       :old="$startCooperation"/>

        <x-admin.input identify="start_last_contract"
                       title="تاریخ شروع آخرین قرارداد"
                       :is-date-picker="true"
                       :old="$startLastContract"/>

        <x-admin.input identify="end_last_contract"
                       title="تاریج پایان آخرین قرارداد"
                       :is-date-picker="true"
                       :old="$endLastContract"
        />

        <x-admin.textarea identify="description"
                          title="توضیحات"
                          :old="$description"/>

        <x-admin.checkbox identify="has_access"
                          description="بلاک شود"
                          :old="$hasAccess"/>

        @if($editMode)
            <x-admin.input identify="id"
                           type="hidden"
                           :old="$id"/>
            <x-admin.button-submit title="{{ trans('panel.update') }}"/>

            <x-admin.button-delete/>
        @else
            <x-admin.button-submit/>
        @endif

    </div>
</div>