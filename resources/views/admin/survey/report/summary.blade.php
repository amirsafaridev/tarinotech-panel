@php use App\Enums\Assets\StyleLoader; @endphp
@php use App\Enums\Assets\ScriptLoader; @endphp
@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
        StyleLoader::Toast(),
   ]])
    <style>
        .question-card {
            margin-bottom: 30px;
            border-right: 4px solid #5d87ff;
            transition: all 0.3s ease;
        }

        .question-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .chart-container {
            position: relative;
            height: 250px;
            margin-top: 15px;
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

        .text-answer {
            border-right: 3px solid #5d87ff;
            padding: 10px 15px;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .nav-link.active {
            background-color: #5d87ff !important;
            color: white !important;
        }
    </style>
@endsection
@section('content')
    <div class="page-header">
        <h1 class="page-title">{{ $title }}: {{ $survey->title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.index') }}">پرسشنامه ها</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.survey.edit', $survey->id) }}">{{ $survey->title }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.report.index', $survey->id) }}">گزارش</a>
                </li>
                <li class="breadcrumb-item active">خلاصه نتایج</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <!-- Report actions -->
        @include('admin.survey.report.partials.report-actions')

        <!-- Navigation tabs -->
        @include('admin.survey.report.partials.tabs-navigation')

        <!-- Questions summary -->
        <div class="col-xl-12">
            <div class="tab-content" id="questionTabContent">
                <!-- All questions tab -->
                <div class="tab-pane fade show active" id="all-questions" role="tabpanel" aria-labelledby="all-tab">
                    @if(empty($questionsSummary))
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle me-1"></i>
                            هنوز داده‌ای برای نمایش وجود ندارد.
                        </div>
                    @else
                        @foreach($questionsSummary as $index => $question)
                            <div class="card question-card mb-4" id="question-{{ $question['id'] }}">
                                @include('admin.survey.report.partials.question-header')
                                <div class="card-body">
                                    <div class="row">
                                        @include('admin.survey.report.partials.question-stats')
                                        <div class="col-md-8">
                                            @if(in_array($question['type'], [1, 2]))
                                                @include('admin.survey.report.partials.question-choice')
                                            @elseif($question['type'] == 3)
                                                @include('admin.survey.report.partials.question-text')
                                            @elseif($question['type'] == 4)
                                                @include('admin.survey.report.partials.question-number')
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Choice questions tab -->
                <div class="tab-pane fade" id="choice-questions" role="tabpanel" aria-labelledby="choice-tab">
                    @include('admin.survey.report.partials.choice-questions-tab')
                </div>

                <!-- Text questions tab -->
                <div class="tab-pane fade" id="text-questions" role="tabpanel" aria-labelledby="text-tab">
                    @include('admin.survey.report.partials.text-questions-tab')
                </div>

                <!-- Number questions tab -->
                <div class="tab-pane fade" id="number-questions" role="tabpanel" aria-labelledby="number-tab">
                    @include('admin.survey.report.partials.number-questions-tab')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('admin.partial.loader.script',['load'=>[
        ScriptLoader::Chart(),
    ]])
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.survey.index') }}');

            // Generate charts for choice questions
            @foreach($questionsSummary as $question)
            @if(in_array($question['type'], [1, 2]) && isset($question['options']) && count($question['options']) > 0)
                @include('admin.survey.report.partials.chart-choice-script')
            @endif
            @endforeach

            // Generate charts for number questions
            @foreach($questionsSummary as $question)
            @if($question['type'] == 4 && isset($question['number_stats']) && isset($question['number_distribution']) && count($question['number_distribution']) > 0)
                @include('admin.survey.report.partials.chart-number-script')
            @endif
            @endforeach
        });
    </script>
@endsection