@php
    $avatar = $admin->avatar ?? null;
    $firstName = $admin->first_name ?? null;
    $lastName = $admin->last_name ?? null;
    $tel = $admin->tel ?? null;
    $postalCode = $admin->postal_code ?? null;
    $nationalCode = $admin->national_code ?? null;
    $shabaNumber = $admin->shaba_number ?? null;
    $cartNumber = $admin->cart_number ?? null;
    $dob = $admin->dob?->toJalali()->format('Y/m/d');
    $resume = $admin->resume ?? null;
@endphp
<div class="card">
    <div class="card-header">
        <h3 class="card-title">اطلاعات پروفایل</h3>
        <div class="card-options">
            <a href="javascript:void(0)" class="card-options-collapse"
               data-bs-toggle="card-collapse"><i class="fal fa-chevron-up"></i></a>
        </div>
    </div>
    <div class="card-body">

        @if($avatar)
            <img class="admin-avatar" src="{{ asset($avatar) }}"
                 alt="{{ $firstName . ' ' . $lastName }}">
        @endif

        <x-admin.input identify="avatar" title="تصویر" type="file"/>

        <x-admin.input identify="first_name" title="نام" :old="$firstName"/>

        <x-admin.input identify="last_name" title="نام خانوادگی" :old="$lastName"/>

        <x-admin.input identify="tel" title="شماره تماس" :old="$tel"/>

        <x-admin.input identify="postal_code" title="کد پستی" :old="$postalCode"/>

        <x-admin.input identify="national_code" title="کد ملی" :old="$nationalCode"/>

        <x-admin.input identify="shaba_number" title="شبا" :old="$shabaNumber"/>

        <x-admin.input identify="cart_number" title="شماره کارت" :old="$cartNumber"/>

        <x-admin.input identify="dob" title="تاریخ تولد" :old="$dob"/>

        <x-admin.textarea identify="resume" title="رزومه" :old="$resume"/>

    </div>
</div>