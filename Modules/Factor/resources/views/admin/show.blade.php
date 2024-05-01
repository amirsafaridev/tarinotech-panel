@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    <style>
        .print-main-table {
            padding: 0;
            margin: 0;
            width: 100%;
            font-size: 16px;
        }

        .print-main-table table, .print-main-table th, .print-main-table td {
            border: 1px solid #686868;
        }

        .print-main-table td {
            padding: 10px;
        }

        .print-main-table p {
            padding: 0;
            margin: 0;
        }

        @media print {
            .jumps-prevent {
                display: none;
            }

            .rtl .app-content {
                margin: 0;
            }

            .hide-in-print {
                display: none !important;
            }

            .print-main-table .p-title {
                font-weight: bold;
                font-size: 18px;
            }

            .print-main-table .f-bold {
                font-weight: bold;
            }

            .print-main-table .header-row-bg {
                background-color: #f3f3f3;
            }

            .print-main-table table, .print-main-table th, .print-main-table td {
                border: 1px solid #686868;
                color: black;
            }

            .print-main-table td {
                padding: 10px;
            }

            .card, .app-content, .page-main {
                background-color: white !important;
            }

            .side-app {
                padding: 0 !important;
            }
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
            @if($factor->items->isNotEmpty())
                @include('factor::admin.part.card-items',['items' => $factor->items])
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center hide-in-print">
                    <span class="bold">پرینت فاکتور</span>
                    <button id="btn_print" type="button" class="btn btn-sm btn-success">پرینت فاکتور</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        @if($factor->project)
                            @if($factor->is_official || $factor->project->user->official_bill))
                                @include('factor::admin.part.official_invoice')
                            @else
                                @include('factor::admin.part.unofficial_invoice')
                            @endif

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    @include('admin.partial.script.global')
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.factor.index') }}');
            $('#btn_print').click(function () {
                printMe();
            })
        });
    </script>
@endsection
