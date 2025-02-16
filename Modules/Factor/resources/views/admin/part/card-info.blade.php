<div class="card">
    <div class="card-header">
        <span class="bold">اطلاعات فاکتور</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <tbody>
                <tr>
                    <td>شناسه</td>
                    <td>{{ $factor->id }}</td>
                </tr>
                @if($factor->serial)
                    <tr>
                        <td>سریال</td>
                        <td>{{ $factor->serial }}</td>
                    </tr>
                @endif
                <tr>
                    <td>عنوان</td>
                    <td>{{ $factor->title }}</td>
                </tr>

                    <tr>
                        <td>درگاه</td>
                        <td>
                            @if($factor->gateway)
                                {{ \Modules\Factor\app\Enums\PaymentGateway::getDescription($factor->gateway) }}
                            @else
                                <span>-</span>
                            @endif
                        </td>
                    </tr>

                <tr>
                    <td>لینک پرداخت</td>
                    <td>{{ route('factor.factor.index',$factor->identify) }}</td>
                </tr>
                <tr>
                    <td>کارشناس</td>
                    <td>
                        <a href="{{ route('admin.admin.show',$factor->admin_id) }}">{{ $factor->admin->first_name }} {{ $factor->admin->last_name }}</a>
                    </td>
                </tr>

                <tr>
                    <td>شناسه یکتا</td>
                    <td>{{ $factor->identify }}</td>
                </tr>
                <tr>
                    <td>شناسه تراکنش</td>
                    <td>{{ $factor->transaction_id ?? 'بدونه شناسه' }}</td>
                </tr>
                <tr>
                    <td>قیمت نهایی (ریال)</td>
                    <td>{{ number_format($factor->final_price) }}</td>
                </tr>
                <tr>
                    <td>وضعیت</td>
                    <td>{{ \Modules\Factor\app\Enums\FactorStatus::getDescription($factor->status) }}</td>
                </tr>
                <tr>
                    <td>نوع فاکتور</td>
                    <td>
                    @if($factor->is_official || $factor->project?->user?->official_bill)
                        <span>فاکتور رسمی</span>
                    @else
                        <span>فاکتور غیر رسمی</span>
                    @endif
                    </td>
                </tr>
                <tr>
                    <td>تاریخ انقضاء</td>
                    <td>{{ verta($factor->expired_at)->format(formatJalaliDate()) }}</td>
                </tr>
                <tr>
                    <td>تاریخ پرداخت</td>
                    <td>{{ $factor->paid_at ? verta($factor->paid_at)->format(formatJalaliDateTime()) : 'پرداخت نشده' }}</td>
                </tr>
                <tr>
                    <td>تاریخ ایجاد</td>
                    <td>{{ verta($factor->created_at)->format(formatJalaliDateTime()) }}</td>
                </tr>

                <tr>
                    <td>پرینت</td>
                    <td>
                        <a class="btn btn-sm btn-info" target="_blank" href="{{ makeRouteContractPreview(\Modules\Factor\app\Models\Factor::class,$factor->id) }}">پرینت فاکتور</a>
                    </td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>
