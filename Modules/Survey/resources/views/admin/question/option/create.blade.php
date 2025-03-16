@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
       \App\Enums\Assets\StyleLoader::Toast(),
       \App\Enums\Assets\StyleLoader::SpectrumColorPicker(),
   ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.index') }}">نظرسنجی‌ها</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.question.index', $survey->id) }}">سوالات نظرسنجی</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.question.option.index', [$survey->id, $question->id]) }}">گزینه‌های سوال</a></li>
                <li class="breadcrumb-item active">افزودن گزینه جدید</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-8 col-md-8 col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        افزودن گزینه جدید برای سوال: {{ $question->question_text }}
                    </h3>
                </div>
                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.survey.question.option.store', [$survey->id, $question->id]) }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-12">
                                <x-admin.input identify="option_text" title="متن گزینه" :required="true"/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <x-admin.input identify="color_code" title="رنگ گزینه" :old="'#' . substr(md5(time()), 0, 6)" class="form-control"/>
                                <div class="form-text">این رنگ در نمودارها و گزارش‌ها استفاده می‌شود.</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <x-admin.checkbox identify="create_another" description="پس از ذخیره، گزینه دیگری اضافه کنم"/>
                            </div>
                        </div>

                        <div class="d-flex mt-4">
                            <x-admin.button title="{{ trans('panel.create') }}" class="me-2"/>
                            <a href="{{ route('admin.survey.question.option.index', [$survey->id, $question->id]) }}" class="btn btn-light">انصراف</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-4 col-md-4 col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">راهنما</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <div class="d-flex">
                            <span class="me-2"><i class="fa fa-info-circle"></i></span>
                            <div>
                                <strong>نکته:</strong> برای هر سوال انتخابی، حداقل دو گزینه تعریف کنید.
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-primary">
                        <div class="d-flex">
                            <span class="me-2"><i class="fa fa-lightbulb"></i></span>
                            <div>
                                <strong>پیشنهاد:</strong> متن گزینه‌ها را کوتاه و گویا انتخاب کنید تا نمودارها خوانایی بهتری داشته باشند.
                            </div>
                        </div>
                    </div>


                    <div class="alert alert-secondary">
                        <div class="d-flex">
                            <span class="me-2"><i class="fa fa-palette"></i></span>
                            <div>
                                <strong>رنگ گزینه:</strong> می‌توانید برای هر گزینه یک رنگ اختصاص دهید تا در نمودارها و گزارش‌ها با این رنگ نمایش داده شود.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::SpectrumColorPicker(),
    ]])
    @include('admin.partial.request')
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.survey.index') }}');

            $("#color_code").spectrum({
                showInput: true,
                preferredFormat: "hex",
                showPalette: false,
                showAlpha: false,
                change: function(color) {
                    // Update the preview with the new color
                    $(".sp-colorize").css("background-color", color.toHexString());
                }
            });
        });
    </script>
@endsection
