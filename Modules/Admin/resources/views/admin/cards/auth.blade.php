@php
    $editMode = isset($admin);
    $email = $admin->email ?? '';
    $mobile = $admin->mobile ?? '';
    $isBlock = $admin->is_block ?? false;
@endphp
<div class="card">
    <div class="card-header">
        <h3 class="card-title">اطلاعات ورود</h3>
        <div class="card-options">
            <a href="javascript:void(0)" class="card-options-collapse"
               data-bs-toggle="card-collapse"><i class="fal fa-chevron-up"></i></a>
        </div>
    </div>
    <div class="card-body">

        <x-admin.input identify="email" title="پست الکترونیکی" :old="$email"/>

        <x-admin.input identify="mobile" title="شماره همراه" :old="$mobile"/>

        <x-admin.select-model multiple="multiple"
                              identify="role[]"
                              title="سطح دسترسی"
                              :has-choice-option="false"
                              :items="$roles"
                              key="id"
                              value="name"/>

        @if(!$editMode)
            <div class="alert alert-success">
                <p>گذرواژه برای پست الکترونیکی و شماره همراه ارسال خواهد شد.</p>
            </div>
        @endif

        @if($editMode)
            <x-admin.checkbox identify="is_block"
                              description="قطع دسترسی"
                              :old="$isBlock"/>
        @endif
    </div>
</div>