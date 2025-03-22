<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $survey->title ?? 'نظرسنجی' }}</title>

    <link href="{{ asset('res-admin/assets/plugins/bootstrap/css/bootstrap.rtl.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('res-admin/assets/font-awesome/css/fontawesome.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('res-admin/assets/font-awesome/css/light.css') }}" rel="stylesheet" />
    <link href="{{ asset('res-admin/assets/font-awesome/css/solid.css') }}" rel="stylesheet" />
    <link href="{{ asset('res-admin/assets/css/fonts.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('res-admin/assets/css/survey.css') }}">
    @yield('styles')
</head>
<body>
<div class="main-container">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</div>

<script src="{{ asset('res-admin/assets/js/jquery.min.js') }}"></script>
@yield('scripts')
</body>
</html>
