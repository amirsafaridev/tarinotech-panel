<script>
    const domainsRequired = $('#domains_required');
    const userId = $('#user_id');
    const languages = $('#languages');
    const selectOptions = $('#options');
    const price = $('#price');

    $(document).ready(function () {
        jalaliDatepicker.startWatch();

        applyTypeInput();
        select2Setup();
        typeStatusSetup();
        domainSetup();
        hostSetup();
        workingDaysCalcSetup();
    })

    function applyTypeInput(){
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

    function typeStatusSetup() {
        const jsonTypeWithStatuses = @json( $types);
        const typeIdSelect = $('#type_id')
        const statusIdSelect = $('#status_id')

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
        setTimeout(()=>{
            @if(isset($project))
                statusIdSelect.val(parseInt('{{ $project->status_id }}'));
            @endif
        },200)
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
                const ajaxUrl = '{{ route('admin.ajax.calendar.calc.day.work') }}';
                const postData = { days: inputValue };

                postAjax(ajaxUrl, postData)
                    .then(handleResponse)
                    .catch(handleAjaxError);
            }, debounceDelay);
        });

        function handleResponse(response) {
            const totalWorkDays = response.total_work_days;
            const totalFreeDays = response.total_free_days;
            const finalDateJalali = response.final_date_jalali;

            const updatedMessage = `تعداد روز های محاسبه شده ${totalWorkDays} می باشد و تعداد روز های تعطیل محاسبه شده ${totalFreeDays} می باشد. تاریخ تحویل ${finalDateJalali} می باشد`;
            displayMessage(updatedMessage);

            deadlineAt.val(finalDateJalali);
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