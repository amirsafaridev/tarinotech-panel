<div class="row mb-4">
    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.input
                identify="search"
                title="جستجو"
                :old="request('search')"
                :is-small="true"
        />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.input
                identify="admin"
                title="پشتیبان"
                :is-small="true"
                :old="request('admin')"
        />
    </div>

    @php
        $statuses = \Modules\Ticket\app\Models\TicketStatus::orderBy('order')->get();
        $statusItems = $statuses->pluck('name', 'id')->toArray();
    @endphp

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.select-simple
                identify="status_id"
                title="وضعیت"
                :items="$statusItems"
                :is-small="true"
                :old="request('status_id')"
        />
    </div>

    @php
        $priorities = \Modules\Ticket\app\Models\TicketPriority::orderBy('level')->get();
        $priorityItems = $priorities->pluck('name', 'id')->toArray();
    @endphp

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.select-simple
                identify="priority_id"
                title="اولویت"
                :items="$priorityItems"
                :is-small="true"
                :old="request('priority_id')"
        />
    </div>

    @php
        $subjects = \Modules\Ticket\app\Models\TicketSubject::get();
        $subjectItems = $subjects->pluck('title', 'id')->toArray();
    @endphp

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.select-simple
                identify="subject_id"
                title="موضوع"
                :items="$subjectItems"
                :is-small="true"
                :old="request('subject_id')"
        />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        @php
            $dateTypeItems = [
                'ticket_details.created_at'=>'تاریخ ایجاد',
                'ticket_details.last_response_at'=>'تاریخ آخرین پاسخ',
                'ticket_details.closed_at'=>'تاریخ بسته شدن',
            ];
        @endphp
        <x-admin.select-simple
                identify="date_column"
                title="فیلد تاریخ"
                :items="$dateTypeItems"
                :old="request('date_column')"
                :is-small="true"
        />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.input
                identify="from_date"
                title="از تاریخ"
                :is-date-picker="true"
                :old="request('from_date')"
                :is-small="true"
        />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.input
                identify="to_date"
                title="تا تاریخ"
                :is-date-picker="true"
                :old="request('to_date')"
                :is-small="true"
        />
    </div>

    @php
        $orderItems=[
            'ticket_details.id-desc'=>'جدیدترین',
            'ticket_details.id-asc'=>'قدیمی ترین',
            'ticket_details.last_response_at-desc'=>'آخرین پاسخ (نزولی)',
            'ticket_details.last_response_at-asc'=>'آخرین پاسخ (صعودی)',
            'ticket_priorities.level-desc'=>'اولویت (نزولی)',
            'ticket_priorities.level-asc'=>'اولویت (صعودی)',
        ];
    @endphp

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.select-simple
                identify="sort"
                title="مرتب سازی"
                :items="$orderItems"
                :old="request('sort')"
                :is-small="true"
        />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <button class="btn btn-primary btn-sm">فیلتر</button>
    </div>
</div> 