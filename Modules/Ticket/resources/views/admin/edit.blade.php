@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
       \App\Enums\Assets\StyleLoader::Toast(),
       \App\Enums\Assets\StyleLoader::Select2(),
   ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.ticket.index') }}">تیکت ها</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.ticket.manage', $chat->id) }}">مشاهده تیکت</a></li>
                <li class="breadcrumb-item active">ویرایش تیکت</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-8 col-md-8 col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">ویرایش تیکت</h3>
                </div>
                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.ticket.update', $chat->id) }}">
                        @csrf
                        @method('PATCH')

                        <x-admin.input identify="title" title="عنوان تیکت" :value="$chat->title" />

                        <div class="row">
                            <div class="col-md-6">
                                <x-admin.select-model
                                    title="موضوع تیکت"
                                    identify="subject_id"
                                    :items="$subjects"
                                    key="id"
                                    value="title"
                                    :selected="$ticketDetail->subject_id"
                                    :is-select2="true" />
                            </div>
                            <div class="col-md-6">
                                <x-admin.select-model
                                    title="وضعیت تیکت"
                                    identify="status_id"
                                    :items="$statuses"
                                    key="id"
                                    value="name"
                                    :selected="$ticketDetail->status_id"
                                    :is-select2="true" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <x-admin.select-model
                                    title="اولویت تیکت"
                                    identify="priority_id"
                                    :items="$priorities"
                                    key="id"
                                    value="name"
                                    :selected="$ticketDetail->priority_id"
                                    :is-select2="true" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-admin.button title="{{ trans('panel.update') }}"/>
                            <a href="{{ route('admin.ticket.manage', $chat->id) }}" class="btn btn-light">{{ trans('panel.cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">اطلاعات تیکت</h3>
                </div>
                <div class="card-body">
                    <p><strong>شناسه تیکت:</strong> #{{ $ticketDetail->id }}</p>
                    <p><strong>کاربر:</strong> {{ optional($chat->users->where('user_type', '!=', \Modules\Admin\app\Models\Admin::class)->first()->user)->fullname }}</p>
                    <p><strong>تاریخ ایجاد:</strong> {{ \Morilog\Jalali\Jalalian::fromDateTime($chat->created_at)->format('Y/m/d H:i') }}</p>
                    <p><strong>آخرین پاسخ:</strong> {{ \Morilog\Jalali\Jalalian::fromDateTime($ticketDetail->last_response_at)->format('Y/m/d H:i') }}</p>
                    
                    @if($ticketDetail->assigned_to)
                    <p><strong>پشتیبان:</strong> {{ optional($ticketDetail->assignedAdmin)->fullname }}</p>
                    @endif
                    
                    <div class="mt-3">
                        <a href="{{ route('admin.ticket.manage', $chat->id) }}" class="btn btn-primary w-100">
                            بازگشت به مشاهده تیکت
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
       \App\Enums\Assets\ScriptLoader::Select2(),
   ]])
    @include('admin.partial.request')
    <script>
        $(document).ready(function (){
            $('.select2').select2();
        });
    </script>
@endsection 