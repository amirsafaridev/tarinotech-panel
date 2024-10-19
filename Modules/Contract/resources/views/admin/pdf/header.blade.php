<table style="width: 100%;border-bottom: 2px solid #192373">
    <tr>
        <td valign="center" style="padding: 10px">
            <img width="180" src="./sign/logo-header.jpg" alt="">
        </td>
        <td width="20%">
            <table>
                <tr>
                    <td style="color: #192373;font-weight: 600;font-size: 14px">تاریخ</td>
                    <td>{{ $model->project?->agreement_at?->toJalali()->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td style="color: #192373;font-weight: 600;font-size: 14px">شماره</td>
                    <td>{{ $model->signable?->id }}</td>

                </tr>
                <tr>
                    <td style="color: #192373;font-weight: 600;font-size: 14px">پیوست</td>
                    <td>
                        @if(!empty($model->project?->contract_attachment))
                            <span>دارد</span>
                        @else
                            <span>ندارد</span>
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
