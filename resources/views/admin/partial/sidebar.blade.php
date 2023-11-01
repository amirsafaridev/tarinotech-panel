<div class="sticky">
    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
    <div class="app-sidebar">
        <div class="side-header">
            <a class="header-brand1" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('res-admin/assets/images/brand/logo.png') }}" class="header-brand-img desktop-logo" alt="Worder">
                <img src="{{ asset('res-admin/assets/images/brand/logo-toggle.png') }}" class="header-brand-img toggle-logo" alt="Worder">
                <img src="{{ asset('res-admin/assets/images/brand/logo-dark.png') }}" class="header-brand-img light-logo1" alt="Worder">
                <img src="{{ asset('res-admin/assets/images/brand/logo-mini-dark.png') }}" class="header-brand-img light-logo" alt="Worder">
            </a>
        </div>
        <div class="main-sidemenu">
            <div class="slide-left disabled" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"/></svg>
            </div>

            <ul class="side-menu">
                <li class="sub-category">
                    <h3>داشبورد</h3>
                </li>
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="{{ route('admin.dashboard') }}">
                        <i class="side-menu__icon fal fa-chart-bar"></i>
                        <span class="side-menu__label">{{ trans('panel.dashboard.title') }}</span>
                    </a>
                </li>
                <li class="sub-category">
                    <h3>مدیریت پرسنل</h3>
                </li>
                <li class="slide can-expand">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)" id="navAdmin">
                        <i class="side-menu__icon fal fa-user"></i>
                        <span class="side-menu__label">{{ trans('panel.admin.index') }}</span><i class="angle fal fa-angle-left"></i>
                    </a>

                    <ul class="slide-menu">
                        <li><a href="{{ route('admin.admin.index') }}" class="slide-item">{{ trans('panel.admin.index') }}</a></li>
                        <li><a href="{{ route('admin.admin.create') }}" class="slide-item">{{ trans('panel.create') }}</a></li>
                        <li><a href="{{ route('admin.admin.group-goal') }}" class="slide-item">{{ trans('panel.goal-group.index') }}</a></li>
                        <li><a href="{{ route('admin.report.goal') }}" class="slide-item">گزارش هدف های فردی</a></li>
                        <li><a href="{{ route('admin.report.goal-group') }}" class="slide-item">گزارش هدف های گروهی</a></li>
                    </ul>
                </li>

                <li class="slide can-expand">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)" id="navRole">
                        <i class="side-menu__icon fal fa-lock"></i>
                        <span class="side-menu__label">{{ trans('panel.role.index') }}</span><i class="angle fal fa-angle-left"></i>
                    </a>

                    <ul class="slide-menu">
                        <li><a href="{{ route('admin.permission.sync') }}" class="slide-item">{{ trans('panel.permission.sync') }}</a></li>
                        <li><a href="{{ route('admin.role.index') }}" class="slide-item">{{ trans('panel.list') }}</a></li>
                        <li><a href="{{ route('admin.role.create') }}" class="slide-item">{{ trans('panel.create') }}</a></li>
                    </ul>
                </li>

                <li class="sub-category">
                    <h3>مدیریت مشتری ها</h3>
                </li>

                <li class="slide can-expand">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)" id="navUser">
                        <i class="side-menu__icon fal fa-user"></i>
                        <span class="side-menu__label">مشتری ها</span><i class="angle fal fa-angle-left"></i>
                    </a>

                    <ul class="slide-menu">
                        <li><a href="{{ route('admin.user.index') }}" class="slide-item">لیست</a></li>
                        <li><a href="{{ route('admin.user.create') }}" class="slide-item">ایجاد</a></li>
                        <li><a href="{{ route('admin.presenter.index') }}" class="slide-item">نمایندگان</a></li>
                    </ul>
                </li>

                <li class="sub-category">
                    <h3>مدیریت پروژه ها</h3>
                </li>

                <li class="slide can-expand">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)" id="navProject">
                        <i class="side-menu__icon fal fa-box"></i>
                        <span class="side-menu__label">پروژه ها</span><i class="angle fal fa-angle-left"></i>
                    </a>

                    <ul class="slide-menu">
                        <li><a href="{{ route('admin.project.index') }}" class="slide-item">لیست پروژه ها</a></li>
                        <li><a href="{{ route('admin.project.web.index') }}" class="slide-item">وب سایت</a></li>
                        <li><a href="{{ route('admin.project.seo.index') }}" class="slide-item">سئو</a></li>
                        <li><a href="{{ route('admin.project.ads.index') }}" class="slide-item">ادوردز</a></li>
                    </ul>
                </li>

                <li class="sub-category">
                    <h3>امور مالی</h3>
                </li>

                <li class="slide can-expand">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)" id="navFactor">
                        <i class="side-menu__icon fal fa-credit-card"></i>
                        <span class="side-menu__label">فاکتور ها</span><i class="angle fal fa-angle-left"></i>
                    </a>

                    <ul class="slide-menu">
                        <li><a href="{{ route('admin.factor.index') }}" class="slide-item">لیست</a></li>
                        <li><a href="{{ route('admin.factor.create') }}" class="slide-item">ایجاد</a></li>
                    </ul>
                </li>

                <li class="sub-category">
                    <h3>محتوا</h3>
                </li>

                <li class="slide can-expand">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)" id="navBlog">
                        <i class="side-menu__icon fal fa-page"></i>
                        <span class="side-menu__label">بلاگ</span><i class="angle fal fa-angle-left"></i>
                    </a>

                    <ul class="slide-menu">
                        <li><a href="{{ route('admin.blog.index') }}" class="slide-item">لیست</a></li>
                        <li><a href="{{ route('admin.blog.create') }}" class="slide-item">ایجاد</a></li>
                        <li><a href="{{ route('admin.blog.category.index') }}" class="slide-item">دسته بندی ها</a></li>
                    </ul>
                </li>

                <li class="sub-category">
                    <h3>تنظیمات پلتفرم</h3>
                </li>

                <li class="slide can-expand">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)" id="navProfile">
                        <i class="side-menu__icon fal fa-cog"></i>
                        <span class="side-menu__label">تنظیمات</span><i class="angle fal fa-angle-left"></i>
                    </a>

                    <ul class="slide-menu">
                        <li><a href="{{ route('admin.setting.index') }}" class="slide-item">تنظیمات پایه</a></li>
                        <li><a href="{{ route('admin.project.type.index') }}" class="slide-item">انواع پروژه ها</a></li>
                        <li><a href="{{ route('admin.project.status.index') }}" class="slide-item">وضعیت پروژه ها</a></li>
                        <li><a href="{{ route('admin.package.index') }}" class="slide-item">پکیج ها</a></li>
                        <li><a href="{{ route('admin.facility.index') }}" class="slide-item">امکانات جانبی</a></li>
                        <li><a href="{{ route('admin.transaction-category.index') }}" class="slide-item">انواع واریزی</a></li>
                        <li><a href="{{ route('admin.sample-message.index') }}" class="slide-item">پیام های آماده</a></li>
                        <li><a href="{{ route('admin.auto-message.index') }}" class="slide-item">پیام های خودکار</a></li>
                        <li><a href="{{ route('admin.free-day.index') }}" class="slide-item">تقویم تعطیلات</a></li>
                    </ul>
                </li>

                <li class="sub-category">
                    <h3>پروفایل من</h3>
                </li>

                <li class="slide can-expand">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)" id="navProfile">
                        <i class="side-menu__icon fal fa-user-edit"></i>
                        <span class="side-menu__label">{{ trans('panel.profile.index') }}</span><i class="angle fal fa-angle-left"></i>
                    </a>

                    <ul class="slide-menu">
                        <li><a href="{{ route('admin.profile.index') }}" class="slide-item">{{ trans('panel.profile.edit') }}</a></li>
                        <li><a href="{{ route('admin.profile.password') }}" class="slide-item">{{ trans('panel.profile.password-change') }}</a></li>
                        <li><a href="{{ route('admin.profile.logout') }}" class="slide-item">{{ trans('panel.profile.sign-out') }}</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
