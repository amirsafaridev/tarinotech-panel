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
        <h1 class="page-title">فاکتور - ویرایش</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.factor.index') }}">فاکتور ها</a></li>
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>

    <form class="row request-form forms-sample" method="post" action="{{ $routeUpdate }}">
        @method('PUT')
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">ویرایش فاکتور</div>
                </div>

                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <div class="forms-sample">
                        @csrf

                        <div class="mb-3">
                            <label for="project_id" class="form-label">انتخاب پروژه</label>
                            <select class="form-control" name="project_id" id="project_id">
                                <option selected="selected" value="{{ $factor->project->id }}">{{ $factor->project->title }} - ({{ $factor->project->domain }})</option>
                            </select>
                        </div>

                        <div id="project_info"></div>

                        <x-admin.input identify="title" title="عنوان فاکتور" :old="$factor->title" />

                        <x-admin.select-enum identify="status" title="وضعیت" :enum-class="\App\Enums\Database\Factor\FactorStatus::class" :old="$factor->status"/>

                        <x-admin.input identify="expired_at" title="تاریخ انقضاء" old="{{ verta($factor->expired_at)->format('Y/m/d') }}" />

                        <x-admin.button-submit title="به روز رسانی"/>

                        <x-admin.button-delete/>

                        <button id="btn_add_item" class="btn btn-success" type="button">افزودن ایتم</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="factor_item_container" class="col-xl-6 col-lg-6 col-md-6 col-12">
            @if($factor->items->isNotEmpty())
                @foreach($factor->items as $index => $item)
                    @include('admin.factor_item.row-item',compact('item','index','transactionCategories'))
                @endforeach
            @endif
        </div>
    </form>

    <form id="deleteItem" action="{{ $routeDestroy }}" method="post" class="form-inline">
        @csrf
        @method('DELETE')
    </form>

@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::Datepicker(),
        \App\Enums\Assets\ScriptLoader::Alert(),
        \App\Enums\Assets\ScriptLoader::Select2(),

    ]])
    @include('admin.partial.request')
    @include('admin.partial.share-script')
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.factor.index') }}');
            const projectId = $('#project_id');
            const projectInfo = $('#project_info');
            //makeSelect2Remote(projectId,'{{ route('admin.ajax.project.remote-select') }}',['domain']);
            /*projectId.change(function (){
                const value = $(this).val();
                postAjax('{{ route('admin.ajax.project.single') }}', {
                    projectId : value
                })
                    .then(function (response) {
                        projectInfo.html(response.html)
                    })
                    .catch(function (response) {
                        showToast(response);
                        console.log(response);
                    });
            });*/

            const dataPickerConfig = {
                format: 'YYYY/MM/DD',
                initialValueType: 'persian',
                initialValue: false,
                autoClose: true
            };
            $('#expired_at').persianDatepicker(dataPickerConfig);

            const factorItemContainer = $('#factor_item_container');
            let itemCount = {{ $factor->items->count() }};
            $('#btn_add_item').click(function (){
                getAjax('{{ route('admin.ajax.factor.view-item') }}')
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

            factorItemContainer.on('click','.btn-remove',function (){
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

            /* Edit */
            //projectId.trigger('change');
            updatePriceInputs();
        })

        function priceInputMaker(input) {
            input.off('click');
            input.off('input');
            input.off('change');
            makeInputPrice(input);
        }

        function updateTotalPrice(card){
            const cardItem = card.parent().parent().parent();
            const h4FinalPrice  = cardItem.find('.factor-item-price');

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

        function updatePriceInputs(){

            $('input.offer-input').each(function (){
                const input = $(this);
                priceInputMaker(input);
                input.on("input", function () {
                    updateTotalPrice(input);
                });
            });

            $('input.price-input').each(function (){
                const input = $(this);
                priceInputMaker(input);

                input.on("input", function () {
                    const priceInput = $(this);
                    const value = parseInt(priceInput.val().replace(/,/g, ''), 10) || 0;

                    const taxInput = priceInput.parent().parent().find('.tax-input');

                    if(value <= 0){
                        taxInput.val(0);
                    }
                    else{
                        let taxCalc = Math.round(value * 0.09);
                        taxInput.val(numberWithCommas(taxCalc))
                    }
                    updateTotalPrice(input);
                })
            })

        }
    </script>
@endsection
