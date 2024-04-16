<div class="card">
    <div class="card-header">
        <h3 class="card-title">فاکتور ها</h3>
        <div class="card-options">
            <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i
                        class="fal fa-chevron-up"></i></a>
        </div>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
            <tr>
                <th>شناسه</th>
                <th>عنوان</th>
                <th>کارشناس</th>
                <th>مبلغ (ریال)</th>
                <th>وضعیت</th>
                <th>مهلت پرداخت</th>
                <th>ایجاد</th>
                <th>عملیات</th>
            </tr>
            </thead>
            <tbody>

            @foreach($project->factors as $factor)
                <tr>
                    <td>{{ $factor->id }}</td>
                    <td>{{ $factor->title }}</td>
                    <td>
                        <a href="{{ route('admin.admin.show',$factor->admin_id) }}">{{ $factor->admin->first_name }} {{ $factor->admin->last_name }}</a>
                    </td>
                    <td>{{ number_format($factor->final_price) }}</td>
                    <td>{!! factorStatusRender($factor->status) !!}</td>
                    <td>
                        @if($factor->expired_at)
                            {{ $factor->expired_at->toJalali()->format(formatJalaliDate()) }}
                        @endif
                    </td>
                    <td>
                        {{ $factor->created_at->toJalali()->format(formatJalaliDateTime()) }}
                    </td>
                    <td>
                        <a class="btn btn-sm btn-primary" href="{{ route('admin.factor.show',$factor->id) }}">نمایش</a>
                        <a class="btn btn-sm btn-warning" href="{{ route('admin.factor.show',$factor->id) }}">ویرایش</a>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
</div>