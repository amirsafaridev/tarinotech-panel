@if (in_array(\App\Enums\Assets\ScriptLoader::DataTable(),$load))
    <script src="{{ asset('res-admin/assets/plugins/datatable/datatables.min.js') }}"></script>
@endif

@if (in_array(\App\Enums\Assets\ScriptLoader::MultiSelect(),$load))
    <script src="{{ asset('res-admin/assets/plugins/lou-multi-select/js/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('res-admin/assets/plugins/multipleselect/jquery.quicksearch.js') }}"></script>
@endif

@if (in_array(\App\Enums\Assets\ScriptLoader::Alert(),$load))
    <script src="{{ asset('res-admin/assets/plugins/sweetalert2/sweetalert.min.js') }}"></script>
@endif

@if (in_array(\App\Enums\Assets\ScriptLoader::CellPhone(),$load))
    <script src="{{ asset('res-admin/assets/plugins/intlTelInput/js/intlTelInput.js') }}"></script>
    <script src="{{ asset('res-admin/assets/plugins/intlTelInput/js/intlTelInput-jquery.min.js') }}"></script>
@endif

@if (in_array(\App\Enums\Assets\ScriptLoader::Select2(),$load))
    <script src="{{ asset('res-admin/assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('res-admin/assets/plugins/select2/js/i18n/fa.js') }}"></script>
@endif

@if (in_array(\App\Enums\Assets\ScriptLoader::ChartJs(),$load))
    <script src="{{ asset('res-admin/assets/plugins/chart/Chart.bundle.js') }}"></script>
@endif

@if (in_array(\App\Enums\Assets\ScriptLoader::Datepicker(),$load))
    <script type="text/javascript" src="{{asset('res-admin/assets/plugins/JalaliDatePicker/jalalidatepicker.min.js')}}"></script>
@endif

@if (in_array(\App\Enums\Assets\ScriptLoader::AjaxForm(),$load))
    <script src="{{asset('res-admin/assets/plugins/jquery.form/jquery.form.min.js')}}"></script>
@endif

@if (in_array(\App\Enums\Assets\ScriptLoader::InputMask(),$load))
    <script type="text/javascript" src="{{asset('res-admin/assets/plugins/input-mask/jquery.mask.min.js')}}"></script>
@endif

@if (in_array(\App\Enums\Assets\ScriptLoader::Recorder(),$load))
    <script type="text/javascript" src="{{asset('res-admin/assets/plugins/recorderjs/recorder.js')}}"></script>
    <script type="text/javascript" src="{{asset('res-admin/assets/plugins/recorderjs/lame.min.js.js')}}"></script>
@endif

@if (in_array(\App\Enums\Assets\ScriptLoader::CKEditor(),$load))
    <script type="text/javascript" src="{{asset('res-admin/assets/plugins/ckeditor/ckeditor.js')}}"></script>
@endif

@if (in_array(\App\Enums\Assets\ScriptLoader::Toast(),$load))
    <script type="text/javascript" src="{{asset('res-admin/assets/plugins/toast/jquery.toast.min.js')}}"></script>
@endif

@if (in_array(\App\Enums\Assets\ScriptLoader::Dropzone(),$load))
    <script type="text/javascript" src="{{asset('res-admin/assets/plugins/dropzone/dropzone.min.js.js')}}"></script>
@endif

@if (in_array(\App\Enums\Assets\ScriptLoader::JQueryUI(),$load))
    <script type="text/javascript" src="{{asset('res-admin/assets/plugins/jqueryui/js/jquery-ui.min.js')}}"></script>
@endif

@if (in_array(\App\Enums\Assets\ScriptLoader::SpectrumColorPicker(),$load))
    <script type="text/javascript" src="{{asset('res-admin/assets/plugins/spectrum-colorpicker/spectrum.min.js')}}"></script>
@endif
