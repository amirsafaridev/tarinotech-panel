<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $logTitle ?? 'لاگ ها' }}</h3>
        <div class="card-options">
            <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i
                        class="fal fa-chevron-up"></i></a>
        </div>
    </div>
    <div class="card-body pb-4">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>شناسه</th>
                    <th>نام کانال</th>
                    <th>رویداد</th>
                    <th>نوع کاربر</th>
                    <th>پروفایل</th>
                    <th>پراپرتی</th>
                    <th>تاریخ ایجاد</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($logs as $item)
                    <tr>
                        <td>{{ $item['id'] }}</td>
                        <td>{{ getEventName($item['log_name']) }}</td>
                        <td>{!! getEventType($item['event']) !!}</td>
                        @if($item['causer'])
                            <td>{{ getEventCauserType($item['causer_type']) }}</td>
                            <td>{!! getCauserProfile($item['causer']) !!}</td>
                        @else
                            <td>-</td>
                            <td>-</td>
                        @endif

                        @if(isset($itemsProperties) && $itemsProperties->isNotEmpty())
                            <td>{{ $itemsProperties->get($item['new_property'])->title ?? $item['new_property'] }}</td>
                        @else
                            <td>{{ $item['new_property'] }}</td>
                        @endif

                        <td>{{ verta($item['created_at'])->format(formatJalaliDateTime()) }}</td>
                        <td>
                            <a target="_blank" class="btn btn-info btn-sm" href="{{ route('admin.log.show',$item['id']) }}">نمایش</a>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>


