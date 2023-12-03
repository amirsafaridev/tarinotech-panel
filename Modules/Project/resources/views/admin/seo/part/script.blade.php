<script>
    const userId = $('#user_id');
    const price = $('#price');
    const priceMonthly = $('#price_monthly');
    const keywordsCount = $('#keywords_count');

    $(document).ready(function () {
        jalaliDatepicker.startWatch();
        applyTypeInput();
        select2Setup();
        hostSetup();
        typeStatusSetup();
    });

    function applyTypeInput(){
        makeInputPrice(price);
        makeInputPrice(priceMonthly);
        makeInputPrice(keywordsCount);
    }

    function select2Setup(){
        userId.select2();
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
</script>