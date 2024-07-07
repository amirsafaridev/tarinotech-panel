@php
    $logoPath = 'private/sign/factor-logo.jpg';
    $logoContent = Storage::get($logoPath);
    $logoBase64 = 'data:image/jpg;base64,' . base64_encode($logoContent);

    $signPath = 'private/sign/sign-real.png';
    $signContent = Storage::get($signPath);
    $signBase64 = 'data:image/png;base64,' . base64_encode($signContent);
@endphp
<table class="print-main-table">
    <tbody>
    <tr>
        <td>
            <img width="120px" src="{{ $logoBase64 }}" alt="">
        </td>
        <td colspan="2" align="center">
            <p class="text-center p-title">صورت حساب الکترونیکی فروش خدمات شرکت برخط نگاران جهان ارتباط</p>

        </td>
        <td align="center">
            <p>شماره فاکتور : ------------</p>
        </td>
    </tr>

    <tr class="header-row-bg">
        <td colspan="3">
            <p class="text-right p-title">مشخصات فروشنده</p>
        </td>
        <td align="center">
            <p class="text-center p-title">{{ $factor->created_at->toJalali()->format(formatJalaliDate()) }}</p>
        </td>
    </tr>
    <tr>
        <td width="25%">
            <p>
                <span>شماره اقتصادی :</span>
                <span>411513734335</span>
            </p>
        </td>
        <td width="25%">
            <p>
                <span>شماره ثبت / شماره ملی :</span>
                <span>489741</span>
            </p>
        </td>
        <td width="25%">
            <p>
                <span>شناسه ملی :</span>
                <span>14005743726</span>
            </p>
        </td>
        <td width="25%">
            <p>
                <span>کدپستی :</span>
                <span>1911613635</span>
            </p>
        </td>
    </tr>
    <tr>
        <td width="50%" colspan="2">
            <p>
                <span>نام شخص حقیقی/حقوقی :</span>
                <span>برخط نگاران جهان ارتباط</span>
            </p>
        </td>
        <td width="50%" colspan="2">
            <p>
                <span>شماره تماس :</span>
                <span>02178513</span>
            </p>
        </td>
    </tr>
    <tr>
        <td width="25%">
            <p>
                <span>شماره پرونده گمرکی :</span>
                <span>-</span>
            </p>
        </td>
        <td width="25%">
            <p>
                <span>کد گمرک محل اظهار :</span>
                <span>-</span>
            </p>
        </td>
        <td width="50%" colspan="2">
            <p>
                <span>شناسه یکتای ثبت قرارداد :</span>
                <span>-</span>
            </p>
        </td>

    </tr>

    <tr>
        <td colspan="4">
            <p>آدرس</p>
            <p>تهران، شهر تهران، داوودیه، خیابان شهید امیر سهیل تبریزیان ، خیابان آسایی، پلاک ۲ طبقه ۳ واحد ۱۲</p>
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
                <span>شماره اقتصادی :</span>
                <span>-</span>
            </p>
        </td>
        <td width="25%">
            <p>
                <span>شماره / شماره ملی :</span>
                <span>{{ $factor->project->user->national_id }}</span>
            </p>
        </td>
        <td width="25%">
            <p>
                <span>شناسه ملی :</span>
                <span>{{ $factor->project->user->company?->identify }}</span>
            </p>
        </td>
        <td width="25%">
            <p>
                <span>کدپستی :</span>
                <span>{{ $factor->project->user->address?->postal_code }}</span>
            </p>
        </td>
    </tr>
    <tr>
        <td width="50%" colspan="2">
            <p>
                <span>نام شخص {{ \Modules\User\app\Enums\PersonType::getDescription($factor->project->user->person_type) }} :</span>
                <span>{{ $factor->project->user->first_name }} {{ $factor->project->user->last_name }}</span>
            </p>
        </td>
        <td width="50%" colspan="2">
            <p>
                <span>نام بنگاه اقتصادی :</span>
                <span>{{ $factor->project->user->company?->name }}</span>
            </p>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <p>آدرس</p>
            <p>{{ $factor->project->user->address?->address }}</p>
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <p class="text-right">مشحصات کالا / خدمت مورد معامله</p>
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <table class="print-main-table inner-table" style="width: 100%;">
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

    <tr>
        <td colspan="2" valign="center">
            <span  class="text-center p-title">مهر و امضا فروشنده</span>
            <img width="120px" style="float: right;margin-bottom: -40px;margin-right: 40px" src="{{ $signBase64 }}" alt="">
        </td>
        <td colspan="2" style="padding: 40px">
            <p  class="text-center p-title">مهر و امضا خریدار</p>
        </td>
    </tr>
    </tbody>
</table>