<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">آرشیو متن قرارداد</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">شناسه</th>
                    <th scope="col">نام کارشناس</th>
                    <th scope="col">زمان بازنشانی</th>
                    <th scope="col">علت تغییر</th>
                    <th scope="col">تاریخ ثبت</th>
                    <th scope="col">عملیات</th>
                </tr>
                </thead>
                <tbody>
                @forelse($package->contractHistories as $history)
                    <tr>
                        <td>{{ $history->id }}</td>
                        <td>{{ $history->admin->first_name }} {{ $history->admin->last_name }}</td>
                        <td>
                            {{ $history->restored_at ? verta($history->restored_at)->format(formatJalaliDate()) : '---' }}
                        </td>
                        <td>{{ $history->change_reason ?: 'ثبت نشده' }}</td>
                        <td>
                            {{ verta($history->created_at)->format(formatJalaliDate()) }}
                        </td>
                        <td>
                            <a href="{{ route('admin.package.edit', [$history->package_id,'restore'=>$history->id]) }}"
                               class="btn btn-sm btn-success"
                               title="بازنشانی به این نسخه">
                                بازگرداندن
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">تاریخچه‌ای یافت نشد</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
