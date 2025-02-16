@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('content')
    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.factor.index') }}">فاکتور ها</a></li>
                <li class="breadcrumb-item active">نمایش</li>
            </ol>
        </div>
    </div>

    <div class="row hide-in-print">
        <div class="col-12 col-md-6 mb-3">
            @include('factor::admin.part.card-info',['factor' => $factor])
        </div>

        <div class="col-12 col-md-6 mb-3">
            @include('factor::admin.part.card-project',['factor' => $factor])

            @if($factor->manual)
                @include('factor::admin.part.card-manual-info',['factor' => $factor])
            @endif

            @if($factor->cheque)
                @include('factor::admin.part.card-cheque-info',['factor' => $factor])
            @endif

            @if($factor->items->isNotEmpty())
                @include('factor::admin.part.card-items',['items' => $factor->items])
            @endif
        </div>
    </div>

@endsection
@section('script')
    @include('admin.partial.script.global')
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.factor.index') }}');
        });
    </script>
@endsection
