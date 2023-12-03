<div class="row mb-4">

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.input
                identify="admin"
                title="کارشناس"
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


    <div class="col-12 col-md-3 col-xl-2 d-flex align-items-end">
        <button type="button" class="btn btn-primary mb-4 datatable-apply">اعمال</button>
    </div>

</div>