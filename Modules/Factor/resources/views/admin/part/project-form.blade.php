<div class="card">
    <div class="card-header">
        <h3 class="card-title">پروژه</h3>
        <div class="card-options">
            <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse">
                <i class="fal fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <div class="card-body">
        <x-admin.input identify="project_title" title="عنوان پروژه"/>

        <div class="row">
            <div class="col-12 col-md-6">
                <x-admin.select-model
                        identify="project_type_id"
                        title="نوع پروژه"
                        :items="$types"
                        :has-choice-option="false"
                        key="id"
                        value="title"/>
            </div>

            <div class="col-12 col-md-6">
                <x-admin.select-simple identify="project_status_id"
                                       title="وضعیت پروژه"
                />
            </div>
        </div>

        <x-admin.select-model
                identify="project_package_id"
                title="پکیج انتخابی"
                :items="$packages"
                key="id"
                value="title"/>

        <x-admin.input identify="project_price" title="قیمت (ریال)"/>

    </div>
</div>