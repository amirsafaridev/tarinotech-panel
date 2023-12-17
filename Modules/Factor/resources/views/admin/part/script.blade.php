<script>
    const projectId = $('#project_id');
    const projectInfo = $('#project_info');
    const factorItemContainer = $('#factor_item_container');
    const btnAddItem = $('#btn_add_item');

    const swalDeleteConfirmationTitle = "حذف";
    const swalDeleteConfirmationMessage = "آیا مطمئن هستید که میخواهید این مورد را حذف کنید؟";
    const swalConfirmButtonText = "حذف";
    const swalCancelButtonText = "صرفه نظر";

    $(document).ready(function () {
        activeParentUl('{{ route('admin.factor.index') }}');
        jalaliDatepicker.startWatch();

        select2Setup();
        projectLoader();
        factorItemSetup();
        factorItemInputType();
    })

    function select2Setup() {
        projectId.select2();
    }

    function projectLoader() {
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
    }

    function factorItemSetup() {
        let itemCount = 0;
        @if(isset($factor))
            itemCount = {{ $factor->items->count() }};
        @endif
        btnAddItem.click(function () {
            getAjax('{{ route('admin.factor.item.view') }}')
                .then(function (response) {
                    let dataResource = response.html;
                    dataResource = dataResource.replace(/__INDEX__/g, itemCount);
                    factorItemContainer.append(dataResource);
                    itemCount++;
                    factorItemInputType();
                })
                .catch(function (response) {
                    showToast(response);
                });
        });

        factorItemContainer.on('click', '.btn-remove', function () {
            const self = $(this);

            swal({
                title: swalDeleteConfirmationTitle,
                text: swalDeleteConfirmationMessage,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#ff0f3b",
                confirmButtonText: swalConfirmButtonText,
                cancelButtonText: swalCancelButtonText,
                closeOnConfirm: true
            }, function () {
                self.closest('.card-factor-item').remove();
                itemCount--;
            });
        });
    }

    function factorItemInputType(){
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
</script>