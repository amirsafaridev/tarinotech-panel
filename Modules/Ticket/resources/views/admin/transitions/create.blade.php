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
                <li class="breadcrumb-item"><a href="{{ route('admin.ticket.transitions.index') }}">انتقال وضعیت‌ها</a></li>
                <li class="breadcrumb-item active">ایجاد</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.ticket.transitions.store') }}">
                        @csrf

                        <x-admin.select-model identify="from_status_id"
                                              title="وضعیت مبدا"
                                              key="id"
                                              value="name"
                                              :items="$statuses"/>

                        <x-admin.select-model identify="to_status_id"
                                              title="وضعیت مقصد"
                                              key="id"
                                              value="name"
                                              :items="$statuses"/>

                        <x-admin.select-model identify="event_id"
                                              title="رویداد"
                                              key="id"
                                              value="name"
                                              :items="$events"/>

                        <x-admin.input identify="days_trigger" title="تعداد روز (اختیاری)" type="number" min="0"/>
                        <div class="form-text">در صورتی که رویداد زمانی است، تعداد روزهای مورد نیاز را وارد کنید</div>

                        <x-admin.checkbox identify="is_active"
                                      description="فعال"
                                      :old="old('is_active', 1)"/>

                        <x-admin.button title="{{ trans('panel.create') }}"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.request')
    <script>
        $(document).ready(function (){
            activeParentUl('{{ route('admin.ticket.transitions.index') }}');
        });
    </script>
@endsection