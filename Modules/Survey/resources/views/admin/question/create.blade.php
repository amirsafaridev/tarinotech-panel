@php use App\Enums\Assets\StyleLoader; @endphp
@php use Modules\Survey\app\Enums\Database\QuestionTypeEnum; @endphp
@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
       StyleLoader::Toast(),
   ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.index') }}">نظرسنجی‌ها</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.question.index', $survey->id) }}">سوالات
                        نظرسنجی</a></li>
                <li class="breadcrumb-item active">افزودن سوال جدید</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8  col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        افزودن سوال جدید به: {{ $survey->title }}
                    </h3>
                </div>
                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post"
                          action="{{ route('admin.survey.question.store', $survey->id) }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-12">
                                <x-admin.input identify="question_text" title="متن سوال" :required="true"/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <x-admin.select-enum
                                    identify="question_type"
                                    title="نوع سوال"
                                    :required="true"
                                    :old="request('question_type')"
                                    :is-small="false"
                                    :enum-class="QuestionTypeEnum::class"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <x-admin.checkbox identify="is_required" description="پاسخ به این سوال اجباری است"/>
                            </div>
                        </div>

                        <!-- گزینه‌های سوال انتخابی -->
                        <div id="choice-options" class="mt-4 mb-3 p-3  d-none">
                            <h5>گزینه‌های سوال</h5>
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle me-2"></i>
                                پس از ایجاد سوال به صفحه مدیریت گزینه‌ها هدایت خواهید شد.
                            </div>
                        </div>

                        <!-- تنظیمات سوال عددی -->
                        <div id="number-options" class="mt-4 mb-3 p-3 d-none">
                            <h5>تنظیمات ورودی عددی</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <x-admin.input identify="settings[min]" title="حداقل مقدار" type="number" step="any" :old="request('settings.min')" />
                                </div>
                                <div class="col-md-6">
                                    <x-admin.input identify="settings[max]" title="حداکثر مقدار" type="number" step="any" :old="request('settings.max')" />
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <x-admin.input identify="settings[step]" title="گام" type="number" step="any" placeholder="مثال: 0.1، 1، 5" :old="request('settings.step')" />
                                </div>
                                <div class="col-md-6">
                                    <x-admin.input identify="settings[default]" title="مقدار پیش‌فرض" type="number" step="any" :old="request('settings.default')" />
                                </div>
                            </div>
                            <div class="alert alert-info mt-3">
                                <i class="fa fa-info-circle me-2"></i>
                                می‌توانید محدوده اعداد مجاز، گام‌های افزایش/کاهش و مقدار پیش‌فرض را تنظیم کنید.
                            </div>
                        </div>

                        <div class="d-flex mt-4">
                            <x-admin.button title="{{ trans('panel.create') }}" class="me-2"/>
                            <a href="{{ route('admin.survey.question.index', $survey->id) }}" class="btn btn-light">انصراف</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-4  col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">راهنمای ایجاد سوال</h3>
                </div>
                <div class="card-body">
                    <h5>انواع سوالات</h5>
                    <div class="table-responsive">
                        <table class="table mt-3 mb-4">
                            <thead class="bg-light">
                            <tr>
                                <th class="text-center">نوع سوال</th>
                                <th class="text-center">کاربرد</th>
                                <th class="text-center">نمونه خروجی</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <span class="d-block fw-bold">متنی</span>
                                    <span class="badge bg-primary mt-1">Text</span>
                                </td>
                                <td>پاسخ‌های تشریحی و توضیحات</td>
                                <td class="text-center">
                                    <i class="fa fa-align-left text-muted"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="d-block fw-bold">تک انتخابی</span>
                                    <span class="badge bg-success mt-1">Single Choice</span>
                                </td>
                                <td>انتخاب یک گزینه از میان چند گزینه</td>
                                <td class="text-center">
                                    <i class="fa fa-dot-circle text-success"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="d-block fw-bold">چند انتخابی</span>
                                    <span class="badge bg-info mt-1">Multiple Choice</span>
                                </td>
                                <td>انتخاب چند گزینه همزمان</td>
                                <td class="text-center">
                                    <i class="fa fa-check-square text-info"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="d-block fw-bold">عددی</span>
                                    <span class="badge bg-warning mt-1">Number</span>
                                </td>
                                <td>ورود مقادیر عددی و محدوده‌های کمی</td>
                                <td class="text-center">
                                    <i class="fa fa-calculator text-warning"></i>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="alert alert-primary">
                        <div class="d-flex">
                            <span class="me-2"><i class="fa fa-lightbulb"></i></span>
                            <div>
                                <strong>نکته:</strong> سوالات تک انتخابی و چند انتخابی برای تحلیل آماری و ایجاد نمودار
                                مناسب‌تر هستند.
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <div class="d-flex">
                            <span class="me-2"><i class="fa fa-exclamation-triangle"></i></span>
                            <div>
                                <strong>توجه:</strong> برای سوالات انتخابی، حتماً باید حداقل دو گزینه تعریف کنید.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.request')
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.survey.index') }}');

            // Handle question type change
            $('#question_type').on('change', function () {
                const questionType = parseInt($(this).val());

                // Hide all option sections by default
                $('#choice-options, #number-options').addClass('d-none');

                // Show choice options section for Single(1) and Multiple(2) choice questions
                if (questionType === {{ QuestionTypeEnum::Single }} ||
                    questionType === {{ QuestionTypeEnum::Multiple }}) {
                    $('#choice-options').removeClass('d-none');
                }

                // Show number options section for Number(4) question type
                if (questionType === {{ QuestionTypeEnum::Number }}) {
                    $('#number-options').removeClass('d-none');
                }
            });

            // Trigger change event on page load to handle initial state
            $('#question_type').trigger('change');
        });
    </script>
@endsection
