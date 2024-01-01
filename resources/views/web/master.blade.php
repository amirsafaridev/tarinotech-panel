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

    <title>@yield('title','تارینوتک - پورتال')</title>

    <link href="{{ asset('res-admin/assets/font-awesome/css/light.css') }}" rel="stylesheet"/>
    <link href="{{ asset('res-admin/assets/font-awesome/css/solid.css') }}" rel="stylesheet"/>

    @yield('head')

    <link id="theme" rel="stylesheet" type="text/css" media="all" href="{{ asset('res-admin/assets/css/page.css') }}"/>

</head>

<body>
@yield('content')
</body>

</html>
