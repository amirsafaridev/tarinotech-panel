<div class="row">

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.select-model
                identify="job_title"
                title="سمت شغلی"
                key="id"
                value="title"
                :items="$jobTitles"
                />
    </div>

    <div class="col-12 col-md-3 col-xl-2 d-flex align-items-end">
        <button type="button" class="btn btn-primary mb-4 datatable-apply">اعمال</button>
    </div>

</div>