@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',[
        'load'=>[
            \App\Enums\Assets\StyleLoader::Toast(),
            \App\Enums\Assets\StyleLoader::Alert(),
        ]
    ])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.index') }}">نظرسنجی‌ها</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.question.index', $survey->id) }}">سوالات نظرسنجی</a></li>
                <li class="breadcrumb-item active">ویرایش سوال</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        ویرایش سوال نظرسنجی: {{ $survey->title }}
                    </h3>
                </div>
                <div class="card-body pb-3">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.survey.question.update', [$survey->id, $question->id]) }}">
                        @csrf
                        @method('PATCH')

                        <div class="row">
                            <div class="col-md-12">
                                <x-admin.input identify="question_text" title="متن سوال" :required="true" :old="$question->question_text"/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <x-admin.select-enum
                                    identify="question_type"
                                    title="نوع سوال"
                                    :required="true"
                                    :old="$question->question_type"
                                    :is-small="false"
                                    :enum-class="\Modules\Survey\app\Enums\Database\QuestionTypeEnum::class"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <x-admin.checkbox identify="is_required" description="پاسخ به این سوال اجباری است" :is-checked="$question->is_required"/>
                            </div>
                        </div>

                        <!-- گزینه‌های سوال انتخابی -->
                        <div id="choice-options" class="mt-4 mb-3 p-3  {{ in_array($question->question_type, [\Modules\Survey\app\Enums\Database\QuestionTypeEnum::Single, \Modules\Survey\app\Enums\Database\QuestionTypeEnum::Multiple]) ? '' : 'd-none' }}">
                            <h5>گزینه‌های سوال</h5>

                            @if($question->options && $question->options->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                        <tr>
                                            <th>متن گزینه</th>
                                            <th>ترتیب</th>
                                            <th>عملیات</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($question->options as $option)
                                            <tr>
                                                <td>{{ $option->option_text }}</td>
                                                <td>{{ $option->order }}</td>
                                                <td>
                                                    <a href="{{ route('admin.survey.question.option.edit', [$survey->id, $question->id, $option->id]) }}" class="btn btn-sm btn-warning">ویرایش</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    هنوز گزینه‌ای برای این سوال تعریف نشده است.
                                </div>
                            @endif

                            <a href="{{ route('admin.survey.question.option.index', [$survey->id, $question->id]) }}" class="btn btn-sm btn-primary mt-2">
                                مدیریت گزینه‌ها
                            </a>
                        </div>

                        <div class="d-flex mt-4">
                            <x-admin.button title="{{ trans('panel.update') }}" class="me-2"/>

                            <a href="{{ route('admin.survey.question.index', $survey->id) }}" class="btn btn-light me-2">
                                بازگشت
                            </a>

                            <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                {{ trans('panel.delete') }}
                            </button>
                        </div>
                    </form>

                    <form id="deleteItem" action="{{ route('admin.survey.question.destroy', [$survey->id, $question->id]) }}" method="post" class="form-inline">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-12">
            <!-- اطلاعات سوال -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">اطلاعات سوال</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>شناسه:</span>
                            <span class="font-weight-bold">{{ $question->id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>نظرسنجی:</span>
                            <span class="font-weight-bold">{{ $survey->title }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>نوع سوال:</span>
                            <span class="font-weight-bold">
                                @php
                                    $type = $question->question_type;
                                    $typeName = \Modules\Survey\app\Enums\Database\QuestionTypeEnum::getDescription($type);
                                    $badgeClass = '';

                                    if ($type == \Modules\Survey\app\Enums\Database\QuestionTypeEnum::Text) {
                                        $badgeClass = 'bg-primary';
                                        $icon = '<i class="fa fa-align-left me-1"></i>';
                                    } elseif ($type == \Modules\Survey\app\Enums\Database\QuestionTypeEnum::Single) {
                                        $badgeClass = 'bg-success';
                                        $icon = '<i class="fa fa-dot-circle me-1"></i>';
                                    } elseif ($type == \Modules\Survey\app\Enums\Database\QuestionTypeEnum::Multiple) {
                                        $badgeClass = 'bg-info';
                                        $icon = '<i class="fa fa-check-square me-1"></i>';
                                    } else {
                                        $badgeClass = 'bg-secondary';
                                        $icon = '<i class="fa fa-question-circle me-1"></i>';
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }}">{!! $icon !!}{{ $typeName }}</span>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>ترتیب نمایش:</span>
                            <span class="font-weight-bold">{{ $question->order }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>اجباری:</span>
                            <span class="font-weight-bold">
                                @if($question->is_required)
                                    <span class="badge bg-success">بله</span>
                                @else
                                    <span class="badge bg-secondary">خیر</span>
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>تعداد پاسخ‌ها:</span>
                            <span class="font-weight-bold">{{ $question->answers_count ?? $question->answers->count() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>تاریخ ایجاد:</span>
                            <span class="font-weight-bold">{{ $question->created_at->toJalali()->format(formatJalaliDateTime()) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>آخرین بروزرسانی:</span>
                            <span class="font-weight-bold">{{ $question->updated_at->toJalali()->format(formatJalaliDateTime()) }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- راهنمای انواع سوالات -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">راهنمای انواع سوالات</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mt-2 mb-3">
                            <thead class="bg-light">
                            <tr>
                                <th class="text-center">نوع سوال</th>
                                <th class="text-center">کاربرد</th>
                                <th class="text-center">نمونه</th>
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
                            </tbody>
                        </table>
                    </div>

                    <div class="alert alert-primary">
                        <div class="d-flex">
                            <span class="me-2"><i class="fa fa-lightbulb"></i></span>
                            <div>
                                <strong>نکته:</strong> سوالات تک انتخابی و چند انتخابی برای تحلیل آماری و ایجاد نمودار مناسب‌تر هستند.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',[
        'load'=>[
            \App\Enums\Assets\ScriptLoader::Alert(),
        ],
    ])
    @include('admin.partial.request')
    <script>
        $(document).ready(function (){
            activeParentUl('{{ route('admin.survey.index') }}');

            // Handle question type change
            $('#question_type').on('change', function() {
                const questionType = parseInt($(this).val());

                // Hide choice options section by default
                $('#choice-options').addClass('d-none');

                // Show choice options section for Single(1) and Multiple(2) choice questions
                if (questionType === {{ \Modules\Survey\app\Enums\Database\QuestionTypeEnum::Single }} ||
                    questionType === {{ \Modules\Survey\app\Enums\Database\QuestionTypeEnum::Multiple }}) {
                    $('#choice-options').removeClass('d-none');
                }
            });

            // Trigger change event on page load to handle initial state
            $('#question_type').trigger('change');
        });
    </script>
@endsection
