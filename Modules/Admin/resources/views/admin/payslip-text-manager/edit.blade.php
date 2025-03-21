@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
@include('admin.partial.loader.style',['load'=>[
    \App\Enums\Assets\StyleLoader::Toast(),
    \App\Enums\Assets\StyleLoader::Alert(),

]])
@endsection
@section('content')
    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.admin.payslip-text-manager.index') }}">مدیریت متن فیش حقوقی</a></li>
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>
    <p class="text-muted">
        📌 <strong>راهنمای استفاده از متغیرهای داینامیک:</strong><br>
        شما می‌توانید از متغیرهای زیر در متن خود استفاده کنید. هنگام نمایش، این متغیرها به‌صورت خودکار جایگزین می‌شوند:
    </p>
    
    <ul class="text-muted">
        <li><strong>@{{month}}</strong> → نمایش نام ماه جاری (مثلاً: اسفند)</li>
        <li><strong>@{{fullname}}</strong> → نمایش نام و نام خانوادگی کاربر (مثلاً: علی رضایی)</li>
    </ul>
    
    <p class="text-muted">
        🔹 <strong>مثال:</strong><br>
        <strong>متن ورودی:</strong> <code>گزارش ماه @{{month}} توسط @{{fullname}} ثبت شد.</code><br>
        <strong>نمایش نهایی:</strong> <code>گزارش ماه اسفند توسط علی رضایی ثبت شد.</code>
    </p>
    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-4 col-12">
            <div class="card">
                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post"
                        action="{{ route('admin.admin.payslip-text-manager.update', $payslipTextManager->id) }}">
                        @csrf
                        @method('PATCH')
                        <x-admin.textarea identify="start_text" title="متن شروع" :old="$payslipTextManager->start_text" />
                        <x-admin.textarea identify="end_text" title="متن پایان" :old="$payslipTextManager->end_text" />

                        <x-admin.button title="ویرایش" />

                        <x-admin.button title="{{ trans('panel.delete') }}" type="button" color="danger"
                            on-click="confirmDelete()" />
                    </form>
                </div>
            </div>
        </div>
    </div>

    <form id="deleteItem" action="{{ route('admin.admin.payslip-text-manager.destroy', $payslipTextManager->id) }}" method="post"
        class="form-inline">
        @csrf
        @method('DELETE')
    </form>
@endsection
@section('script')
    @include('admin.partial.request')
    @include('admin.partial.ckeditor')
    @include('admin::admin.script.share')

    @include('admin.partial.loader.script', ['load' => [
    \App\Enums\Assets\ScriptLoader::Alert(),
    \App\Enums\Assets\ScriptLoader::CKEditor(),

    ]])
    <script>
        $(document).ready(function() {
            activeParentUl('{{ route('admin.admin.payslip-text-manager.index') }}');
        })
    </script>
@endsection
