@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.ticket.index') }}">تیکت ها</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">اطلاعات تیکت</h3>
                    <div class="card-options">
                        <a href="{{ route('admin.ticket.edit', $chat->id) }}" class="btn btn-sm btn-primary">
                            <i class="fe fe-edit"></i> ویرایش تیکت
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td>شناسه</td>
                                <td>{{ $ticketDetail->id }}</td>
                            </tr>
                            <tr>
                                <td>عنوان</td>
                                <td>{{ $chat->title }}</td>
                            </tr>
                            <tr>
                                <td>کاربر</td>
                                <td>
                                    @foreach($chat->users as $chatUser)
                                        @if($chatUser->user_type !== \Modules\Admin\app\Models\Admin::class)
                                            {{ optional($chatUser->user)->first_name }} {{ optional($chatUser->user)->last_name }}
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td>موضوع</td>
                                <td>{{ $ticketDetail->subject->title }}</td>
                            </tr>
                            @if($chat->project)
                            <tr>
                                <td>پروژه</td>
                                <td>
                                    <a href="{{ route('admin.project.manage', $chat->project->id) }}">
                                        {{ $chat->project->title }} ({{ $chat->project->domain }})
                                    </a>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td>وضعیت</td>
                                <td>
                                    <span class="badge" style="background-color: {{ $ticketDetail->status->color }}">
                                        {{ $ticketDetail->status->name }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>اولویت</td>
                                <td>
                                    <span class="badge" style="background-color: {{ $ticketDetail->priority->color }}">
                                        {{ $ticketDetail->priority->name }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>پشتیبان</td>
                                <td>
                                    @if($ticketDetail->assigned_to)
                                        {{ $ticketDetail->assignedAdmin->first_name }} {{ $ticketDetail->assignedAdmin->last_name }}
                                    @else
                                        <span class="badge bg-warning">تعیین نشده</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>آخرین پاسخ</td>
                                <td>{{ optional($ticketDetail->last_response_at)->toJalali()->format(formatJalaliDateTime()) ?? 'بدون پاسخ' }}</td>
                            </tr>
                            @if($ticketDetail->closed_at)
                                <tr>
                                    <td>تاریخ بسته شدن</td>
                                    <td>{{ $ticketDetail->closed_at->toJalali()->format(formatJalaliDateTime()) }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td>امتیاز</td>
                                <td>{{ $ticketDetail->rating ? $ticketDetail->rating . '/5' : 'ثبت نشده' }}</td>
                            </tr>
                            @if($ticketDetail->rating_comment)
                                <tr>
                                    <td>نظر کاربر</td>
                                    <td>{{ $ticketDetail->rating_comment }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td>تاریخ ایجاد</td>
                                <td>{{ $ticketDetail->created_at->toJalali()->format(formatJalaliDateTime()) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">گفتگو</h3>
                </div>
                <div class="card-body chat-container">
                    <div class="chat-messages" id="chatMessages">
                        @foreach($chat->messages as $message)
                            <div class="message @if($message->user_type === \Modules\Admin\app\Models\Admin::class) admin-message @else user-message @endif">
                                <div class="message-header">
                                    <strong>
                                        @if($message->user_type === \Modules\Admin\app\Models\Admin::class)
                                            {{ optional($message->user)->first_name }} {{ optional($message->user)->last_name }} (پشتیبان)
                                        @else
                                            {{ optional($message->user)->first_name }} {{ optional($message->user)->last_name }}
                                        @endif
                                    </strong>
                                    <small>{{ $message->created_at->toJalali()->format(formatJalaliDateTime()) }}</small>
                                </div>
                                <div class="message-content">
                                    {!! nl2br(e($message->content)) !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
    <style>
        .chat-container {
            height: 500px;
            overflow-y: auto;
        }
        .chat-messages {
            display: flex;
            flex-direction: column;
        }
        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            max-width: 80%;
        }
        .user-message {
            background-color: #f0f2f5;
            align-self: flex-start;
        }
        .admin-message {
            background-color: #e3effd;
            align-self: flex-end;
        }
        .message-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 12px;
        }
        .message-content {
            word-break: break-word;
        }
    </style>
@endsection 