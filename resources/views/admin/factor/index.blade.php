@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
        \App\Enums\Assets\StyleLoader::Select2(),
    ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">فاکتورها</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item active">فاکتورها</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">

                <div class="card-header">
                    <h3 class="card-title">فاکتورها</h3>
                    <div class="card-options">
                        <a href="{{ route('admin.factor.create') }}" class="btn btn-success btn-sm">ایجاد فاکتور</a>
                    </div>
                </div>

                <div class="card-body">
                    @include('admin.partial.message')

                   @include('admin.factor.part.filter')

                    @if($factors->isNotEmpty())
                        <div class="table-responsive">
                            <table id="data-table" class="table">
                                <thead>
                                <tr>
                                    <th>شناسه</th>
                                    <th>عنوان</th>
                                    <th>پروژه</th>
                                    <th>کارشناس</th>
                                    <th>کاربر</th>
                                    <th>مبلغ</th>
                                    <th>وضعیت</th>
                                    <th>تاریخ ایجاد</th>
                                    <th>مهلت پرداخت</th>
                                    <th>عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($factors as $factor)
                                    <tr>
                                        <td>{{ $factor->id }}</td>
                                        <td>{{ $factor->title }}</td>
                                        <td>{{ $factor->project->title }}</td>
                                        <td>{{ $factor->admin->first_name }} {{ $factor->admin->last_name }}</td>
                                        <td>{{ $factor->user->first_name }} {{ $factor->user->last_name }}</td>
                                        <td>{{ number_format($factor->final_price) }}</td>
                                        <td>{{ \App\Enums\Database\Factor\FactorStatus::getDescription($factor->status) }}</td>
                                        <td>{{ verta($factor->created_at)->format(formatJalaliDate()) }}</td>
                                        <td>{{ verta($factor->expired_at)->format(formatJalaliDate()) }}</td>
                                         <td>
                                             <a class="btn btn-warning btn-sm" href="{{ route('admin.factor.edit',$factor->id) }}">ویرایش</a>
                                             <a class="btn btn-info btn-sm" href="{{ route('admin.factor.show',$factor->id) }}">نمایش | پرینت</a>
                                         </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $factors->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <p>فاکتوری یافت نشد!</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::Select2(),
    ]])
    @include('admin.partial.script.global')
    <script>
        $(document).ready(function () {
            makeInputPrice($('#price_from'));
            makeInputPrice($('#price_to'));
        });
    </script>
@endsection
