<form class="row mb-4" action="{{ route('admin.login.index') }}">


    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.input
                identify="user"
                title="کاربر"
                :old="request('user')"/>
    </div>



    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.select-simple identify="sort"
                               title="مرتب سازی"
                               :items="$sortItems"
                               :old="request('sort')"/>
    </div>

    <div class="col-12 col-md-3 col-xl-2 d-flex align-items-end justify-content-end">
        <button class="btn btn-block btn-primary mb-4">اعمال</button>
    </div>

</form>