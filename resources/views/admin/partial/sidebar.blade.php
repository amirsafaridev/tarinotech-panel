<div class="sticky">
    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
    <div class="app-sidebar">
        <div class="side-header">
            <a class="header-brand1" href="{{ route('admin.dashboard.index') }}">
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
                    <a class="side-menu__item" data-bs-toggle="slide" href="{{ route('admin.dashboard.index') }}">
                        <i class="side-menu__icon fal fa-chart-bar"></i>
                        <span class="side-menu__label">{{ trans('panel.dashboard.title') }}</span>
                    </a>
                </li>

                @canany(['ADMIN_ADMIN_INDEX','ADMIN_ADMIN_CREATE','ADMIN_ADMIN_JOB_TITLE_INDEX','ADMIN_ADMIN_GROUP_GOAL','ADMIN_REPORT_GOAL','ADMIN_REPORT_GOAL_GROUP'])
                    <li class="sub-category">
                        <h3>مدیریت پرسنل</h3>
                    </li>
                    <li class="slide can-expand">
                        <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)" id="navAdmin">
                            <i class="side-menu__icon fal fa-user"></i>
                            <span class="side-menu__label">{{ trans('panel.admin.index') }}</span><i class="angle fal fa-angle-left"></i>
                        </a>
                        <ul class="slide-menu">
                            @can('ADMIN_ADMIN_INDEX')
                                <li><a href="{{ route('admin.admin.index') }}" class="slide-item">پرسنل</a></li>
                            @endcan

                            @can('ADMIN_ADMIN_CREATE')
                                <li><a href="{{ route('admin.admin.create') }}" class="slide-item">ایجاد</a></li>
                            @endcan

                            @can('ADMIN_ADMIN_JOB_TITLE_INDEX')
                                <li><a href="{{ route('admin.admin.job-title.index') }}" class="slide-item">عنوان شغلی</a></li>
                            @endcan

                            @can('ADMIN_ADMIN_GROUP_GOAL')
                                <li><a href="{{ route('admin.admin.group-goal') }}" class="slide-item">اهداف گروهی</a></li>
                            @endcan

                            @can('ADMIN_REPORT_GOAL')
                                <li><a href="{{ route('admin.report.goal') }}" class="slide-item">گزارش هدف های فردی</a></li>
                            @endcan

                            @can('ADMIN_REPORT_GOAL_GROUP')
                                <li><a href="{{ route('admin.report.goal-group') }}" class="slide-item">گزارش هدف های گروهی</a></li>
                            @endcan
                        </ul>
                    </li>
                @endcanany


                @canany(['ADMIN_PERMISSION_INDEX','ADMIN_ROLE_INDEX','ADMIN_ROLE_CREATE'])
                    <li class="slide can-expand">
                        <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)" id="navRole">
                            <i class="side-menu__icon fal fa-lock"></i>
                            <span class="side-menu__label">{{ trans('panel.role.index') }}</span><i class="angle fal fa-angle-left"></i>
                        </a>

                        <ul class="slide-menu">
                            @can('ADMIN_PERMISSION_INDEX')
                                <li><a href="{{ route('admin.permission.index') }}" class="slide-item">پرمیشن ها</a></li>
                            @endcan

                            @can('ADMIN_ROLE_INDEX')
                                <li><a href="{{ route('admin.role.index') }}" class="slide-item">{{ trans('panel.list') }}</a></li>
                            @endcan

                            @can('ADMIN_ROLE_CREATE')
                                <li><a href="{{ route('admin.role.create') }}" class="slide-item">{{ trans('panel.create') }}</a></li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                <li class="sub-category">
                    <h3>مدیریت مشتری ها</h3>
                </li>

                @canany(['ADMIN_USER_INDEX','ADMIN_USER_CREATE','ADMIN_PRESENTER_INDEX','ADMIN_KNOWLEDGE_WAY_INDEX'])
                    <li class="slide can-expand">
                        <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)" id="navUser">
                            <i class="side-menu__icon fal fa-user"></i>
                            <span class="side-menu__label">مشتری ها</span><i class="angle fal fa-angle-left"></i>
                        </a>
                        <ul class="slide-menu">
                            @can('ADMIN_USER_INDEX')
                                <li><a href="{{ route('admin.user.index') }}" class="slide-item">لیست</a></li>
                            @endcan

                            @can('ADMIN_USER_CREATE')
                                <li><a href="{{ route('admin.user.create') }}" class="slide-item">ایجاد</a></li>
                            @endcan

                            @can('ADMIN_PRESENTER_INDEX')
                                <li><a href="{{ route('admin.presenter.index') }}" class="slide-item">نمایندگان</a></li>
                            @endcan

                            @can('ADMIN_KNOWLEDGE_WAY_INDEX')
                                <li><a href="{{ route('admin.knowledge-way.index') }}" class="slide-item">راه های آشنایی</a></li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                <li class="sub-category">
                    <h3>مدیریت پروژه ها</h3>
                </li>

                <li class="slide can-expand">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)" id="navProject">
                        <i class="side-menu__icon fal fa-box"></i>
                        <span class="side-menu__label">پروژه ها</span><i class="angle fal fa-angle-left"></i>
                    </a>

                    <ul class="slide-menu">
                        @can('ADMIN_PROJECT_INDEX')
                            <li><a href="{{ route('admin.project.index') }}" class="slide-item">لیست پروژه ها</a></li>
                        @endcan

                        @can('ADMIN_PROJECT_RENEWAL_INDEX')
                            <li><a href="{{ route('admin.project.renewal.index') }}" class="slide-item">لیست تمدید ها</a></li>
                        @endcan

                        @can('ADMIN_PROJECT_WEB_INDEX')
                            <li><a href="{{ route('admin.project.web.index') }}" class="slide-item">وب سایت</a></li>
                        @endcan

                        @can('ADMIN_PROJECT_SEO_INDEX')
                            <li><a href="{{ route('admin.project.seo.index') }}" class="slide-item">سئو</a></li>
                        @endcan

                        @can('ADMIN_PROJECT_ADS_INDEX')
                            <li><a href="{{ route('admin.project.ads.index') }}" class="slide-item">ادوردز</a></li>
                        @endcan

                        @can('ADMIN_PROJECT_FACILITY_INDEX')
                            <li><a href="{{ route('admin.project.facility.index') }}" class="slide-item">امکانات جانبی</a></li>
                        @endcan

                        @can('ADMIN_PROJECT_OPTION_INDEX')
                            <li><a href="{{ route('admin.project.option.index') }}" class="slide-item">امکانات</a></li>
                        @endcan

                        @can('ADMIN_PROJECT_TYPE_INDEX')
                            <li><a href="{{ route('admin.project.type.index') }}" class="slide-item">انواع پروژه ها</a></li>
                        @endcan

                        @can('ADMIN_PROJECT_STATUS_INDEX')
                            <li><a href="{{ route('admin.project.status.index') }}" class="slide-item">وضعیت پروژه ها</a></li>
                        @endcan

                        @can('ADMIN_PACKAGE_INDEX')
                            <li><a href="{{ route('admin.package.index') }}" class="slide-item">پکیج ها</a></li>
                        @endcan

                        @can('ADMIN_PROJECT_BUSINESS_DOMAIN_INDEX')
                            <li><a href="{{ route('admin.project.business_domain.index') }}" class="slide-item">زمینه های کاری</a></li>
                        @endcan
                    </ul>
                </li>

                <li class="sub-category">
                    <h3>پشتیبانی ها</h3>
                </li>

                <li class="slide can-expand">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)" id="navSupport">
                        <i class="side-menu__icon fal fa-headset"></i>
                        <span class="side-menu__label">پشتیبانی ها</span><i class="angle fal fa-angle-left"></i>
                    </a>

                    <ul class="slide-menu">
                        @can('ADMIN_SUPPORT_INDEX')
                            <li><a href="{{ route('admin.support.index') }}" class="slide-item">لیست</a></li>
                        @endcan

                        @can('ADMIN_SUPPORT_GROUP_CREATE')
                            <li><a href="{{ route('admin.support.group.create') }}" class="slide-item">ایجاد گروه</a></li>
                        @endcan

                        @can('ADMIN_SUPPORT_NOTIFY_INDEX')
                            <li><a href="{{ route('admin.support.notify.index') }}" class="slide-item">اطلاعیه ها</a></li>
                        @endcan

                        @can('ADMIN_SUPPORT_SAMPLE_MESSAGE_INDEX')
                            <li><a href="{{ route('admin.support.sample-message.index') }}" class="slide-item">پیام های آماده</a></li>
                        @endcan
                    </ul>
                </li>

                <li class="slide can-expand">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)" id="navSupport">
                        <i class="side-menu__icon fal fa-comment"></i>
                        <span class="side-menu__label">تیکت ها</span><i class="angle fal fa-angle-left"></i>
                    </a>

                    <ul class="slide-menu">
                        @can('ADMIN_TICKET_INDEX')
                            <li><a href="{{ route('admin.ticket.index') }}" class="slide-item">لیست</a></li>
                        @endcan
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
                        @can('ADMIN_FACTOR_INDEX')
                            <li><a href="{{ route('admin.factor.index') }}" class="slide-item">لیست</a></li>
                        @endcan

                        @can('ADMIN_FACTOR_CREATE')
                            <li><a href="{{ route('admin.factor.create') }}" class="slide-item">ایجاد</a></li>
                        @endcan

                        @can('ADMIN_FACTOR_CATEGORY_INDEX')
                            <li><a href="{{ route('admin.factor.category.index') }}" class="slide-item">انواع واریزی</a></li>
                        @endcan
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
                        @can('ADMIN_BLOG_INDEX')
                            <li><a href="{{ route('admin.blog.index') }}" class="slide-item">لیست</a></li>
                        @endcan

                        @can('ADMIN_BLOG_CREATE')
                            <li><a href="{{ route('admin.blog.create') }}" class="slide-item">ایجاد</a></li>
                        @endcan

                        @can('ADMIN_BLOG_CATEGORY_INDEX')
                            <li><a href="{{ route('admin.blog.category.index') }}" class="slide-item">دسته بندی ها</a></li>
                        @endcan
                    </ul>
                </li>

                <li class="sub-category">
                    <h3>لاگ ها</h3>
                </li>

                <li class="slide can-expand">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)" id="navBlog">
                        <i class="side-menu__icon fal fa-bug"></i>
                        <span class="side-menu__label">لاگ ها</span><i class="angle fal fa-angle-left"></i>
                    </a>

                    <ul class="slide-menu">
                        @can('ADMIN_LOG_INDEX')
                            <li><a href="{{ route('admin.log.index') }}" class="slide-item">لیست</a></li>
                        @endcan

                        @can('ADMIN_LOGIN_INDEX')
                            <li><a href="{{ route('admin.login.index') }}" class="slide-item">ورود ها</a></li>
                        @endcan
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
                        @can('ADMIN_SETTING_INDEX')
                            <li><a href="{{ route('admin.setting.index') }}" class="slide-item">تنظیمات پایه</a></li>
                        @endcan

                        @can('ADMIN_AUTO_MESSAGE_INDEX')
                            <li><a href="{{ route('admin.auto-message.index') }}" class="slide-item">پیام های خودکار</a></li>
                        @endcan

                        @can('ADMIN_FREE_DAY_INDEX')
                            <li><a href="{{ route('admin.free-day.index') }}" class="slide-item">تقویم تعطیلات</a></li>
                        @endcan
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
                        <li><a href="{{ route('admin.admin.profile.index') }}" class="slide-item">پروفایل</a></li>
                        <li><a href="{{ route('admin.admin.profile.password') }}" class="slide-item">تغییر گذر واژه</a></li>
                        <li><a href="{{ route('admin.admin.profile.logout') }}" class="slide-item">خروج</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
