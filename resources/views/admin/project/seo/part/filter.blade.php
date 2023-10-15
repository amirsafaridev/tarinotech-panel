<form class="row mb-4" action="{{ route('admin.project.seo.index') }}">
    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.input
                identify="id"
                title="شناسه"
                :old="request('id')"/>
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.input
                identify="domain"
                title="دامنه"
                :old="request('domain')"/>
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.input
                identify="user"
                title="کارفرما"
                :old="request('user')"/>
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.select-model identify="status_id[]"
                              title="وضعیت پروژه"
                              key="id"
                              value="title"
                              :items="$statuses"
                              :multiple="true"
                              :old="request('status_id')"/>
    </div>


    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.select-simple identify="sort"
                               title="وضعیت پروژه"
                               :items="$sortItems"
                               :old="request('sort')"/>
    </div>

    <div class="col-12 col-md-3 col-xl-2 d-flex align-items-end justify-content-end">
        <button class="btn btn-block btn-primary mb-4">اعمال</button>
    </div>

</form>