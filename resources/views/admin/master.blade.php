<!doctype html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Tarinotech Platform">
    <meta name="author" content="Ali Mousavi">
    <meta name="keywords" content="Tarinotech Platform">
    <meta name="robots" content="noindex">
    <meta name="robots" content="nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('favicon')

    <title>@yield('title','تارینوتک - پورتال')</title>


    <link href="{{ asset('res-admin/assets/font-awesome/css/light.css') }}" rel="stylesheet" />
    <link href="{{ asset('res-admin/assets/font-awesome/css/solid.css') }}" rel="stylesheet" />

    @if(app()->isLocal())
        <link href="{{ asset('res-admin/assets/css/style.css?u=1') }}" rel="stylesheet" />
    @else
        <link href="{{ asset('res-admin/assets/css/style.min.css') }}" rel="stylesheet" />
    @endif

    <link href="{{ asset('res-admin/assets/css/custom.css') }}" rel="stylesheet" />

    @yield('head')

    @stack('styles')

    <link id="theme" rel="stylesheet" type="text/css" media="all" href="{{ asset('res-admin/assets/css/my-style.css') }}" />


</head>

<body class="app sidebar-mini rtl @if(app()->isLocal()) dark-mode @endif">

<div id="global-loader">
    <div class="u-loading">
        <div class="u-loading__symbol">
            <img src="{{ asset('res-admin/assets/images/loader.png') }}" class="loader-img" alt="{{ config('app.name') }}">
        </div>
    </div>
</div>

<div class="page">
    <div class="page-main">

        <div class="app-header header sticky">
            <div class="container-fluid main-container">
                <div class="d-flex">
                    <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar" href="javascript:void(0)">
                        <i class="fal fa-navicon"></i>
                    </a>
                    <a class="logo-horizontal d-none" href="{{ route('admin.dashboard.index') }}">
                        <img src="{{ asset('res-admin/assets/images/brand/logo.png') }}" class="header-brand-img desktop-logo" alt="logo">
                        <img src="{{ asset('res-admin/assets/images/brand/logo-dark.png') }}" class="header-brand-img light-logo1" alt="logo">
                    </a>

                    <div class="d-flex order-lg-2 ms-auto header-right-icons">
                        <div class="dropdown d-none">
                            <a href="javascript:void(0)" class="nav-link icon" data-bs-toggle="dropdown">
                                <i class="fal fa-search"></i>
                            </a>
                        </div>
                        <button class="navbar-toggler navresponsive-toggler d-lg-none ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon fe fe-more-vertical"></span>
                        </button>
                        <div class="navbar navbar-collapse responsive-navbar p-0">
                            <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                                <div class="d-flex order-lg-2">

                                    <div class="dropdown  d-flex">
                                        <a class="nav-link icon theme-layout nav-link-bg layout-setting">
                                            <span style="margin-top: 10px" class="dark-layout"><i class="fal fa-moon"></i></span>
                                            <span style="margin-top: 10px" class="light-layout"><i class="fal fa-sun"></i></span>
                                        </a>
                                    </div>

                                    <div class="dropdown d-flex profile-1">
                                        <a href="javascript:void(0)" data-bs-toggle="dropdown" class="nav-link leading-none d-flex">
                                            <img src="{{ auth()->user()->avatar ? asset(auth()->user()->avatar) : asset('uploads/admin/avatar.png') }}" alt="{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}" class="avatar  profile-user brround cover-image">
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            <div class="drop-heading">
                                                <div class="text-center">
                                                    <h5 class="text-dark mb-0 fs-14 fw-semibold">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h5>
                                                    <small class="text-muted">{{ auth()->user()->email }}</small>
                                                </div>
                                            </div>
                                            <div class="dropdown-divider m-0"></div>
                                            <a class="dropdown-item" href="{{ route('admin.admin.profile.index') }}">
                                                <i class="dropdown-icon fe fe-user"></i>
                                                <span>{{ trans('panel.profile.edit') }}</span>
                                            </a>
                                            <a class="dropdown-item" href="{{ route('admin.admin.profile.logout') }}">
                                                <i class="dropdown-icon fe fe-alert-circle"></i>
                                                <span>{{ trans('panel.profile.sign-out') }}</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.partial.sidebar')

        <div class="main-content app-content mt-0">
            <div class="side-app">

                <div class="main-container container-fluid">
                    @yield('content')
                </div>

            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="row align-items-center flex-row-reverse">
                <div class="col-md-12 col-sm-12 text-center">
                    <span>Copyright © {{ now()->year }}  - </span>
                    <span>تمامی حقوق این سایت مربوط به تارینوتک می باشد.</span>
                </div>
            </div>
        </div>
    </footer>
</div>

<a href="#top" id="back-to-top"><i class="fal fa-angle-up"></i></a>

<script src="{{ asset('res-admin/assets/js/jquery.min.js') }}"></script>

<script src="{{ asset('res-admin/assets/plugins/bootstrap/js/popper.min.js') }}"></script>
<script src="{{ asset('res-admin/assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>


<script src="{{ asset('res-admin/assets/plugins/sidemenu/sidemenu.js') }}"></script>

<script src="{{ asset('res-admin/assets/plugins/sidebar/sidebar.js') }}"></script>

<script src="{{ asset('res-admin/assets/plugins/p-scroll/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('res-admin/assets/plugins/p-scroll/pscroll.js') }}"></script>

<script src="{{ asset('res-admin/assets/js/sticky.js') }}"></script>

<script src="{{ asset('res-admin/assets/js/custom.js') }}"></script>

@yield('script')

@stack('scripts');

<script>
    $(document).ready(function () {
        activeParentUl('{{ url()->current() }}');
    })
    function activeParentUl(route) {
        setTimeout(function () {
            const currentLink = $(`div.main-sidemenu a[href="${route}"]`);
            const canExpand = currentLink.closest('li.can-expand');
            canExpand.addClass('is-expanded');
            currentLink.addClass('active')
        }, 200);
    }
</script>
</body>

</html>
