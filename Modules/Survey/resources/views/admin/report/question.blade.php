@php use App\Enums\Assets\StyleLoader; @endphp
@php use App\Enums\Assets\ScriptLoader; @endphp
@php use Modules\Survey\app\Enums\Database\QuestionTypeEnum; @endphp
@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
        StyleLoader::Toast(),
   ]])
    <style>
        .chart-container {
            position: relative;
            height: 350px;
            margin-bottom: 20px;
        }

        .chart-container-small {
            position: relative;
            height: 250px;
            margin-bottom: 20px;
        }

        .option-badge {
            display: inline-block;
            margin-right: 5px;
            margin-bottom: 5px;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            color: #fff;
        }

        .stats-card {
            transition: all 0.3s;
            border-radius: 10px;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .text-answer {
            border-right: 3px solid #5d87ff;
            padding: 10px 15px;
            margin-bottom: 15px;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .text-answer:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .word-cloud-item {
            display: inline-block;
            margin: 5px;
            padding: 5px 10px;
            border-radius: 20px;
            background-color: #5d87ff;
            color: white;
            font-size: 14px;
        }

        .word-cloud-item.size-1 {
            font-size: 14px;
            opacity: 0.7;
        }

        .word-cloud-item.size-2 {
            font-size: 16px;
            opacity: 0.8;
        }

        .word-cloud-item.size-3 {
            font-size: 18px;
            opacity: 0.9;
        }

        .word-cloud-item.size-4 {
            font-size: 20px;
        }

        .word-cloud-item.size-5 {
            font-size: 22px;
            font-weight: bold;
        }
    </style>
@endsection
@section('content')
    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.index') }}">پرسشنامه ها</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.edit', $survey->id) }}">{{ $survey->title }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.report.index', $survey->id) }}">گزارش</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.report.summary', $survey->id) }}">خلاصه نتایج</a></li>
                <li class="breadcrumb-item active">گزارش سوال</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <!-- Question info card -->
        @include('survey::admin.report.partials.question.question-info-card')

        <!-- Main content based on question type -->
        @switch($question->question_type)
            @case(QuestionTypeEnum::Text)
            @case(QuestionTypeEnum::ShortText)
                @include('survey::admin.report.partials.question-type.text')
                @break

            @case(QuestionTypeEnum::Single)
            @case(QuestionTypeEnum::Multiple)
                @include('survey::admin.report.partials.question-type.option')
                @break

            @case(QuestionTypeEnum::Number)
                @include('survey::admin.report.partials.question-type.number')
                @break
        @endswitch
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
        ScriptLoader::Chart(),
    ]])
    @include('survey::admin.report.partials.script.question-scripts')
@endsection
