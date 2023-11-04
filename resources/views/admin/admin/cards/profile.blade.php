<div class="card">
    <div class="card-header">
        <h3 class="card-title">اطلاعات پروفایل</h3>
        <div class="card-options">
            <a href="javascript:void(0)" class="card-options-collapse"
               data-bs-toggle="card-collapse"><i class="fal fa-chevron-up"></i></a>
        </div>
    </div>
    <div class="card-body">
        <x-admin.input identify="avatar" title="تصویر" type="file"/>

        <x-admin.input identify="first_name" title="نام"/>

        <x-admin.input identify="last_name" title="نام خانوادگی"/>

        <x-admin.input identify="tel" title="شماره تماس"/>

        <x-admin.input identify="postal_code" title="کد پستی"/>

        <x-admin.input identify="national_code" title="کد ملی"/>

        <x-admin.input identify="shaba_number" title="شبا"/>

        <x-admin.input identify="cart_number" title="شماره کارت"/>

        <x-admin.input identify="dob" title="تاریخ تولد"/>

        <x-admin.textarea identify="resume" title="رزومه"/>

    </div>
</div>