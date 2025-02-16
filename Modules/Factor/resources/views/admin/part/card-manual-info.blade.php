<div class="card">
    <div class="card-header">
        <span class="bold">اطلاعات پرداخت دستی</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <tbody>
                <tr>
                    <td>تاریخ پرداخت</td>
                    <td>{{ verta($factor->manual->payment_date )->format(formatJalaliDateTime()) }}</td>
                </tr>
                @if($factor->manual->file)
                    <tr>
                        <td>فیش واریزی</td>
                        <td>
                            <a target="_blank" href="{{ route('stream.read',$factor->manual->file) }}">دانلود</a>
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
