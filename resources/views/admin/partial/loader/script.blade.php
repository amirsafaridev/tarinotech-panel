@if (in_array(\App\Enums\Assets\ScriptLoader::DataTable(),$load))
    <script src="{{ asset('res-admin/assets/plugins/datatable/datatables.min.js') }}"></script>
@endif

@if (in_array(\App\Enums\Assets\ScriptLoader::MultiSelect(),$load))
    <script src="{{ asset('res-admin/assets/plugins/lou-multi-select/js/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('res-admin/assets/plugins/lou-multi-select/js/jquery.quicksearch.js') }}"></script>
@endif

@if (in_array(\App\Enums\Assets\ScriptLoader::Alert(),$load))
    <script src="{{ asset('res-admin/assets/plugins/sweetalert2/sweetalert.min.js') }}"></script>
@endif


@if (in_array(\App\Enums\Assets\ScriptLoader::Select2(),$load))
    <script src="{{ asset('res-admin/assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('res-admin/assets/plugins/select2/js/i18n/fa.js') }}"></script>
@endif

@if (in_array(\App\Enums\Assets\ScriptLoader::ChartJs(),$load))
    <script src="{{ asset('res-admin/assets/plugins/chart/Chart.bundle.js') }}"></script>
@endif

@if (in_array(\App\Enums\Assets\ScriptLoader::Datepicker(),$load))
    <script type="text/javascript" src="{{asset('res-admin/assets/plugins/persian-datepicker/persian-date.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('res-admin/assets/plugins/persian-datepicker/persian-datepicker.min.js')}}"></script>
@endif

@if (in_array(\App\Enums\Assets\ScriptLoader::InputMask(),$load))
    <script type="text/javascript" src="{{asset('res-admin/assets/plugins/inputmask/dist/min/jquery.inputmask.bundle.min.js')}}"></script>
@endif

@if (in_array(\App\Enums\Assets\ScriptLoader::CKEditor(),$load))
    <script type="text/javascript" src="{{asset('res-admin/assets/plugins/ckeditor/ckeditor.js')}}"></script>
@endif

@if (in_array(\App\Enums\Assets\ScriptLoader::Toast(),$load))
    <script type="text/javascript" src="{{asset('res-admin/assets/plugins/toast/jquery.toast.min.js')}}"></script>
@endif
