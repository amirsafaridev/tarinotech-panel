@php
    use App\Enums\Assets\ScriptLoader;use App\Enums\Assets\StyleLoader;use Modules\Survey\app\Enums\Database\QuestionTypeEnum;
@endphp
@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
        StyleLoader::JQueryUI(),
        StyleLoader::Toast(),
   ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.index') }}">نظرسنجی‌ها</a></li>
                <li class="breadcrumb-item active">سوالات نظرسنجی</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title">
                        <h4 class="mb-0">{{ $survey->title }}</h4>
                        <p class="text-muted mb-0 fs-12">{{ $survey->description }}</p>
                    </div>
                    <div>
                        <a class="btn btn-primary" href="{{ route('admin.survey.question.create', $survey->id) }}">افزودن
                            سوال جدید</a>
                        <a class="btn btn-secondary" href="{{ route('admin.survey.edit', $survey->id) }}">بازگشت به
                            نظرسنجی</a>
                    </div>
                </div>
                <div class="card-body">
                    @include('admin.partial.message')

                    @if($questions->isEmpty())
                        <div class="alert alert-info">
                            هنوز سوالی برای این نظرسنجی تعریف نشده است. برای افزودن سوال روی دکمه "افزودن سوال جدید"
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
                                    <th>متن سوال</th>
                                    <th>نوع سوال</th>
                                    <th>اجباری</th>
                                    <th>تعداد گزینه‌ها</th>
                                    <th>عملیات</th>
                                </tr>
                                </thead>
                                <tbody id="sortable-questions">
                                @foreach($questions as $question)
                                    <tr data-id="{{ $question->id }}">
                                        <td>
                                            <div class="drag-handle cursor-move">
                                                <i class="fa fa-bars"></i>
                                            </div>
                                        </td>
                                        <td>{{ $question->order }}</td>
                                        <td>{{ $question->id }}</td>
                                        <td>{{ $question->question_text }}</td>
                                        <td>
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
                                        </td>
                                        <td>
                                            @if($question->is_required)
                                                <span class="badge bg-success">بله</span>
                                            @else
                                                <span class="badge bg-secondary">خیر</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(in_array($question->question_type, ['single_choice', 'multiple_choice']))
                                                {{ $question->options_count ?? $question->options->count() }}
                                                <a href="{{ route('admin.survey.question.option.index', [$survey->id, $question->id]) }}"
                                                   class="btn btn-sm btn-outline-primary ms-2">
                                                    <i class="fa fa-list"></i> گزینه‌ها
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('admin.survey.question.edit', [$survey->id, $question->id]) }}"
                                                   class="btn btn-sm btn-warning">
                                                    <i class="fa fa-edit"></i> ویرایش
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="confirmDeleteQuestion({{ $question->id }})">
                                                    <i class="fa fa-trash"></i> حذف
                                                </button>
                                                <form id="delete-question-{{ $question->id }}"
                                                      action="{{ route('admin.survey.question.destroy', [$survey->id, $question->id]) }}"
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
            $('#sortable-questions').sortable({
                handle: '.drag-handle',
                update: function (event, ui) {
                    let items = [];
                    $('#sortable-questions tr').each(function (index) {
                        items.push({
                            id: $(this).data('id'),
                            order: index + 1
                        });
                    });

                    // Save the new order via AJAX
                    $.ajax({
                        url: '{{ route('admin.survey.question.reorder', $survey->id) }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            items: items
                        },
                        success: function (response) {

                            $.toast({
                                heading: 'ترتیب سوالات با موفقیت ذخیره شد.',
                                text: response.message,
                                allowToastClose: false,
                                position: 'bottom-left',
                                hideAfter: 4400,
                                textAlign: 'right',
                                icon: 'success'
                            });

                            $('#sortable-questions tr').each(function (index) {
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

        function confirmDeleteQuestion(id) {
            swal({
                title: 'آیا مطمئن هستید؟',
                text: "این سوال و تمام گزینه‌های آن حذف خواهند شد!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#ff0f3b',
                confirmButtonText: 'بله، حذف کن!',
                cancelButtonText: 'خیر، انصراف',
                closeOnConfirm: false
            }, function () {
                document.getElementById('delete-question-' + id).submit();
            });
        }
    </script>
@endsection
