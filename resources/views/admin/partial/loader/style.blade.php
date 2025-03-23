@if (in_array(\App\Enums\Assets\StyleLoader::DataTable(),$load))

@endif

@if (in_array(\App\Enums\Assets\StyleLoader::Toast(),$load))
    <link rel="stylesheet" href="{{ asset('res-admin/assets/plugins/toast/jquery.toast.min.css') }}">
@endif

@if (in_array(\App\Enums\Assets\StyleLoader::MultiSelect(),$load))
    <link rel="stylesheet" href="{{ asset('res-admin/assets/plugins/lou-multi-select/css/multi-select.css') }}">
@endif

@if (in_array(\App\Enums\Assets\StyleLoader::CellPhone(),$load))
    <link rel="stylesheet" href="{{ asset('res-admin/assets/plugins/intlTelInput/css/intlTelInput.min.css') }}">
@endif

@if (in_array(\App\Enums\Assets\StyleLoader::Alert(),$load))
    <link rel="stylesheet" href="{{ asset('res-admin/assets/plugins/sweetalert2/sweetalert.css') }}">
@endif

@if (in_array(\App\Enums\Assets\StyleLoader::Select2(),$load))
    <link rel="stylesheet" href="{{ asset('res-admin/assets/plugins/select2/css/select2.min.css') }}">
@endif

@if (in_array(\App\Enums\Assets\StyleLoader::Quill(),$load))
    <script src="{{ asset('res-admin/assets/plugins/ckeditor/ckeditor.js') }}"></script>
@endif

@if (in_array(\App\Enums\Assets\StyleLoader::Datepicker(),$load))
    <link rel="stylesheet" href="{{ asset('res-admin/assets/plugins/JalaliDatePicker/jalalidatepicker.min.css') }}">
@endif

@if (in_array(\App\Enums\Assets\StyleLoader::Acf(),$load))
    <link rel="stylesheet" href="{{ asset('res-admin/assets/css/acf.css') }}">
@endif

@if (in_array(\App\Enums\Assets\StyleLoader::Dropzone(),$load))
    <link rel="stylesheet" href="{{ asset('res-admin/assets/plugins/dropzone/dropzone.min.css') }}">
@endif

@if (in_array(\App\Enums\Assets\StyleLoader::JQueryUI(),$load))

@endif

@if (in_array(\App\Enums\Assets\StyleLoader::SpectrumColorPicker(),$load))
    <link rel="stylesheet" src="{{asset('res-admin/assets/plugins/spectrum-colorpicker/spectrum.min.css')}}">
    <style>
        .sp-colorize{
            height: 39px !important;
        }
    </style>
@endif
