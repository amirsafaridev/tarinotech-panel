<div class="row">

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.input
                identify="admin"
                title="کارشناس فروش"
        />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.select-model
                identify="type"
                title="نوع"
                key="id"
                value="path"
                :items="$types"
            />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.select-model
                identify="status"
                title="وضعیت"
                key="id"
                value="path"
                :items="$statuses"
                />
    </div>

    @php
        $signItems=[
            \Modules\Contract\app\Enums\SignableStatus::Pending=>'امضاء نشده',
            \Modules\Contract\app\Enums\SignableStatus::Signed=>'امضاء شده',
        ];
    @endphp

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.select-simple
                identify="is_signed"
                title="وضعیت امضاء"
                :items="$signItems"
                />
    </div>

    @php
        $signItems=[
            \Modules\Contract\app\Enums\UserSignableStatus::Pending=>'امضاء نشده',
            \Modules\Contract\app\Enums\UserSignableStatus::Accepted=>'امضاء شده',
        ];
    @endphp

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.select-simple
                identify="is_signed_user"
                title="وضعیت امضاء کارفرما"
                :items="$signItems"
        />
    </div>

    <div class="col-12 col-md-3 col-xl-2 d-flex align-items-end">
        <button type="button" class="btn btn-primary mb-4 datatable-apply">اعمال</button>
    </div>

</div>