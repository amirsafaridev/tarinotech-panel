@php use App\Enums\Assets\StyleLoader; @endphp
@php use App\Enums\Assets\ScriptLoader; @endphp
@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
    @include('admin.partial.loader.style',[
        'load'=>[
            StyleLoader::Toast(),
            StyleLoader::Alert(),
            StyleLoader::SpectrumColorPicker(),
        ]
    ])
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
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.survey.question.option.index', [$survey->id, $question->id]) }}">گزینه‌های
                        سوال</a></li>
                <li class="breadcrumb-item active">ویرایش گزینه</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-8 col-md-8 col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        ویرایش گزینه برای سوال: {{ $question->question_text }}
                    </h3>
                </div>
                <div class="card-body pb-3">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post"
                          action="{{ route('admin.survey.question.option.update', [$survey->id, $question->id, $option->id]) }}">
                        @csrf
                        @method('PATCH')

                        <div class="row">
                            <div class="col-md-12">
                                <x-admin.input identify="option_text" title="متن گزینه" :required="true"
                                               :old="$option->option_text"/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <x-admin.input identify="color_code" title="رنگ گزینه" :old="$option->color_code"
                                               class="form-control colorpicker"/>
                                <div class="form-text">این رنگ در نمودارها و گزارش‌ها استفاده می‌شود.</div>
                            </div>
                        </div>

                        <div class="d-flex mt-4">
                            <x-admin.button title="{{ trans('panel.update') }}" class="me-2"/>
                            <a href="{{ route('admin.survey.question.option.index', [$survey->id, $question->id]) }}"
                               class="btn btn-light me-2">
                                بازگشت
                            </a>
                            <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                {{ trans('panel.delete') }}
                            </button>
                        </div>
                    </form>

                    <form id="deleteItem"
                          action="{{ route('admin.survey.question.option.destroy', [$survey->id, $question->id, $option->id]) }}"
                          method="post" class="form-inline">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-4 col-md-4 col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">اطلاعات گزینه</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>شناسه:</span>
                            <span class="font-weight-bold">{{ $option->id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>سوال:</span>
                            <span class="font-weight-bold">{{ $question->question_text }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>ترتیب نمایش:</span>
                            <span class="font-weight-bold">{{ $option->order }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>رنگ:</span>
                            <span class="font-weight-bold">
                                <span class="color-swatch"
                                      style="background-color: {{ $option->color_code }}; display: inline-block; width: 20px; height: 20px; border-radius: 4px; border: 1px solid #ccc;"></span>
                                {{ $option->color_code }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>تعداد انتخاب:</span>
                            <span class="font-weight-bold">{{ $option->answerOptions->count() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>تاریخ ایجاد:</span>
                            <span
                                class="font-weight-bold">{{ $option->created_at->toJalali()->format(formatJalaliDateTime()) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>آخرین بروزرسانی:</span>
                            <span
                                class="font-weight-bold">{{ $option->updated_at->toJalali()->format(formatJalaliDateTime()) }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">راهنما</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <div class="d-flex">
                            <span class="me-2"><i class="fa fa-exclamation-triangle"></i></span>
                            <div>
                                <strong>هشدار:</strong> در صورت حذف این گزینه، تمام پاسخ‌های مرتبط با آن نیز حذف خواهند
                                شد.
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
            ScriptLoader::Alert(),
            ScriptLoader::SpectrumColorPicker(),
        ],
    ])
    @include('admin.partial.request')
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.survey.index') }}');

            $("#color_code").spectrum({
                showInput: true,
                preferredFormat: "hex",
                showPalette: false,
                showAlpha: false,
                change: function (color) {
                    $(".sp-colorize").css("background-color", color.toHexString());
                }
            });
        });
    </script>
@endsection
