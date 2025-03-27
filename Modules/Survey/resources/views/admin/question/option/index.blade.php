@php use App\Enums\Assets\StyleLoader; @endphp
@php use Modules\Survey\app\Enums\Database\QuestionTypeEnum; @endphp
@php use App\Enums\Assets\ScriptLoader; @endphp
@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
        StyleLoader::Toast(),
        StyleLoader::JQueryUI(),
   ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.index') }}">پرسشنامه ها</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.question.index', $survey->id) }}">سوالات
                        پرسشنامه</a></li>
                <li class="breadcrumb-item active">گزینه‌های سوال</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title">
                        <h4 class="mb-0">{{ $question->question_text }}</h4>
                        <p class="text-muted mb-0 fs-12">
                            نوع سوال:
                            @php
                                $type = $question->question_type;
                                $typeName = QuestionTypeEnum::getDescription($type);
                                $badgeClass = '';

                                if ($type == QuestionTypeEnum::Text) {
                                    $badgeClass = 'bg-primary';
                                    $icon = '<i class="fa fa-align-left me-1"></i>';
                                } elseif ($type == QuestionTypeEnum::Single) {
                                    $badgeClass = 'bg-success';
                                    $icon = '<i class="fa fa-dot-circle me-1"></i>';
                                } elseif ($type == QuestionTypeEnum::Multiple) {
                                    $badgeClass = 'bg-info';
                                    $icon = '<i class="fa fa-check-square me-1"></i>';
                                } else {
                                    $badgeClass = 'bg-secondary';
                                    $icon = '<i class="fa fa-question-circle me-1"></i>';
                                }
                            @endphp
                            <span class="badge {{ $badgeClass }}">{!! $icon !!}{{ $typeName }}</span>
                        </p>
                    </div>
                    <div>
                        <a class="btn btn-primary"
                           href="{{ route('admin.survey.question.option.create', [$survey->id, $question->id]) }}">افزودن
                            گزینه جدید</a>
                        <a class="btn btn-secondary" href="{{ route('admin.survey.question.index', $survey->id) }}">بازگشت
                            به سوالات</a>
                    </div>
                </div>
                <div class="card-body">
                    @include('admin.partial.message')

                    @if($options->isEmpty())
                        <div class="alert alert-info">
                            هنوز گزینه‌ای برای این سوال تعریف نشده است. برای افزودن گزینه روی دکمه "افزودن گزینه جدید"
                            کلیک کنید.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap">
                                <thead>
                                <tr>
                                    <th width="10"></th>
                                    <th>شناسه</th>
                                    <th>ترتیب</th>
                                    <th>متن گزینه</th>
                                    <th>رنگ</th>
                                    <th>عملیات</th>
                                </tr>
                                </thead>
                                <tbody id="sortable-options">
                                @foreach($options as $option)
                                    <tr data-id="{{ $option->id }}">
                                        <td>
                                            <div class="drag-handle cursor-move">
                                                <i class="fa fa-bars"></i>
                                            </div>
                                        </td>
                                        <td>{{ $option->id }}</td>
                                        <td>{{ $option->order }}</td>
                                        <td>{{ $option->option_text }}</td>
                                        <td>
                                            <span class="color-swatch"
                                                  style="background-color: {{ $option->color_code }}; display: inline-block; width: 20px; height: 20px; border-radius: 4px; border: 1px solid #ccc;"></span>
                                            <small>{{ $option->color_code }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('admin.survey.question.option.edit', [$survey->id, $question->id, $option->id]) }}"
                                                   class="btn btn-sm btn-warning">
                                                    <i class="fa fa-edit"></i> ویرایش
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="confirmDeleteOption({{ $option->id }})">
                                                    <i class="fa fa-trash"></i> حذف
                                                </button>
                                                <form id="delete-option-{{ $option->id }}"
                                                      action="{{ route('admin.survey.question.option.destroy', [$survey->id, $question->id, $option->id]) }}"
                                                      method="POST" class="d-none">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
        ScriptLoader::Alert(),
        ScriptLoader::JQueryUI(),
        ScriptLoader::Toast(),
    ]])
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.survey.index') }}');

            // Initialize sortable
            $('#sortable-options').sortable({
                handle: '.drag-handle',
                update: function (event, ui) {
                    let items = [];
                    $('#sortable-options tr').each(function (index) {
                        items.push({
                            id: $(this).data('id'),
                            order: index + 1
                        });
                    });

                    // Save the new order via AJAX
                    $.ajax({
                        url: '{{ route('admin.survey.question.option.reorder', [$survey->id, $question->id]) }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            items: items
                        },
                        success: function (response) {

                            $.toast({
                                heading: 'ترتیب گزینه‌ها با موفقیت ذخیره شد.',
                                text: response.message,
                                allowToastClose: false,
                                position: 'bottom-left',
                                hideAfter: 4400,
                                textAlign: 'right',
                                icon: 'success'
                            });
                            // Update displayed order numbers
                            $('#sortable-options tr').each(function (index) {
                                $(this).find('td:eq(2)').text(index + 1);
                            });
                        },
                        error: function () {
                            $.toast({
                                heading: 'خطا در ذخیره ترتیب سوالات.',
                                text: response.message,
                                allowToastClose: false,
                                position: 'bottom-left',
                                hideAfter: 4400,
                                textAlign: 'right',
                                icon: 'warning'
                            })
                        }
                    });
                }
            });
        });

        function confirmDeleteOption(id) {
            swal({
                title: 'آیا مطمئن هستید؟',
                text: "این گزینه حذف خواهد شد!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#ff0f3b',
                confirmButtonText: 'بله، حذف کن!',
                cancelButtonText: 'خیر، انصراف',
                closeOnConfirm: false
            }, function () {
                document.getElementById('delete-option-' + id).submit();
            });
        }
    </script>
@endsection
