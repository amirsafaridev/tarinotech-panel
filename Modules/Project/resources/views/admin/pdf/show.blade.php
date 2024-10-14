<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="{{ asset('res-admin/assets/css/fonts.css') }}">
    <style>
        body {
            font-family: iransanse, serif;
            direction: rtl;
            font-size: 12px;
            line-height: 24px;
        }

        table {
            border-spacing: 0px;
            border-collapse: separate;
            width: 100%;
        }

        table td {
            border: 1px solid #d8d8d8;
        }

        td, th { /* table cells */
            padding: 5px;
        }

        .header-row-bg {
            background-color: #f4f4f4;
        }

        .text-center {
            text-align: center;
        }

        .p-title {
            font-weight: bold;
        }

        .f-bold {
            font-weight: bold;
        }
    </style>
</head>
<body dir="rtl">
<div class="row">
    <div class="col-xl-6 col-lg-6 col-md-6 col-12">
        @include('project::admin.part.project-info-card')
    </div>

    <div class="col-xl-6 col-lg-6 col-md-6 col-12">
        @if($project->target_type  === \Modules\Project\app\Models\ProjectWeb::class)
            @include('project::admin.part.web-info-card')
        @endif

        @if($project->target_type  === \Modules\Project\app\Models\ProjectSeo::class)
            @include('project::admin.part.seo-info-card')
        @endif

        @if($project->target_type  === \Modules\Project\app\Models\ProjectAds::class)
            @include('project::admin.part.ads-info-card')
        @endif
    </div>
</div>
</body>
</html>




