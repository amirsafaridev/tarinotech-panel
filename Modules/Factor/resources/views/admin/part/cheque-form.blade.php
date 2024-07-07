<div class="card">
    <div class="card-header">
        <h3 class="card-title">اطلاعات چک</h3>
        <div class="card-options">
            <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse">
                <i class="fal fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-12 col-md-6">
                <x-admin.input identify="cheque_amount" title="مبلغ چک (ریال)" :old="$factor?->cheque?->amount" :disabled="$isFreeze"/>
            </div>
            <div class="col-12 col-md-6 position-relative">
                <x-admin.input type="file" identify="cheque_file" title="تصویر چک" :disabled="$isFreeze"/>
                @if($factor?->cheque?->cheque_file)
                    <a class="btn btn-sm btn-success position-absolute top-0 end-0" href="{{ route('stream.read',$factor?->cheque?->cheque_file) }}">دانلود</a>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6">
                <x-admin.input identify="cheque_identifier" title="شناسه صیاد" :old="$factor?->cheque?->cheque_identifier" :disabled="$isFreeze"/>
            </div>
            <div class="col-12 col-md-6">
                @php
                    $chequePaymentDate = null;
                    if($factor?->cheque?->payment_date){
                        $chequePaymentDate = verta($factor->cheque->payment_date)->format('Y/m/d');
                    }
                @endphp
                <x-admin.input identify="cheque_payment_date" title="تاریخ سر رسید" :is-date-picker="true" :old="$chequePaymentDate" :disabled="$isFreeze"/>
            </div>
        </div>

        <x-admin.checkbox identify="cheque_registered" :readonly="$isFreeze" description="ثبت چک در سامانه توسط مشتری انجام شده" :old="(bool)$factor?->cheque?->cheque_identifier"/>

    </div>
</div>