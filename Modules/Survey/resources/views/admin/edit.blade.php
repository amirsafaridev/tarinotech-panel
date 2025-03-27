@php use App\Enums\Assets\StyleLoader; @endphp
@php use Modules\Survey\app\Enums\Database\AuthTypeEnum; @endphp
@php use App\Enums\Assets\ScriptLoader; @endphp
@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
    @include('admin.partial.loader.style',[
        'load'=>[
            StyleLoader::Toast(),
            StyleLoader::Alert(),
            StyleLoader::Datepicker(),
        ]
    ])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.index') }}">پرسش نامه ها</a></li>
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-8 col-md-8 col-12">
            <div class="card">
                <div class="card-body pb-3">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post"
                          action="{{ route('admin.survey.update', $survey->id) }}">
                        @csrf
                        @method('PATCH')

                        <div class="row">
                            <div class="col-md-12">
                                <x-admin.input identify="title" title="عنوان پرسش نامه" :old="$survey->title"/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <x-admin.textarea identify="description" title="توضیحات" :old="$survey->description"/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <x-admin.textarea identify="thank_you_message" title="متن انتهایی پرسشنامه" :old="$survey->thank_you_message" placeholder="متنی که بعد از تکمیل پرسشنامه به کاربر نمایش داده می‌شود"/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <x-admin.input identify="start_date"
                                               title="تاریخ شروع"
                                               :is-date-picker="true"
                                               :old="$survey->start_date ? $survey->start_date->toJalali()->format('Y/m/d') : ''"/>
                            </div>
                            <div class="col-md-6">
                                <x-admin.input identify="end_date"
                                               title="تاریخ پایان"
                                               :is-date-picker="true"
                                               :old="$survey->end_date ? $survey->end_date->toJalali()->format('Y/m/d') : ''"/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <x-admin.checkbox identify="is_active" description="فعال"
                                                  :old="$survey->is_active"/>
                            </div>
                            <div class="col-md-6">
                                <x-admin.checkbox identify="has_meta" description="دارای متا"
                                                  :old="$survey->has_meta"/>
                            </div>
                            <div class="col-md-6">
                                <x-admin.checkbox identify="requires_auth" description="نیاز به احراز هویت"
                                                  :old="$survey->requires_auth"/>
                            </div>
                        </div>

                        <div class="row auth-guard-container {{ !$survey->requires_auth ? 'd-none' : '' }}">
                            <div class="col-md-12">
                                <x-admin.select-enum
                                    identify="auth_guard"
                                    title="انتخاب گارد احراز هویت"
                                    :old="$survey->auth_guard"
                                    :is-small="false"
                                    :enum-class="AuthTypeEnum::class"
                                />
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="access_token">لینک دسترسی</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="access_token"
                                       value="{{ route('survey.public.show', $survey->access_token)}}"
                                       readonly>
                                <button class="btn btn-outline-secondary" type="button"
                                        onclick="copyToClipboard('access_token')">کپی
                                </button>
                            </div>
                            <small class="form-text text-muted">این لینک را می‌توانید با دیگران به اشتراک بگذارید تا در
                                پرسش نامه شرکت کنند.</small>
                        </div>

                        <div class="d-flex mt-4">
                            <x-admin.button title="{{ trans('panel.update') }}" class="ml-2"/>
                            <a href="{{ route('admin.survey.question.index', $survey->id) }}" class="btn btn-info me-2">مدیریت
                                سوالات</a>
                            <a href="{{ route('admin.survey.response.index', $survey->id) }}" class="btn btn-success me-2">مشاهده پاسخ‌ها</a>
                            <button type="button" class="btn btn-danger"
                                    onclick="confirmDelete()">{{ trans('panel.delete') }}</button>
                        </div>
                    </form>

                    <form id="deleteItem" action="{{ route('admin.survey.destroy', $survey->id) }}" method="post"
                          class="form-inline">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-4 col-md-4 col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">اطلاعات پرسش نامه</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>شناسه:</span>
                            <span class="font-weight-bold">{{ $survey->id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>تعداد سوالات:</span>
                            <span
                                class="font-weight-bold">{{ $survey->questions_count ?? $survey->questions->count() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>تعداد پاسخ‌ها:</span>
                            <span
                                class="font-weight-bold">{{ $survey->responses_count ?? $survey->responses->count() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>تاریخ ایجاد:</span>
                            <span
                                class="font-weight-bold">{{ $survey->created_at->toJalali()->format(formatJalaliDateTime()) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>آخرین بروزرسانی:</span>
                            <span
                                class="font-weight-bold">{{ $survey->updated_at->toJalali()->format(formatJalaliDateTime()) }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',[
        'load'=>[
            ScriptLoader::Alert(),
            ScriptLoader::Datepicker(),
        ],
    ])
    @include('admin.partial.request')
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.survey.index') }}');
            jalaliDatepicker.startWatch();

            $('#requires_auth').on('change', function () {
                if ($(this).is(':checked')) {
                    $('.auth-guard-container').removeClass('d-none');
                } else {
                    $('.auth-guard-container').addClass('d-none');
                }
            });
        });

        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            element.select();
            document.execCommand('copy');
            $.toast({
                heading: 'موفق',
                text: 'لینک پرسش نامه کپی شد',
                allowToastClose: false,
                position: 'bottom-left',
                hideAfter: 4400,
                textAlign: 'right',
                icon: 'success'
            });
        }
    </script>
@endsection
