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
            identify="from_date"
            title="از تاریخ ایجاد"
            :is-date-picker="true"
            :old="request('from_date')"
            :is-small="true"
        />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.input
            identify="to_date"
            title="تا تاریخ ایجاد"
            :is-date-picker="true"
            :old="request('to_date')"
            :is-small="true"
        />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.input
            identify="start_date"
            title="تاریخ شروع"
            :is-date-picker="true"
            :old="request('start_date')"
            :is-small="true"
        />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.input
            identify="end_date"
            title="تاریخ پایان"
            :is-date-picker="true"
            :old="request('end_date')"
            :is-small="true"
        />
    </div>

    @php
        $statusItems = [
            '' => 'همه وضعیت‌ها',
            '1' => 'فعال',
            '0' => 'غیرفعال',
        ];

        $requiresAuthItems = [
            '' => 'همه حالت‌های احراز هویت',
            '1' => 'نیاز به احراز هویت',
            '0' => 'بدون نیاز به احراز هویت',
        ];

        $orderItems=[
            'surveys.created_at-desc'=>'جدیدترین ها',
            'surveys.created_at-asc'=>'قدیمی ترین ها',
            'questions_count-desc'=>'بیشترین سوال',
            'questions_count-asc'=>'کمترین سوال',
            'responses_count-desc'=>'بیشترین پاسخ',
            'responses_count-asc'=>'کمترین پاسخ',
            'surveys.start_date-desc'=>'تاریخ شروع (نزولی)',
            'surveys.start_date-asc'=>'تاریخ شروع (صعودی)',
            'surveys.end_date-desc'=>'تاریخ پایان (نزولی)',
            'surveys.end_date-asc'=>'تاریخ پایان (صعودی)',
        ];
    @endphp

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.select-simple
            identify="status"
            title="وضعیت"
            :items="$statusItems"
            :old="request('status')"
            :is-small="true"
        />
    </div>

    <div class="col-12 col-md-3 col-xl-2">
        <x-admin.select-simple
            identify="requires_auth"
            title="احراز هویت"
            :items="$requiresAuthItems"
            :old="request('requires_auth')"
            :is-small="true"
        />
    </div>

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
