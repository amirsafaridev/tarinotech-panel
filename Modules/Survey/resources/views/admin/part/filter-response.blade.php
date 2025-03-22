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
            'survey_responses.created_at-desc'=>'جدیدترین ها',
            'survey_responses.created_at-asc'=>'قدیمی ترین ها',
            'answers_count-desc'=>'بیشترین پاسخ',
            'answers_count-asc'=>'کمترین پاسخ',
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
