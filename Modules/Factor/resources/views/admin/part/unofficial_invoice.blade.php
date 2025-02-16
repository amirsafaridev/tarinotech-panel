<table class="print-main-table">
    <tbody>
    <tr class="header-row-bg">
        <td colspan="3">
            <p class="text-center p-title">صورت حساب الکترونیکی فروش خدمات</p>
        </td>
        <td colspan="1">
            <p class="text-center p-title">{{ $factor->created_at->toJalali()->format(formatJalaliDate()) }}</p>
        </td>
    </tr>

    <tr>
        <td colspan="4" class="header-row-bg">
            <p class="text-right p-title">مشخصات خریدار</p>
        </td>
    </tr>
    <tr>

        <td width="25%">
            <p>
                <span>نام :</span>
                <span>{{ $factor->project->user->first_name }} {{ $factor->project->user->last_name }}</span>
            </p>
        </td>

        <td width="25%">
            <p>
                <span>شماره / شماره ملی :</span>
                @if($factor->meta)
                    <span>-</span>
                @else
                    <span>{{ $factor->project->user->national_id }}</span>
                @endif
            </p>
        </td>
        <td width="25%">
            <p>
                <span>شماره تماس :</span>
                <span>{{ $factor->project->user->mobile }}</span>
            </p>
        </td>
        <td width="25%">
            <p>
                <span>کدپستی :</span>
                @if($factor->meta)
                    <span>-</span>
                @else
                    <span>{{ $factor->project->user->address?->postal_code }}</span>
                @endif
            </p>
        </td>
    </tr>


    <tr>
        <td colspan="4" class="header-row-bg">
            <p class="text-right p-title">مشحصات کالا / خدمت مورد معامله</p>
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <table class="print-main-table inner-table">
                <tbody>
                <tr>
                    <td>ردیف</td>
                    <td>شرح کالا / خدمات</td>
                    <td>واحد اندازه گیری</td>
                    <td>تعداد / مقدار</td>
                    <td>مبلغ واحد (ریال)</td>
                    <td>نوع ارز</td>
                    <td>مبلغ تخفیف</td>
                    <td>نوع مالیات بر ارزش افزوده</td>
                    <td>مبلغ مالیات بر ارزش افزوده</td>
                    <td>مبلغ کالا / خدمات</td>
                </tr>
                @php
                    $totalDiscount = 0;
                    $totalTaxAmount = 0;
                    $totalPrice = 0;
                @endphp

                @if($factor->items->isNotEmpty())
                    @foreach($factor->items as $item)
                        @php
                            $totalDiscount += $item->discount;
                            $totalTaxAmount += $item->tax_amount;
                            $totalPrice += $item->final_price;
                        @endphp
                        <tr>
                            <td>{{ $loop->index + 1}}</td>
                            <td>{{ $item->title }}</td>
                            <td>عدد</td>
                            <td>1</td>
                            <td>{{ number_format($item->price) }}</td>
                            <td>ریال (ایران)</td>
                            <td>{{ number_format($item->discount) }}</td>
                            <td>{{ config('factor.tax') * 100 }} درصد</td>
                            <td>{{ number_format($item->tax_amount) }}</td>
                            <td>{{ number_format($item->final_price) }}</td>
                        </tr>
                    @endforeach
                @endif

                <tr>
                    <td colspan="6">جمع کل</td>
                    <td class="f-bold">{{ number_format($totalDiscount) }}</td>
                    <td class="f-bold">-</td>
                    <td class="f-bold">{{ number_format($totalTaxAmount) }}</td>
                    <td class="f-bold">{{ number_format($totalPrice) }}</td>
                </tr>

                <tr>
                    <td colspan="6">مبلغ نهایی</td>
                    <td colspan="4" class="f-bold">{{ number_format($totalPrice) }}</td>
                </tr>

                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>