@php
    // Ensure we have a ticket detail object, can be passed directly or via chat->ticketDetail
    $ticketDetail = $ticketDetail ?? (isset($chat) ? $chat->ticketDetail : null);
    $chat = $chat ?? ($ticketDetail ? $ticketDetail->chat : null);
@endphp

@if($ticketDetail && $chat)
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">اطلاعات تیکت</h3>
        @if(isset($showEditButton) && $showEditButton)
            <div>
                <a href="{{ route('admin.ticket.edit', $chat->id) }}" class="btn btn-sm btn-primary">
                    <i class="fe fe-edit"></i> ویرایش
                </a>
            </div>
        @endif
    </div>
    <div class="card-body p-0">
        <div class="ticket-title p-3 border-bottom">
            <h5 class="mb-1">{{ $chat->title }}</h5>
            <span class="badge bg-primary">#{{ $ticketDetail->id }}</span>
        </div>
        
        <div class="ticket-info p-3">
            <div class="table-responsive">
                <table class="table table-sm m-0">
                    <tbody>
                        <tr>
                            <td class="fw-bold" width="40%">
                                <i class="fe fe-user text-muted me-1"></i> کاربر
                            </td>
                            <td>{{ optional($chat->users->where('user_type', '!=', \Modules\Admin\app\Models\Admin::class)->first()->user)->fullname }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">
                                <i class="fe fe-tag text-muted me-1"></i> موضوع
                            </td>
                            <td>{{ optional($ticketDetail->subject)->title ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">
                                <i class="fe fe-calendar text-muted me-1"></i> تاریخ ایجاد
                            </td>
                            <td>{{ $chat->created_at->toJalali()->format(formatJalaliDate()) }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">
                                <i class="fe fe-clock text-muted me-1"></i> آخرین پاسخ
                            </td>
                            <td>{{ optional($ticketDetail->last_response_at)->toJalali()->format(formatJalaliDate()) ?? 'بدون پاسخ' }}</td>
                        </tr>
                        @if($ticketDetail->assigned_to)
                        <tr>
                            <td class="fw-bold">
                                <i class="fe fe-user-check text-muted me-1"></i> پشتیبان
                            </td>
                            <td>{{ optional($ticketDetail->assignedAdmin)->fullname ?? '-' }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-between mt-3 p-3 bg-light">
                <div>
                    <small class="d-block text-muted mb-1">وضعیت</small>
                    <span class="badge" style="background-color: {{ optional($ticketDetail->status)->color ?? '#999' }}">
                        {{ optional($ticketDetail->status)->name ?? '-' }}
                    </span>
                </div>
                <div>
                    <small class="d-block text-muted mb-1">اولویت</small>
                    <span class="badge" style="background-color: {{ optional($ticketDetail->priority)->color ?? '#999' }}">
                        {{ optional($ticketDetail->priority)->name ?? '-' }}
                    </span>
                </div>
            </div>

            @if(isset($showRating) && $showRating && $ticketDetail->rating)
            <div class="rating-container mt-3 p-3 border-top">
                <small class="d-block text-muted mb-1">امتیاز کاربر</small>
                <div class="rating-stars">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fe fe-star {{ $i <= $ticketDetail->rating ? 'text-warning' : 'text-muted' }}"></i>
                    @endfor
                    <span class="ms-2 badge bg-warning text-dark">{{ $ticketDetail->rating }}/5</span>
                </div>
            </div>
            @endif

            @if(isset($showActions) && $showActions)
            <div class="actions-container mt-3 pt-3 border-top">
                <a href="{{ route('admin.ticket.manage', $chat->id) }}" class="btn btn-primary btn-sm w-100">
                    <i class="fe fe-message-square me-1"></i> مشاهده تیکت
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@else
<div class="alert alert-warning">
    اطلاعات تیکت یافت نشد
</div>
@endif 