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

    @include('favicon')

    <title>@yield('title','Tarinotech App')</title>
    <link href="{{ asset('res-admin/assets/css/style.min.css') }}" rel="stylesheet" />

</head>

<body class="app sidebar-mini rtl @if(app()->isLocal()) dark-mode @endif">

<div class="login-img">
    <div id="global-loader">
        <div class="u-loading">
            <div class="u-loading__symbol">
                <img src="{{ asset('res-admin/assets/images/loader.png') }}" class="loader-img" alt="{{ config('app.name') }}">
            </div>
        </div>
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
