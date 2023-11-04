<div class="card">
    <div class="card-header">
        <h3 class="card-title">اطلاعات ورود</h3>
        <div class="card-options">
            <a href="javascript:void(0)" class="card-options-collapse"
               data-bs-toggle="card-collapse"><i class="fal fa-chevron-up"></i></a>
        </div>
    </div>
    <div class="card-body">

        <x-admin.input identify="email" title="پست الکترونیکی"/>

        <x-admin.input identify="mobile" title="شماره همراه"/>

        <x-admin.select-model multiple="multiple"
                              identify="role[]"
                              title="سطح دسترسی"
                              :has-choice-option="false"
                              :items="$roles"
                              key="id"
                              value="name"/>

        <div class="alert alert-success">
            <p>گذرواژه برای پست الکترونیکی و شماره همراه ارسال خواهد شد.</p>
        </div>

    </div>
</div>