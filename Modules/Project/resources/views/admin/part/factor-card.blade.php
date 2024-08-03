@php
    use \Modules\Factor\app\Enums\FactorStatus;
@endphp
<div class="card">
    <div class="card-header">
        <h3 class="card-title">فاکتور ها</h3>
        <div class="card-options">
            <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i
                        class="fal fa-chevron-up"></i></a>
        </div>
    </div>

    <div class="card-body">

        @if($project->factors->isNotEmpty())
            @can('PROJECT_PRICE_SHOW')
                <div class="row">
                    <div class="col-sm-6 col-md-4 col-lg-4 col-xl-3">
                        <div class="card bg-primary-gradient img-card box-primary-shadow">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="text-white">
                                        <h2 class="mb-0 number-font">{{ number_format($project->factors->sum('final_price')) }}</h2>
                                        <p class="text-white mb-0">مجموع کل فاکتور ها (ریال)</p>
                                    </div>
                                    <div class="ms-auto"><i class="fa fa-user-o text-white fs-30 me-2 mt-2"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-4 col-xl-3">
                        <div class="card bg-success-gradient img-card box-info-shadow">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="text-white">
                                        @php
                                            $totalPaid = number_format($project->factors->whereIn('status',[FactorStatus::Paid,FactorStatus::PaidManual])->sum('final_price'));
                                        @endphp
                                        <h2 class="mb-0 number-font">{{ $totalPaid }}</h2>
                                        <p class="text-white mb-0">مجموع پرداخت شده (ریال)</p>
                                    </div>
                                    <div class="ms-auto"><i class="fa fa-envelope-o text-white fs-30 me-2 mt-2"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-4 col-lg-4 col-xl-3">
                        <div class="card bg-danger-gradient img-card box-info-shadow">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="text-white">
                                        @php
                                            $totalNotPaid = number_format($project->factors->whereNotIn('status',[FactorStatus::Paid,FactorStatus::PaidManual])->sum('final_price'));
                                        @endphp
                                        <h2 class="mb-0 number-font">{{ $totalNotPaid }}</h2>
                                        <p class="text-white mb-0">مجموع پرداخت نشده (ریال)</p>
                                    </div>
                                    <div class="ms-auto"><i class="fa fa-envelope-o text-white fs-30 me-2 mt-2"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-4 col-lg-4 col-xl-3">
                        <div class="card bg-info-gradient img-card box-info-shadow">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="text-white">
                                        @php
                                            $totalTax = $project->price * $project->tax_rate;

                                        @endphp
                                        <h2 class="mb-0 number-font">{{ number_format($project->price + $totalTax ) }}</h2>
                                        <p class="text-white mb-0">مبلغ کل قرارداد + ارزش افزوده (ریال)</p>
                                    </div>
                                    <div class="ms-auto"><i class="fa fa-envelope-o text-white fs-30 me-2 mt-2"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            <table class="table">
                <thead>
                <tr>
                    <th>شناسه</th>
                    <th>عنوان</th>
                    <th>کارشناس فروش</th>
                    @can('PROJECT_PRICE_SHOW')
                        <th>مبلغ (ریال)</th>
                    @endcan
                    <th>درگاه</th>
                    <th>وضعیت</th>
                    <th>تاریخ پرداخت</th>
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
                        @can('PROJECT_PRICE_SHOW')
                            <td>{{ number_format($factor->final_price) }}</td>
                        @endcan
                        <td>
                            @if($factor->gateway)
                                {{ \Modules\Factor\app\Enums\PaymentGateway::getDescription($factor->gateway) }}
                            @endif
                        </td>
                        <td>
                            {!! factorStatusRender($factor->status,$factor->is_confirm) !!}
                        </td>
                        <td>
                            @if($factor->paid_at)
                                {{ $factor->paid_at->toJalali()->format(formatJalaliDate()) }}
                            @endif
                        </td>
                        <td>
                            {{ $factor->created_at->toJalali()->format(formatJalaliDateTime()) }}
                        </td>
                        <td>
                            <a class="btn btn-sm btn-primary"
                               href="{{ route('admin.factor.show',$factor->id) }}">نمایش</a>
                            <a class="btn btn-sm btn-warning"
                               href="{{ route('admin.factor.edit',$factor->id) }}">ویرایش</a>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        @endif
    </div>
</div>