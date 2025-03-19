@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
    @include('admin.partial.loader.style', [
        'load' => [
        \App\Enums\Assets\StyleLoader::Toast(),
         \App\Enums\Assets\StyleLoader::Datepicker()],
    ])
@endsection
@section('content')
    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.personnel.personnel-report.index') }}">گزارش تردد و مرخصی</a>
                </li>
                <li class="breadcrumb-item active">ایجاد درخواست مرخصی</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-8 col-md-8 col-12">
            <div class="card">
                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <form id="leaveRequestForm" class="request-form forms-sample" method="post"
                        action="{{ route('admin.personnel.personnel-report.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">نوع مرخصی</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="daily" value="daily" checked>
                                <label class="form-check-label" for="daily">روزانه</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="hourly" value="hourly">
                                <label class="form-check-label" for="hourly">ساعتی</label>
                            </div>
                        </div>

                        <div id="dailyFields">
                            <x-admin.input identify="start_date" title="از تاریخ" :is-date-picker="true" required />
                            <x-admin.input identify="end_date" title="تا تاریخ" :is-date-picker="true" required />
                        </div>

                        <div id="hourlyFields" style="display: none;">
                            <x-admin.input identify="date" title="تاریخ" :is-date-picker="true" required />
                            <x-admin.input type="time" identify="start_time" title="از ساعت" required />
                            <x-admin.input type="time" identify="end_time" title="تا ساعت" required />
                        </div>

                        <x-admin.textarea identify="description" title="توضیحات" />

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="rules_accepted" id="rulesAccepted" value="1" required>
                                <label class="form-check-label" for="rulesAccepted">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#rulesModal">قوانین مرخصی</a> را مطالعه کردم و می‌پذیرم
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_emergency" id="isEmergency" value="1">
                                <label class="form-check-label" for="isEmergency">
                                    این درخواست مرخصی اضطراری است
                                </label>
                            </div>
                        </div>

                        <x-admin.button title="ثبت درخواست" />
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal قوانین مرخصی -->
    <div class="modal fade" id="rulesModal" tabindex="-1" aria-labelledby="rulesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rulesModalLabel">قوانین مرخصی</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>قوانین کلی مرخصی:</h6>
                    <ul>
                        <li>درخواست مرخصی باید حداقل 3 روز قبل از تاریخ مورد نظر ثبت شود.</li>
                        <li>مرخصی ساعتی حداکثر 4 ساعت در روز مجاز است.</li>
                        <li>مرخصی روزانه حداکثر 3 روز متوالی مجاز است.</li>
                        <li>در صورت نیاز به مرخصی بیشتر از 3 روز، باید با مدیر مستقیم هماهنگ شود.</li>
                        <li>در صورت عدم حضور در روزهای مرخصی، باید از طریق سیستم تردد ثبت شود.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::Datepicker(),
    ]])
    @include('admin.partial.request')
    @include('admin.partial.script.global')

    <script>
        $(document).ready(function() {
            jalaliDatepicker.startWatch();

            activeParentUl('{{ route('admin.admin.personnel-report.index') }}');

            // تغییر نوع مرخصی
            $('input[name="type"]').change(function() {
                if ($(this).val() === 'daily') {
                    $('#dailyFields').show();
                    $('#hourlyFields').hide();
                } else {
                    $('#dailyFields').hide();
                    $('#hourlyFields').show();
                }
            });

            // اعتبارسنجی فرم
            $('#leaveRequestForm').submit(function(e) {
                const type = $('input[name="type"]:checked').val();
                
                if (type === 'daily') {
                    const startDate = new Date($('#start_date').val());
                    const endDate = new Date($('#end_date').val());
                    
                    if (endDate < startDate) {
                        e.preventDefault();
                        showAlert('error', 'تاریخ پایان نمی‌تواند قبل از تاریخ شروع باشد');
                        return false;
                    }
                } else {
                    const startTime = $('#start_time').val();
                    const endTime = $('#end_time').val();
                    
                    if (endTime <= startTime) {
                        e.preventDefault();
                        showAlert('error', 'ساعت پایان باید بعد از ساعت شروع باشد');
                        return false;
                    }
                }

                if (!$('#rulesAccepted').is(':checked')) {
                    e.preventDefault();
                    showAlert('error', 'لطفاً قوانین مرخصی را مطالعه و پذیرش کنید');
                    return false;
                }
            });
        })
    </script>
@endsection