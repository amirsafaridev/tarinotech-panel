<script>
    const userId = $('#user_id');
    const price = $('#price');
    const priceMonthly = $('#price_monthly');
    const keywordsCount = $('#keywords_count');
    const amountContent = $('#amount_content');
    const agreementDuration = $('#agreement_duration');
    const packageId = $('#package_id');
    const agreementAt = $('#agreement_at');
    const keywords = $('#keywords');

    let minimumPricePercent = 100;
    let minimumPrice = 0;

    const packages = @json($packages);

    const editMode = {{ isset($project) ? 'true' : 'false' }};

    $(document).ready(function () {
        jalaliDatepicker.startWatch();
        applyTypeInput();
        select2Setup();
        hostSetup();
        packageSetup();
        agreementAtSetup();
        agreementDurationSetup();
        typeStatusSetup();
        if(!editMode){
            priceMonthlySetup();
        }
        CKEDITOR.replace('contract_attachment', {height: 400});

        if(editMode){
            packageId.trigger('change');
            keywordsSetup();
        }
    });

    function applyTypeInput() {
        makeInputPrice(price);
        makeInputPrice(priceMonthly);
        makeInputPrice(keywordsCount);
        makeInputNumber(amountContent);
    }

    function select2Setup() {
        userId.select2();
    }

    function agreementAtSetup() {
        agreementAt.change(function () {
            loadPackage($(this).val(), packageId.val());
        })
    }

    function agreementDurationSetup(){
        agreementDuration.change(function (){
            price.trigger('keyup');
        });
    }

    function keywordsSetup(){
       keywords.select2({
            tags: true,
            maximumSelectionLength: keywordsCount.val() ?? 0,
            placeholder: 'تگ‌ها را انتخاب یا اضافه کنید',
            language: {
                maximumSelected: function(args) {
                    return "شما فقط می‌توانید 10 تگ انتخاب کنید";
                }
            }
        });
    }

    function priceMonthlySetup() {
        price.keyup(function () {
            const priceValue = parseInt($(this).val().toString().replaceAll(',', ''));

            if(isNaN(priceValue)){
                return;
            }

            const agreementDurationValue = parseInt(agreementDuration.val());
            const monthlyDuration = agreementDurationValue / 30;

            if (monthlyDuration <= 0 || isNaN(monthlyDuration)) {
                alert('مدت زمان توافق باید بیشتر از صفر باشد.');
                priceMonthly.val('');
                return;
            }

            let pricePerMonth =  Math.round(priceValue / monthlyDuration);
            priceMonthly.val(pricePerMonth);
            priceMonthly.trigger('click');
        });
    }


    function hostSetup() {

        const hostLocation = $('#host_location');
        const hostContainer = $('#host_container');

        hostLocation.change(function () {
            if ($(this).val() === 'IN_COMPANY') {
                setStateHost(false);
            } else {
                setStateHost(true);
            }
        });

        function setStateHost(status) {
            if (status) {
                hostContainer.removeClass('d-none');
            } else {
                hostContainer.addClass('d-none');
            }
        }
    }

    function packageSetup() {
        packageId.change(function () {
            const selectedPackageId = $(this).val();
            const selectedPackage = packages.find(pkg => parseInt(pkg.id) === parseInt(selectedPackageId));
            if (selectedPackage) {
                if(!editMode) {
                    amountContent.val(selectedPackage.seo_amount_content);
                    keywordsCount.val(selectedPackage.seo_keywords_count);
                    agreementDuration.val(selectedPackage.seo_agreement_duration);
                    price.trigger('keyup');
                }
                minimumPricePercent = parseFloat(selectedPackage.minimum_price_percent);

                keywordsSetup();
                loadPackage(agreementAt.val(), selectedPackageId);
            } else {
                console.log('Package not found');
            }
        })
    }

    const priceLabel = $('label[for="price"]');
    function loadPackage(data, id) {
        const dateRegex = /^\d{4}\/\d{2}\/\d{2}$/;
        if (isNaN(parseInt(id))) {
            console.error('Invalid package ID. It must be a number.');
            return;
        }
        if (!dateRegex.test(data)) {
            console.error('Invalid date format. It must be in YYYY/MM/DD format.');
            return;
        }
        blockUI();
        postAjax('{{ route('admin.ajax.package.current-price') }}', {
            date: data,
            package_id: id
        }).then(function (response) {
            minimumPrice = response.data.minimumPrice;
            const minimumPriceFormatted = response.data.minimumPriceFormatted;

            const $labelContent = $('<span></span>').text('قیمت (');
            const $minPriceSpan = $('<span></span>')
                .css('color', 'red')
                .text(`حداقل قیمت ${minimumPriceFormatted}`);
            $labelContent.append($minPriceSpan).append(' می‌باشد)');

            priceLabel.html($labelContent.html());

            unblockUI();
        }).catch(function () {
            unblockUI();
            priceLabel.html("قیمت (ریال)");
        });
    }


    function typeStatusSetup() {
        const jsonTypeWithStatuses = @json($types);

        const typeIdSelect = $('#type_id');
        const statusIdSelect = $('#status_id');

        typeIdSelect.change(function () {
            const id = parseInt($(this).val());
            const type = jsonTypeWithStatuses.find(function (item) {
                return item.id === id;
            })
            if (type) {
                statusIdSelect.empty();
                type.statuses.forEach(function (status) {
                    const option = $('<option>', {
                        value: status.id,
                        text: status.title
                    });
                    statusIdSelect.append(option);
                });
            }
        });
        typeIdSelect.trigger('change');
        setTimeout(() => {
            @if(isset($project))
            statusIdSelect.val(parseInt('{{ $project->status_id }}'));
            @endif
        }, 200)
    }
</script>