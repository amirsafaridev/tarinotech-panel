<!doctype html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Worder Platform">
    <meta name="author" content="Ali Mousavi">
    <meta name="keywords" content="Worder Platform">
    <meta name="robots" content="noindex">
    <meta name="robots" content="nofollow">

    @include('favicon')

    <title>@yield('title','Worder')</title>

    @if(app()->isLocal())
        <link id="style" href="{{ asset('res-admin/assets/plugins/bootstrap/css/bootstrap.rtl.purged.css') }}" rel="stylesheet" />
    @else
        <link id="style" href="{{ asset('res-admin/assets/plugins/bootstrap/css/bootstrap.rtl.min.css') }}" rel="stylesheet" />
    @endif

    @if(app()->isLocal())
        <link href="{{ asset('res-admin/assets/css/style.css') }}" rel="stylesheet" />
    @else
        <link href="{{ asset('res-admin/assets/css/style.purged.css') }}" rel="stylesheet" />
    @endif

    <link href="{{ asset('res-admin/assets/css/dark-style.css') }}" rel="stylesheet" />
    <link href="{{ asset('res-admin/assets/css/skin-modes.css') }}" rel="stylesheet" />
    <link id="theme" rel="stylesheet" type="text/css" media="all" href="{{ asset('res-admin/assets/colors/color1.css') }}" />
</head>

<body class="app sidebar-mini rtl dark-mode">

<div class="login-img">
    <div id="global-loader">
        <img src="{{ asset('res-admin/assets/images/loader.svg') }}" class="loader-img" alt="Loader">
    </div>
    <div class="page">
        <div class="">
            <div class="col col-login mx-auto mt-7">
                <div class="text-center">
                    <img src="{{ asset('res-share/logo.png') }}" class="header-brand-img" alt="{{ config('app.name') }}">
                </div>
            </div>
            <div class="container-login100">
                <div class="wrap-login100 p-6">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('res-admin/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('res-admin/assets/plugins/bootstrap/js/popper.min.js') }}"></script>
<script src="{{ asset('res-admin/assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('res-admin/assets/js/show-password.min.js') }}"></script>
<script src="{{ asset('res-admin/assets/plugins/p-scroll/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('res-admin/assets/js/themeColors.js') }}"></script>
<script src="{{ asset('res-admin/assets/js/custom.js') }}"></script>
</body>
</html>
