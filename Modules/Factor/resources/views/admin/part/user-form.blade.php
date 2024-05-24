<div class="card">
    <div class="card-header">
        <h3 class="card-title">کارفرما</h3>
        <div class="card-options">
            <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse">
                <i class="fal fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <x-admin.input-phone identify="user_mobile"
                                     title="شماره همراه"/>
            </div>
            <div class="col-12 col-md-6">
                <x-admin.input identify="user_first_name"
                               title="نام"/>
            </div>
            <div class="col-12 col-md-6">
                <x-admin.input identify="user_last_name"
                               title="نام خانوادگی"/>
            </div>
        </div>

        <x-admin.select-enum identify="user_person_type"
                             title="نوع شخص"
                             :enum-class="\Modules\User\app\Enums\PersonType::class"/>

        <x-admin.checkbox identify="user_official_bill" description="درخواست فاکتور رسمی"  />
    </div>
</div>