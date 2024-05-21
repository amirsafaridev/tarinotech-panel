<script>
    const domainsRequired = $('#domains_required');
    const userId = $('#user_id');
    const languages = $('#languages');
    const selectOptions = $('#options');
    const price = $('#price');
    const agreementAt = $('#agreement_at');

    $(document).ready(function () {
        jalaliDatepicker.startWatch();

        applyTypeInput();
        select2Setup();
        typeStatusSetup();
        typePackageSetup();
        domainSetup();
        hostSetup();
        workingDaysCalcSetup();
    })

    function applyTypeInput() {
        makeInputPrice(price);
    }

    function select2Setup() {
        domainsRequired.select2();
        userId.select2();
        languages.select2();
        selectOptions.select2();
    }

    function domainSetup() {
        const haveDomain = $('#have_domain')
        const domainContainer = $('#domain_container');

        haveDomain.change(function () {
            setStateDomain($(this).is(':checked'));
        });

        @if(isset($project))
        setStateDomain({{ $project->target->domains['have_domain'] }});
        @endif

        function setStateDomain(status) {
            if (status) {
                domainContainer.removeClass('d-none');
            } else {
                domainContainer.addClass('d-none');
            }
        }
    }

    function hostSetup() {
        const haveHost = $('#have_host');
        const hostContainer = $('#host_container');

        haveHost.change(function () {
            setStateHost($(this).is(':checked'));
        });

        @if(isset($project))
        setStateHost({{ $project->target->host['have_host'] }});
        @endif

        function setStateHost(status) {
            if (status) {
                hostContainer.removeClass('d-none');
            } else {
                hostContainer.addClass('d-none');
            }
        }
    }

    const typeId = $('#type_id')

    function typeStatusSetup() {
        const jsonTypeWithStatuses = @json( $types);
        const statusId = $('#status_id');

        typeId.change(function () {
            const id = parseInt($(this).val());
            const type = jsonTypeWithStatuses.find(function (item) {
                return item.id === id;
            })
            if (type) {
                statusId.empty();
                type.statuses.forEach(function (status) {
                    const option = $('<option>', {
                        value: status.id,
                        text: status.title
                    });
                    statusId.append(option);
                });
            }
        });

        typeId.trigger('change');
        setTimeout(() => {
            @if(isset($project))
            statusId.val(parseInt('{{ $project->status_id }}'));
            @endif
        }, 200)
    }

    function typePackageSetup() {
        const jsonTypeWithPackages = @json( $packages);
        const packageId = $('#package_id');

        typeId.change(function () {
            const id = parseInt($(this).val());
            const packages = jsonTypeWithPackages.filter(function (item) {
                return item.type_id === id;
            })
            packageId.empty();
            packages.forEach(function (status) {
                const option = $('<option>', {
                    value: status.id,
                    text: status.title
                });
                packageId.append(option);
            });
        });
        typeId.trigger('change');
        setTimeout(() => {
            @if(isset($project))
            packageId.val(parseInt('{{ $project->target->package_id }}'));
            @endif
        }, 200)
    }

    function workingDaysCalcSetup() {
        const workingDaysInput = $('#working_days');
        const deadlineAt = $('#deadline_at');
        const alertMessageContainer = $('#alert_working_days .message');
        const debounceDelay = 2000;
        let debounceTimer;

        workingDaysInput.on('keyup', function () {
            const inputValue = $(this).val();

            if (!inputValue) {
                return;
            }

            clearTimeout(debounceTimer);

            displayMessage('<span class="fal fa-spinner fa-spin"></span>');

            debounceTimer = setTimeout(function () {
                const ajaxUrl = '{{ route('admin.ajax.calendar.calc') }}';
                const postData = {days: inputValue, start_date: agreementAt.val()};

                postAjax(ajaxUrl, postData)
                    .then(handleResponse)
                    .catch(handleAjaxError);
            }, debounceDelay);
        });

        deadlineAt.on('change', function () {
            const inputValue = $(this).val();

            if (!inputValue) {
                return;
            }

            const ajaxUrl = '{{ route('admin.ajax.calendar.calc') }}';
            const postData = {start_date: agreementAt.val(),end_date: deadlineAt.val()};

            postAjax(ajaxUrl, postData)
                .then(handleResponse)
                .catch(handleAjaxError);
        });

        function handleResponse(response) {
            const totalWorkDays = response.total_work_days;
            const totalFreeDays = response.total_free_days;
            const finalDateJalali = response.final_date_jalali;
            const total = response.total;

            const updatedMessage = `تعداد روز های تقویمی ${total} روز، روزهای کاری ${totalWorkDays} و تعداد روز های تعطیل محاسبه شده ${totalFreeDays} روز می باشد. تار یخ تحویل ${finalDateJalali} می باشد.`;

            displayMessage(updatedMessage);

            deadlineAt.val(finalDateJalali);
            // workingDaysInput.val(total);
        }

        function handleAjaxError(response) {
            console.error(response);
        }

        function displayMessage(message) {
            alertMessageContainer.html(message);
        }

        makeInputNumber(workingDaysInput);
    }

</script>