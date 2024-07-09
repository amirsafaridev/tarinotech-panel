<div class="card">
    <div class="card-header">
        <span class="bold">اطلاعات چک</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <tbody>
                <tr>
                    <td>مبلغ چک</td>
                    <td>{{ number_format($factor->cheque->amount) }} ریال</td>
                </tr>

                <tr>
                    <td>تاریخ سر رسید</td>
                    <td>{{ verta($factor->cheque->payment_date )->format(formatJalaliDate()) }}</td>
                </tr>

                <tr>
                    <td>شناسه صیاد</td>
                    <td>{{ $factor->cheque->cheque_identifier }}</td>
                </tr>

                <tr>
                    <td>ثبت چک در سامانه توسط مشتری انجام شده</td>
                    <td>
                        @include('admin.partial.bool_badge',['value'=>$factor->cheque->cheque_registered])
                    </td>
                </tr>

                @if($factor->cheque->cheque_file)
                    <tr>
                        <td>تصویر چک</td>
                        <td>
                            <a target="_blank" href="{{ route('stream.read',$factor->cheque->cheque_file) }}">دانلود</a>
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
