@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
       \App\Enums\Assets\StyleLoader::Toast(),
       \App\Enums\Assets\StyleLoader::Alert(),
       \App\Enums\Assets\StyleLoader::Select2(),
       \App\Enums\Assets\StyleLoader::Datepicker(),
   ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.factor.index') }}">فاکتور ها</a></li>
                <li class="breadcrumb-item active">ایجاد</li>
            </ol>
        </div>
    </div>

    <form class="row request-form forms-sample" method="post" action="{{ route('admin.factor.store') }}">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">ایجاد فاکتور</div>
                </div>

                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <div class="forms-sample">
                        @csrf

                        <div class="mb-3">
                            <label for="project_id" class="form-label">انتخاب پروژه</label>
                            <select class="form-control" name="project_id" id="project_id">
                                <option value="">انتخاب پروژه</option>
                            </select>
                        </div>

                        <div id="project_info"></div>

                        <x-admin.input identify="title" title="عنوان فاکتور"/>

                        <x-admin.input identify="expired_at"
                                       title="تاریخ انقضاء"
                                       :is-date-picker="true"
                                       old="{{ verta(now()->addDays(3))->format('Y/m/d') }}"/>

                        <x-admin.button-submit/>

                        <button id="btn_add_item" class="btn btn-success" type="button">افزودن آیتم</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="factor_item_container" class="col-12"></div>
    </form>

@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::Datepicker(),
        \App\Enums\Assets\ScriptLoader::Alert(),
        \App\Enums\Assets\ScriptLoader::Select2(),

    ]])
    @include('admin.partial.request')
    @include('admin.partial.script.global')
    <script>
        $(document).ready(function () {

            const projectId = $('#project_id');
            const projectInfo = $('#project_info');
            makeSelect2Remote(projectId, '{{ route('admin.ajax.project.remote-select') }}', ['domain']);
            projectId.change(function () {
                const value = $(this).val();
                postAjax('{{ route('admin.ajax.project.single') }}', {
                    projectId: value
                })
                    .then(function (response) {
                        projectInfo.html(response.html)
                    })
                    .catch(function (response) {
                        showToast(response);
                        console.log(response);
                    });
            });

            jalaliDatepicker.startWatch();

            const factorItemContainer = $('#factor_item_container');
            let itemCount = 0;
            $('#btn_add_item').click(function () {
                getAjax('{{ route('admin.factor.item.view') }}')
                    .then(function (response) {
                        let dataResource = response.html;
                        dataResource = dataResource.replace(/__INDEX__/g, itemCount);
                        factorItemContainer.append(dataResource);
                        itemCount++;
                        updatePriceInputs();
                    })
                    .catch(function (response) {
                        showToast(response);
                        console.log(response);
                    });
            })

            factorItemContainer.on('click', '.btn-remove', function () {
                const self = $(this);
                swal({
                    title: "حذف",
                    text: "آیا مطمئن هستید که میخواهید این مورد را حذف کنید؟",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#ff0f3b",
                    confirmButtonText: "حذف",
                    cancelButtonText: "صرفه نظر",
                    closeOnConfirm: true
                }, function () {
                    self.closest('.card-factor-item').remove();
                    itemCount--;
                });
            });
        })

        function priceInputMaker(input) {
            input.off('click');
            input.off('input');
            input.off('change');
            makeInputPrice(input);
        }

        function updateTotalPrice(card) {
            const cardItem = card.parent().parent().parent().parent().parent();
            const h4FinalPrice = cardItem.find('.factor-item-price');

            let totalPrice = 0;
            let totalSub = 0;

            cardItem.find('.calc').each(function () {

                const inputInProcess = $(this);
                let price = parseInt(inputInProcess.val().replace(/,/g, ''), 10) || 0;

                if (inputInProcess.hasClass('offer-input')) {
                    totalSub += price;
                } else {
                    totalPrice += price;
                }
            });
            h4FinalPrice.text(numberWithCommas(totalPrice - totalSub));
        }

        function updatePriceInputs() {

            $('input.offer-input').each(function () {
                const input = $(this);
                priceInputMaker(input);
                input.on("input", function () {
                    updateTotalPrice(input);
                });
            });

            $('input.price-input').each(function () {
                const input = $(this);
                priceInputMaker(input);

                input.on("input", function () {
                    const priceInput = $(this);
                    const value = parseInt(priceInput.val().replace(/,/g, ''), 10) || 0;


                    const taxInput = priceInput.parent().parent().parent().find('.tax-input');

                    console.log(taxInput);
                    if (value <= 0) {
                        taxInput.val(0);
                    } else {
                        let taxCalc = Math.round(value * 0.09);
                        taxInput.val(numberWithCommas(taxCalc))
                    }
                    updateTotalPrice(input);
                })
            })

        }
    </script>
@endsection
