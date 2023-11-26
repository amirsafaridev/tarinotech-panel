<?php

/*
|--------------------------------------------------------------------------
| Authentication Language Lines
|--------------------------------------------------------------------------
|
| The following language lines are used during authentication for various
| messages that we need to display to the user. You are free to modify
| these language lines according to your application's requirements.
|
*/

return [

    'auth' => [
        'login' => [
            'login' => 'ورود به پنل',
            'title' => 'پورتال تارینوتک - ورود',
            'email' => 'پست الکترونیکی',
            'email_placeholder' => 'پست الکترونیکی را وارد کنید',
            'password' => 'گذرواژه',
            'password_placeholder' => 'گذرواژه را وارد کنید',
            'captcha' => 'کد امنیتی',
            'captcha_placeholder' => 'کد امنیتی را وارد کنید',
            'remember' => 'مرابه خاطر بسپار',
            'forget' => 'گذرواژه را فراموش کرده ام',
            'submit' => 'ورود به پورتال',
        ],
        'reset' => [
            'reset' => 'بازنشانی گذرواژه',
            'title' => 'پورتال تارینوتک - بازنشانی گذرواژه',
            'submit' => 'بازنشانی گذرواژه',
            'captcha' => 'کد امنیتی',
            'captcha_placeholder' => 'کد امنیتی را وارد کنید',
            'password' => 'گذرواژه',
            'password_placeholder' => 'گذرواژه را وارد کنید',
            'password_rep' => 'تکرار گذرواژه',
            'password_rep_placeholder' => 'تکرار گذرواژه را وارد کنید',
            'code' => 'کد بازنشانی',
            'code_placeholder' => 'کد بازنشانی را وارد کنید',
            'otp' => [
                'invalidate' => 'کد ارسالی صحیح نیست یا منقضی شده است',
            ],
            'error' => 'خطایی رخ داده است بعدا تلاش کنید!',
        ],
        'forget' => [
            'title' => 'پورتال تارینوتک - فراموشی گذرواژه',
            'forget' => 'بازیابی گذرواژه',
            'identify' => 'پست الکترونیکی / شماره همراه',
            'captcha' => 'کد امنیتی',
            'captcha_placeholder' => 'کد امنیتی را وارد کنید',
            'send' => 'ارسال کد بازیابی',
            'user_notfound' => 'کاربری با اطلاعات ارسال شده یافت نشد',
            'otp' => [
                'already_sent' => 'کد قبلا ارسال شده لطفا کمی صبر کنید',
                'sent' => 'کد بازیابی گذرواژه برای شما ارسال شد',
            ],
            'error' => 'خطا در ارسال کد بازیابی لطفا بعدا تلاش کنید',
        ],
    ],

    'update' => 'به روز رسانی',
    'delete' => 'حذف',
    'create' => 'ایجاد',
    'edit' => 'ویرایش',
    'list' => 'لیست',
    'success_store' => 'با موفقیت ایجاد شد.',
    'success_no_change' => 'تغییری ایجاد نشد.',
    'success_update' => 'با موفقیت به روز شد.',
    'success_delete' => 'با موفقیت حذف شد.',
    'error_delete' => 'خطا در حذف اطلاعات',
    'error_exception' => 'خطایی رخ داده است!',
    'error_update' => 'خطا در ویرایش اطلاعات',
    'dashboard' => [
        'title' => 'داشبورد',
        'main' => 'ماژول ها',
        'total_user' => 'تعداد کل کاربران',
        'total_word' => 'تعداد کل کلمات',
        'total_contact' => 'تعداد تماس ها',
        'total_queue_word' => 'کلمات در انتظار',
        'show_link' => 'نمایش',
        'total_admin' => 'تعداد پرسنل',
    ],
    'action' => [
        'edit' => 'ویرایش',
        'manage' => 'مدیریت',
        'info' => 'جزئیات',
        'show' => 'نمایش',
        'change_password' => 'تغییر گذر واژه',
    ],
    'setting' => [
        'title' => 'تنظیمات',
    ],

    'admin' => [
        'index' => 'پرسنل',
        'title' => 'پرسنل',
        'edit' => 'ویرایش فرد',
        'create' => 'ایجاد فرد',
        'show' => 'نمایش فرد',
        'not_login' => 'بدون ورود',
        'edit_password' => 'تغییر گذرواژه',
    ],

    'goal-group' => [
        'index' => 'اهداف گروهی',
        'title' => 'اهداف گروهی',
    ],

    'report-goal' => [
        'index' => 'گزارش اهداف فروش',
        'title' => 'گزارش اهداف فروش',
    ],
    'admin_goal' => [
        'index' => 'هدف های فروش',
        'title' => 'هدف های فروش',
        'edit' => 'ویرایش فرد',
        'create' => 'ایجاد فرد',
        'show' => 'نمایش فرد',
        'not_login' => 'بدون ورود',
        'edit_password' => 'تغییر گذرواژه',
    ],
    'role' => [
        'index' => 'سطح دسترسی',
        'title' => 'سطح دسترسی',
        'edit' => 'ویرایش دسترسی',
        'create' => 'ایجاد دسترسی',
        'show' => 'نمایش دسترسی',
    ],
    'permission' => [
        'sync' => 'همگام سازی',
        'sync-success' => 'همگام سازی با موفقیت انجام شد.',
    ],
    'profile' => [
        'index' => 'پروفایل',
        'edit' => 'ویرایش پروفایل',
        'password-change' => 'تغییر گذرواژه',
        'sign-out' => 'خروج',
    ],
];
