@php
    use App\Enums\Assets\ScriptLoader;use App\Enums\Assets\StyleLoader;use Modules\Survey\app\Enums\Database\QuestionTypeEnum;
@endphp
@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
    @include('admin.partial.loader.style',[
        'load'=>[
            StyleLoader::Alert(),
        ]
    ])
    <style>
        .question-card {
            border-right: 4px solid #5d87ff;
            margin-bottom: 15px;
        }

        .required-badge {
            font-size: 10px;
            padding: 3px 6px;
            margin-right: 5px;
            vertical-align: middle;
        }

        .option-badge {
            display: inline-block;
            margin-right: 5px;
            margin-bottom: 5px;
            padding: 5px 10px;
            border-radius: 20px;
            color: #fff;
        }

        .option-selected {
            background-color: #00c069 !important;
            color: #ffffff;
        }

        .option-unselected {
            background-color: #f0f0f5 !important;
            color: #6c6c8e !important;
        }

        .dark-mode .option-selected {
            background-color: #00e77b !important;
            color: #1a1a3c;
        }

        .dark-mode .option-unselected {
            background-color: rgba(26, 26, 60, 0.49) !important;
            color: white !important;
        }

        .answer-value {
            font-weight: 500;
            font-size: 1.1em;
            color: #2e323e;
        }

        .dark-mode .answer-value {
            color: #f0f0f5;
        }
    </style>
@endsection
@section('content')
    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.index') }}">نظرسنجی‌ها</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.survey.edit', $survey->id) }}">{{ $survey->title }}</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.survey.response.index', $survey->id) }}">پاسخ‌ها</a></li>
                <li class="breadcrumb-item active">جزئیات پاسخ</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <!-- Response details -->
        <div class="col-xl-8 col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">پاسخ‌های داده شده</h3>
                </div>
                <div class="card-body">
                    @if($response->answers->isEmpty())
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle me-2"></i>
                            هیچ پاسخی ثبت نشده است.
                        </div>
                    @else
                        @php
                            $groupedAnswers = $response->answers->groupBy('survey_question_id');
                            $questions = $survey->questions()->orderBy('order')->get();
                        @endphp

                        @foreach($questions as $question)
                            <div class="card question-card mb-4 shadow-sm">
                                <div class="card-header py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">
                                            {{ $question->question_text }}
                                            @if($question->is_required)
                                                <span class="badge bg-danger required-badge">اجباری</span>
                                            @endif
                                        </h5>
                                        <span class="badge bg-secondary">
                                            @php
                                                $typeName = QuestionTypeEnum::getDescription($question->question_type);
                                                echo $typeName;
                                            @endphp
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body answer-card">
                                    @if(isset($groupedAnswers[$question->id]))
                                        @php
                                            $answer = $groupedAnswers[$question->id]->first();
                                        @endphp

                                        @switch($question->question_type)
                                            @case(QuestionTypeEnum::Text)
                                                @include('survey::admin.response.partials.text', ['answer' => $answer])
                                                @break
                                            @case(QuestionTypeEnum::Number)
                                                @include('survey::admin.response.partials.number', ['answer' => $answer])
                                                @break
                                            @case(QuestionTypeEnum::Single)
                                            @case(QuestionTypeEnum::Multiple)
                                                @include('survey::admin.response.partials.choice', ['answer' => $answer, 'question' => $question])
                                                @break
                                            @default
                                                @include('survey::admin.response.partials.unsupported')
                                        @endswitch
                                    @else
                                    @include('survey::admin.response.partials.no-answer')
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <!-- Response metadata card -->
        <div class="col-xl-4 col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">اطلاعات پاسخ دهنده</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>شناسه پاسخ:</span>
                            <span class="fw-bold">{{ $response->id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>نام پاسخ دهنده:</span>
                            <span class="fw-bold">{{ $response->respondent_name ?? 'نامشخص' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>ایمیل پاسخ دهنده:</span>
                            <span class="fw-bold">{{ $response->respondent_email ?? 'نامشخص' }}</span>
                        </li>
                        @if($response->user_id)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>شناسه کاربری:</span>
                                <span class="fw-bold">{{ $response->user_id }}</span>
                            </li>
                        @endif
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>آی‌پی:</span>
                            <span class="fw-bold">{{ $response->ip_address }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>شروع نظرسنجی:</span>
                            <span
                                class="fw-bold">{{ $response->started_at ? $response->started_at->toJalali()->format(formatJalaliDateTime()) : 'نامشخص' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>پایان نظرسنجی:</span>
                            <span
                                class="fw-bold">{{ $response->completed_at ? $response->completed_at->toJalali()->format(formatJalaliDateTime()) : 'نامشخص' }}</span>
                        </li>
                        @if($response->started_at && $response->completed_at)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>مدت زمان تکمیل:</span>
                                <span class="fw-bold">{{ $response->getCompletionTimeString() }}</span>
                            </li>
                        @endif
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>تاریخ ثبت:</span>
                            <span
                                class="fw-bold">{{ $response->created_at->toJalali()->format(formatJalaliDateTime()) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>تعداد پاسخ‌ها:</span>
                            <span class="fw-bold">{{ $response->answers->count() }}</span>
                        </li>
                    </ul>
                </div>
                <div class="card-footer">
                    <div class="d-flex">
                        <a href="{{ route('admin.survey.response.index', $survey->id) }}"
                           class="btn btn-outline-primary me-2">
                            <i class="fa fa-arrow-right ml-1"></i> بازگشت به لیست
                        </a>
                        <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                            <i class="fa fa-trash ml-1"></i> حذف پاسخ
                        </button>
                    </div>
                </div>
            </div>

            <!-- Survey info -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">اطلاعات نظرسنجی</h3>
                </div>
                <div class="card-body">
                    <h4>{{ $survey->title }}</h4>
                    <p class="text-muted">{{ $survey->description }}</p>
                    <div class="d-flex justify-content-between mt-3">
                        <span>وضعیت:</span>
                        <span>{!! $survey->is_active ? '<span class="badge bg-success">فعال</span>' : '<span class="badge bg-danger">غیرفعال</span>' !!}</span>
                    </div>
                    @if($survey->start_date)
                        <div class="d-flex justify-content-between mt-2">
                            <span>تاریخ شروع:</span>
                            <span>{{ $survey->start_date->toJalali()->format(formatJalaliDate()) }}</span>
                        </div>
                    @endif
                    @if($survey->end_date)
                        <div class="d-flex justify-content-between mt-2">
                            <span>تاریخ پایان:</span>
                            <span>{{ $survey->end_date->toJalali()->format(formatJalaliDate()) }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <form id="deleteForm" action="{{ route('admin.survey.response.destroy', [$survey->id, $response->id]) }}"
          method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection
@section('script')
    @include('admin.partial.loader.script',[
        'load'=>[
            ScriptLoader::Alert(),
        ]
    ])
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.survey.index') }}');
        });

        function confirmDelete() {
            swal({
                title: 'آیا مطمئن هستید؟',
                text: "این پاسخ به همراه تمام جزئیات آن حذف خواهد شد و قابل بازیابی نیست!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#ff0f3b',
                confirmButtonText: 'بله، حذف کن!',
                cancelButtonText: 'خیر، انصراف',
                closeOnConfirm: false
            }, function () {
                document.getElementById('deleteForm').submit();
            });
        }
    </script>
@endsection
