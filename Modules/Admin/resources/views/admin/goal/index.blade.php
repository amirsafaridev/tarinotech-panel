@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
       \App\Enums\Assets\StyleLoader::Toast(),
   ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.admin.index') }}">{{ trans('panel.admin.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.admin.show',$admin->id) }}">{{ sprintf('%s %s',$admin->first_name,$admin->last_name) }}</a></li>
                <li class="breadcrumb-item active">اهداف فروش</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h4>{{ $admin->first_name }} {{ $admin->last_name }}</h4>
                    </div>
                    @include('admin.partial.message')
                    <div class="table-responsive">
                        <table id="data-table" class="table table-condensed">
                            <thead>
                            <tr>
                                <th>نام ماه</th>
                                <th>تاریخ شروع</th>
                                <th>تاریخ پایان</th>
                                <th>هدف فروش (ریال)</th>
                                <th>هدف فروش (دلار)</th>
                                <th>{{ trans('datatable.action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($dateItems as $item)
                                <tr>
                                    <td class="d-flex flex-column gap-2">
                                        <span> {{ $item['start']->format('F') }}</span>
                                        <span class="text-sm"> {{ $item['start']->toCarbon()->format('F') }} - {{ $item['end']->toCarbon()->format('F') }}</span>
                                    </td>
                                    <td>{{ $item['start']->format('Y-m-d') }}</td>
                                    <td>{{ $item['end']->format('Y-m-d') }}</td>
                                    <td>
                                        <input class="form-control inp-profitability" type="text"
                                               value="{{ number_format($item['profitability']) }}">
                                    </td>
                                    <td>
                                        <input class="form-control inp-profitability-dollar" type="text"
                                               value="{{ number_format($item['profitability_dollar'],2) }}">
                                    </td>
                                    <td>
                                        <button class="btn btn-success btn-sm btn-submit"
                                                data-start="{{ $item['start']->toCarbon()->format('Y-m-d') }}"
                                                data-end="{{ $item['end']->toCarbon()->format('Y-m-d') }}"
                                                type="button">ثبت
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::Toast(),
    ]])
    @include('admin.partial.script.global')
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.admin.index') }}');
            makeInputPrice($(".inp-profitability-dollar"), true);
            makeInputPrice($(".inp-profitability"));
            $('.btn-submit').click(function () {
                const btn = $(this);
                const profitability = btn.parent().parent().find('.inp-profitability').val();
                const profitabilityDollar = btn.parent().parent().find('.inp-profitability-dollar').val();
                const postData = {
                    profitability: profitability,
                    profitability_dollar: profitabilityDollar,
                    start: btn.data('start'),
                    end: btn.data('end')
                };
                submit(postData, btn);
            })
        });

        function submit(postData, target) {
            target.text('صبر کنید');
            postAjax('{{ route('admin.admin.goal.save',$admin->id) }}', postData)
                .then(function (response) {
                    target.text('ثبت');
                })
                .catch(function (response) {
                    target.text('ثبت');
                });
        }
    </script>
@endsection
