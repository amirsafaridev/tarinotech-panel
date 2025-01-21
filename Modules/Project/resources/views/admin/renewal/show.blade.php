@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
        \App\Enums\Assets\StyleLoader::Toast(),
        \App\Enums\Assets\StyleLoader::Alert(),
        \App\Enums\Assets\StyleLoader::Datepicker(),
    ]])
@endsection
@section('content')
    <div class="page-header">
        <div class="d-flex gap-2">
            <h1 class="page-title">لیست تمدید ها - نمایش</h1>
        </div>

        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.renewal.index') }}">لیست تمدید ها</a></li>
                <li class="breadcrumb-item active">نمایش</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-3">
            @include('admin.partial.message')
        </div>

        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">اطلاعات پروژه</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle">
                            <thead>
                            <tr>
                                <th>شناسه پروژه</th>
                                <th>عنوان پروژه</th>
                                <th>دامنه</th>
                                <th>کارشناس</th>
                                <th>نوع</th>
                                <th>تاریخ قرارداد</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{{ $projectRenewal->project_id ?? 'نامشخص' }}</td>
                                <td>
                                    @if($projectRenewal->project && $projectRenewal->project->title)
                                        <a href="{{ route('admin.project.manage', $projectRenewal->project_id) }}" class="text-decoration-none">
                                            {{ $projectRenewal->project->title }}
                                        </a>
                                    @else
                                        <span class="text-muted">عنوان نامشخص</span>
                                    @endif
                                </td>
                                <td>{{ $projectRenewal->project->domain ?? 'نامشخص' }}</td>
                                <td>
                                    @if($projectRenewal->project?->admin)
                                        {{ $projectRenewal->project->admin->first_name ?? '' }}
                                        {{ $projectRenewal->project->admin->last_name ?? '' }}
                                    @else
                                        <span class="text-muted">نامشخص</span>
                                    @endif
                                </td>
                                <td>
                                    @if($projectRenewal->project?->base_id && $projectRenewal->project?->type)
                                        {{ \Modules\Project\app\Enums\ProjectBase::getDescription($projectRenewal->project->base_id) }}
                                        -
                                        {{ $projectRenewal->project->type->title }}
                                    @else
                                        <span class="text-muted">نامشخص</span>
                                    @endif
                                </td>
                                <td>
                                    @if($projectRenewal->project?->agreement_at)
                                        {{ $projectRenewal->project->agreement_at->toJalali()->format(formatJalaliDate()) }}
                                    @else
                                        <span class="text-muted">نامشخص</span>
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">اطلاعات مالی</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle">
                            <thead>
                            <tr>
                                <th>مبلغ قرارداد (ریال)</th>
                                <th>مبلغ فعلی پکیج (ریال)</th>
                                <th>مبلغ براساس پکیج (ریال)</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    @if($projectRenewal->project_price)
                                        {{ number_format($projectRenewal->project_price) }}
                                    @else
                                        <span class="text-muted">0</span>
                                    @endif
                                </td>
                                <td>
                                    @if($projectRenewal->package_price)
                                        {{ number_format($projectRenewal->package_price) }}
                                    @else
                                        <span class="text-muted">0</span>
                                    @endif
                                </td>
                                <td>
                                    @if($projectRenewal->package_calculated_price)
                                        {{ number_format($projectRenewal->package_calculated_price) }}
                                    @else
                                        <span class="text-muted">0</span>
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">امکانات جانبی</h5>
                    <a class="btn btn-success btn-sm" href="{{ route('admin.project.renewal.facility.create',['project_renewal_id'=>$projectRenewal->id]) }}">ایجاد</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr class="text-center">
                                <th class="fw-bold">عنوان امکان</th>
                                <th class="fw-bold">قیمت ایجاد</th>
                                <th class="fw-bold">تاریخ ایجاد</th>
                                <th class="fw-bold">سیکل کاری تمدید</th>
                                <th class="fw-bold">سیکل مالی تمدید</th>
                                <th class="fw-bold">مبلغ نهایی</th>
                                <th class="fw-bold">توضیحات</th>
                                <th class="fw-bold">عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($projectRenewal->facilities->isNotEmpty())
                                @foreach($projectRenewal->facilities as $facility)
                                    <tr class="tr-row align-middle" data-id="{{ $facility->id }}">
                                        <td class="align-middle">
                                            <span>{{ $facility->facility->title }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <x-admin.select-enum
                                                identify="price_{{ $facility->id }}"
                                                :enum-class="\App\Enums\Database\Facility\PriceType::class"
                                                :old="$facility->price_type"/>
                                            <input type="text" class="form-control mt-2 custom-price-input" placeholder="مبلغ را وارد کنید" value="{{ $facility->price_value }}">
                                        </td>
                                        <td class="align-middle">{{ $facility->created_at->toJalali()->format(formatJalaliDate()) }}</td>

                                        <td class="align-middle">
                                            <x-admin.select-enum identify="work_{{ $facility->id }}"
                                                                 :enum-class="\App\Enums\Database\Facility\WorkCycle::class"
                                                                 :old="$facility->work_cycle"/>
                                            @if($facility->work_cycle === \App\Enums\Database\Facility\WorkCycle::InputDate)
                                                <input type="text" data-jdp class="form-control mt-2 custom-work-input" value="{{ $facility->work_cycle_value->toJalali()->format('Y/m/d') }}">
                                            @else
                                                <input type="text" data-jdp class="form-control mt-2 custom-work-input" value="{{ $facility->work_cycle_value }}">
                                            @endif
                                        </td>

                                        <td class="align-middle">
                                            <x-admin.select-enum identify="financial_{{ $facility->id }}"
                                                                 :enum-class="\App\Enums\Database\Facility\FinancialCycle::class"
                                                                 :old="$facility->financial_cycle"/>

                                            <input type="text" class="form-control mt-2 custom-financial-input" placeholder="مبلغ را وارد کنید" value="{{ $facility->financial_cycle_value }}">
                                        </td>
                                        <td class="align-middle text-center"> {{  number_format($facility->calculated_price)  }}</td>
                                        <td>
                                            <x-admin.textarea  identify="description_{{ $facility->id }}"
                                                               :old="$facility->description"
                                                                :rows="2"/>
                                        </td>
                                        <td class="align-middle">
                                            <button type="button" class="btn btn-block btn-sm btn-success">ثبت</button>
                                            <button type="button" class="btn btn-block btn-sm btn-danger">حذف</button>
                                        </td>
                                    </tr>

                                @endforeach

                            @else
                                <tr>
                                    <td colspan="8" class="text-center py-4">هیچ موردی یافت نشد</td>
                                </tr>

                            @endif
                            <tr>
                                <td colspan="8" class="align-middle">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center gap-2">
                                            <span>مبلغ نهایی:</span>
                                            <span class="font-weight-bold">
                                                @if($projectRenewal->facilities && $projectRenewal->facilities->isNotEmpty())
                                                    {{ number_format($projectRenewal->facilities->sum('calculated_price') ?? 0) }}
                                                @else
                                                    0
                                                @endif
                                            </span>
                                            <span>ریال</span>
                                        </div>
                                        <button
                                            type="button"
                                            id="generate-invoice"
                                            class="btn btn-lg btn-success"
                                            @if(!$projectRenewal->facilities || $projectRenewal->facilities->isEmpty())
                                                disabled
                                            @endif
                                        >
                                            <i class="fas fa-file-invoice me-2"></i>
                                            صدور نهایی فاکتور
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::Toast(),
        \App\Enums\Assets\ScriptLoader::Alert(),
        \App\Enums\Assets\ScriptLoader::Datepicker(),
    ]])
    @include('admin.partial.script.global')

    <script>

        // Toast Configuration and Helpers
        const toastConfig = {
            position: 'bottom-left',
            hideAfter: 4400,
            textAlign: 'right',
            allowToastClose: true
        };

        const getDefaultHeading = (type) => {
            const headings = {
                success: 'موفق',
                error: 'خطا',
                warning: 'اعتبار سنجی'
            };
            return headings[type] || '';
        };

        const previewToast = (type, message, heading = null) => {
            const config = {
                ...toastConfig,
                text: message,
                icon: type,
                heading: heading || getDefaultHeading(type)
            };

            if (type === 'warning') {
                config.loaderBg = '#ffffff';
                config.bgColor = '#ff8100';
            }

            $.toast(config);
        };

        const handleValidationErrors = (errors) => {
            const errorMessages = Object.values(errors).flat().join('<br>');
            previewToast('warning', errorMessages);
        };

        // Facility Management Class
        class FacilityManager {
            constructor() {
                this.selectors = {
                    row: '.tr-row',
                    submitBtn: '.btn-success',
                    deleteBtn: '.btn-danger',
                    priceSelect: 'select[id^="price_"]',
                    workSelect: 'select[id^="work_"]',
                    financialSelect: 'select[id^="financial_"]',
                    description: 'textarea[id^="description_"]',
                    priceInput: '.custom-price-input',
                    workInput: '.custom-work-input',
                    financialInput: '.custom-financial-input',
                    generateInvoiceBtn: '#generate-invoice',
                    totalPriceCell: '#total-price'
                };

                this.$table = $('table');
                this.$csrf = $('meta[name="csrf-token"]').attr('content');

                this.initializeEventListeners();
                this.initializeInputVisibility();
                this.initializeInputFormatters();
                jalaliDatepicker.startWatch();
            }

            // Event Listeners
            initializeEventListeners() {
                this.$table
                    .on('click', `${this.selectors.row} ${this.selectors.submitBtn}`, this.handleUpdate.bind(this))
                    .on('click', `${this.selectors.row} ${this.selectors.deleteBtn}`, this.handleDelete.bind(this))
                    .on('click', this.selectors.generateInvoiceBtn, this.handleInvoiceGeneration.bind(this))
                    .on('change', this.selectors.priceSelect, this.togglePriceInput.bind(this))
                    .on('change', this.selectors.workSelect, this.toggleWorkInput.bind(this))
                    .on('change', this.selectors.financialSelect, this.toggleFinancialInput.bind(this))
                    .on('input', this.selectors.priceInput, this.updateTotalPrice.bind(this));
            }

            // UI Toggle Handlers
            initializeInputVisibility() {
                const initializeSelect = (selector, inputSelector, compareValue) => {
                    $(`${this.selectors.row} ${selector}`).each(function() {
                        $(this).closest('td').find(inputSelector)
                            .toggle($(this).val() === compareValue);
                    });
                };

                initializeSelect(
                    this.selectors.priceSelect,
                    this.selectors.priceInput,
                    '{{ \App\Enums\Database\Facility\PriceType::Input }}'
                );
                initializeSelect(
                    this.selectors.workSelect,
                    this.selectors.workInput,
                    '{{ \App\Enums\Database\Facility\WorkCycle::InputDate }}'
                );
                initializeSelect(
                    this.selectors.financialSelect,
                    this.selectors.financialInput,
                    '{{ \App\Enums\Database\Facility\FinancialCycle::InputPrice }}'
                );
            }

            initializeInputFormatters() {
                $(this.selectors.row).each((_, row) => {
                    makeInputPrice($(row).find(this.selectors.priceInput));
                    makeInputPrice($(row).find(this.selectors.financialInput));
                });
            }

            // Form Data Handlers
            getFormData($row, facilityId) {
                const baseFormData = {
                    facility_id: facilityId,
                    price_type: $row.find(this.selectors.priceSelect).first().val(),
                    work_cycle: $row.find(this.selectors.workSelect).val(),
                    work_cycle_value: $row.find(this.selectors.workInput).val(),
                    financial_cycle: $row.find(this.selectors.financialSelect).val(),
                    description: $row.find(this.selectors.description).val(),
                };

                const priceValue = $row.find(this.selectors.priceInput).val();
                const financialValue = $row.find(this.selectors.financialInput).val();

                if (priceValue) {
                    baseFormData.price_value = parseInt(priceValue.replace(/,/g, ''), 10);
                }
                if (financialValue) {
                    baseFormData.financial_cycle_value = parseInt(financialValue.replace(/,/g, ''), 10);
                }

                return baseFormData;
            }

            // AJAX Handlers
            handleUpdate(event) {
                const $btn = $(event.currentTarget);
                const $row = $btn.closest(this.selectors.row);
                const facilityId = $row.data('id');
                const originalContent = $btn.html();

                $btn.html('<i class="fal fa-spinner fa-spin"></i>').prop('disabled', true);

                const formData = this.getFormData($row, facilityId);
                const updateUrl = "{{ route('admin.project.renewal.facility.update', ['project_renewal_id' => $projectRenewal->id, 'project_facility_renewal_id' => ':facilityId']) }}"
                    .replace(':facilityId', facilityId);

                $.ajax({
                    url: updateUrl,
                    method: 'PATCH',
                    data: formData,
                    headers: { 'X-CSRF-TOKEN': this.$csrf },
                    success: (response) => {
                        this.updateRowUI($row, response);
                        previewToast('success', response.message);
                    },
                    error: (xhr) => {
                        if (xhr.status === 422) {
                            handleValidationErrors(xhr.responseJSON.errors);
                        } else {
                            previewToast('error', xhr.responseJSON.message);
                        }
                    },
                    complete: () => {
                        $btn.html(originalContent).prop('disabled', false);
                    }
                });
            }

            handleDelete(event) {
                const $btn = $(event.currentTarget);
                const $row = $btn.closest(this.selectors.row);
                const facilityId = $row.data('id');

                if (!confirm('Are you sure you want to delete this facility?')) return;

                const originalContent = $btn.html();
                $btn.html('<i class="fal fa-spinner fa-spin"></i>').prop('disabled', true);

                const deleteUrl = "{{ route('admin.project.renewal.facility.destroy', ['project_renewal_id' => $projectRenewal->id, 'project_facility_renewal_id' => ':facilityId']) }}"
                    .replace(':facilityId', facilityId);

                $.ajax({
                    url: deleteUrl,
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': this.$csrf },
                    success: (response) => {
                        $row.fadeOut(400, () => {
                            $row.remove();
                            this.updateTotalPrice();
                        });
                        previewToast('success', response.message);
                    },
                    error: (xhr) => {
                        previewToast('error', xhr.responseJSON.message);
                    },
                    complete: () => {
                        if (!$row.is(':animated')) {
                            $btn.html(originalContent).prop('disabled', false);
                        }
                    }
                });
            }

            handleInvoiceGeneration() {

                swal({
                    title: 'تایید صدور فاکتور',
                    text: 'آیا از صدور فاکتور اطمینان دارید؟',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'بله، صادر کن!',
                    cancelButtonText: 'لغو'
                }, function(){
                    window.location.href = '{{ route('admin.project.renewal.facility.make-factor',['project_renewal_id'=>$projectRenewal->id]) }}';

                });
            }

            // UI Update Handlers
            updateRowUI($row, data) {
                // Update row UI based on response data
            }

            updateTotalPrice() {
                //$(this.selectors.totalPriceCell).text(total.toLocaleString());
            }

            // Input Toggle Handlers
            togglePriceInput(event) {
                const $select = $(event.currentTarget);
                const $input = $select.closest('td').find(this.selectors.priceInput);
                $input.toggle($select.val() === '{{ \App\Enums\Database\Facility\PriceType::Input }}');
            }

            toggleWorkInput(event) {
                const $select = $(event.currentTarget);
                const $input = $select.closest('td').find(this.selectors.workInput);
                $input.toggle($select.val() === '{{ \App\Enums\Database\Facility\WorkCycle::InputDate }}');
            }

            toggleFinancialInput(event) {
                const $select = $(event.currentTarget);
                const $input = $select.closest('td').find(this.selectors.financialInput);
                $input.toggle($select.val() === '{{ \App\Enums\Database\Facility\FinancialCycle::InputPrice }}');
            }
        }

        // Initialize on document ready
        $(document).ready(() => {
            new FacilityManager();
        });
    </script>
@endsection
