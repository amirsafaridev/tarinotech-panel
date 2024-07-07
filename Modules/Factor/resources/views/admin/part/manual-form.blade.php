<div class="card">
    <div class="card-header">
        <h3 class="card-title">اطلاعات واریزی</h3>
        <div class="card-options">
            <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse">
                <i class="fal fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-12 col-md-6">
                @php
                    $manualPaymentDate = null;
                    if($factor?->manual?->payment_date){
                        $manualPaymentDate = verta($factor->manual->payment_date)->format('Y/m/d');
                    }
                @endphp
                <x-admin.input identify="manual_payment_date" title="تاریخ واریز" :is-date-picker="true" :old="$manualPaymentDate" :disabled="$isFreeze"/>
            </div>
            <div class="col-12 col-md-6 position-relative">
                <x-admin.input type="file" identify="manual_file" title="فایل" :disabled="$isFreeze"/>
                @if($factor?->manual?->file)
                    <a  class="btn btn-sm btn-success position-absolute top-0 end-0" href="{{ route('stream.read',$factor?->manual?->file) }}">دانلود</a>
                @endif
            </div>
        </div>

    </div>
</div>