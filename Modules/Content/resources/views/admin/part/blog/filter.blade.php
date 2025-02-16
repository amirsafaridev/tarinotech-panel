<div class="row mb-4">

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.select-model
                identify="category_id"
                title="دسته بندی"
                key="id"
                value="title"
                :is-small="true"
                :items="$categories"
                :old="request('title')"/>
    </div>

</div>