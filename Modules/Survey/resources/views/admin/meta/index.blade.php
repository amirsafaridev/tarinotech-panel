@php use App\Enums\Assets\StyleLoader; @endphp
@php use App\Enums\Assets\ScriptLoader; @endphp
@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
        StyleLoader::Toast(),
        StyleLoader::Select2(),
   ]])
    <style>
        .survey-link {
            max-width: 250px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: block;
        }
        .copy-link-wrapper {
            display: flex;
            align-items: center;
        }
        .copy-btn {
            margin-right: 8px;
            flex-shrink: 0;
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
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.index') }}">پرسشنامه ها</a></li>
                <li class="breadcrumb-item active">مدیریت متا</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title">افزودن متا نظرسنجی</div>
                    @if($surveyable_type && $surveyable_id)
                        <div>
                            <a class="btn btn-secondary"
                               href="{{ url()->previous() }}">بازگشت</a>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    @include('admin.partial.message')

                    @if($surveyable_type && $surveyable_id)
                        <form class="request-form forms-sample" method="post"
                              action="{{ route('admin.survey.meta.store', ['surveyable_type' => $surveyable_type, 'surveyable_id' => $surveyable_id]) }}">
                            @csrf

                            @if($modelInfo)
                                <div class="alert alert-info">
                                    <div class="d-flex align-items-center">
                                        <i class="fa fa-info-circle fs-4 me-2"></i>
                                        <div>
                                            <strong>افزودن پرسشنامه به:</strong>
                                            <span class="fw-bold">{{ $modelInfo['class'] }}: {{ $modelInfo['title'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle me-2"></i>
                                    در حال افزودن متا پرسشنامه برای:
                                    <strong>{{ $surveyable_type }} #{{ $surveyable_id }}</strong>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="survey_id">انتخاب پرسشنامه</label>
                                        <select class="form-control select2" id="survey_id" name="survey_id" required>
                                            <option value="">انتخاب کنید...</option>
                                            @foreach($surveys as $survey)
                                                <option value="{{ $survey->id }}">
                                                    {{ $survey->title }} (شناسه: {{ $survey->id }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            @if(url()->previous() && !str_contains(url()->previous(), 'survey/meta'))
                                <input type="hidden" name="redirect_back" value="{{ url()->previous() }}">
                            @endif

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">ثبت متا</button>
                                <a href="{{ url()->previous() }}" class="btn btn-light me-2">انصراف</a>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle me-2"></i>
                            برای افزودن متا نظرسنجی، باید پارامترهای <code>surveyable_type</code> و <code>surveyable_id</code> را در URL ارسال کنید.
                            <div class="mt-2">
                                <strong>مثال:</strong>
                                <code>{{ route('admin.survey.meta.index') }}?surveyable_type=Project&surveyable_id=123</code>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if($existingMetas && $existingMetas->count() > 0)
            <div class="col-xl-12 col-lg-12 mt-4">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">متاهای موجود</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>شناسه</th>
                                    <th>عنوان پرسشنامه</th>
                                    <th width="35%">لینک</th>
                                    <th>تاریخ ایجاد</th>
                                    <th>عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($existingMetas as $meta)
                                    @php
                                        $surveyLink = route('survey.public.show', [$meta->survey->access_token, 'meta'=>$meta->access_token]);
                                    @endphp
                                    <tr>
                                        <td>{{ $meta->id }}</td>
                                        <td>{{ $meta->survey->title ?? 'نامشخص' }}</td>
                                        <td>
                                            <div class="copy-link-wrapper">
                                                <a href="{{ $surveyLink }}" target="_blank" class="survey-link" title="{{ $surveyLink }}">
                                                    {{ $surveyLink }}
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-primary copy-btn"
                                                        onclick="copyToClipboard('{{ $surveyLink }}', {{ $meta->id }})">
                                                    <i class="fa fa-copy"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>{{ $meta->created_at->toJalali()->format(formatJalaliDateTime()) }}</td>
                                        <td>
                                            <form id="delete-meta-{{ $meta->id }}"
                                                  action="{{ route('admin.survey.meta.destroy', $meta->id) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="confirmDeleteMeta({{ $meta->id }})">
                                                    <i class="fa fa-trash"></i> حذف
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
        ScriptLoader::Alert(),
        ScriptLoader::Select2(),
        ScriptLoader::Toast(),
    ]])
    @include('admin.partial.request')
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.survey.index') }}');

            $('.select2').select2({
                placeholder: "انتخاب کنید...",
                width: '100%'
            });
        });

        function confirmDeleteMeta(id) {
            swal({
                title: 'آیا مطمئن هستید؟',
                text: "این متا پرسشنامه حذف خواهد شد و قابل بازیابی نیست!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#ff0f3b',
                confirmButtonText: 'بله، حذف کن!',
                cancelButtonText: 'خیر، انصراف',
                closeOnConfirm: false
            }, function () {
                document.getElementById('delete-meta-' + id).submit();
            });
        }

        function copyToClipboard(text, id) {
            // Create a temporary input element
            var tempInput = document.createElement("input");
            tempInput.value = text;
            document.body.appendChild(tempInput);

            // Select and copy the text
            tempInput.select();
            document.execCommand("copy");

            // Remove the temporary element
            document.body.removeChild(tempInput);

            // Show success message
            $.toast({
                heading: 'کپی شد!',
                text: 'لینک پرسشنامه در کلیپ بورد کپی شد.',
                position: 'bottom-left',
                loaderBg: '#ff6849',
                icon: 'success',
                hideAfter: 3500,
                stack: 6
            });
        }
    </script>
@endsection
