<script>
    $(document).ready(function () {
        typeStatusSetup();
    })

    const typeId = $('#type_id')

    function typeStatusSetup() {
        const jsonTypeWithStatuses = @json( $types);
        const statusId = $('#project_status_id');
        const statusForwardId = $('#project_status_forward_id');

        typeId.change(function () {
            const id = parseInt($(this).val());
            const type = jsonTypeWithStatuses.find(function (item) {
                return item.id === id;
            })
            if (type) {
                statusId.empty();
                statusForwardId.empty();
                type.statuses.forEach(function (status) {
                    const option = $('<option>', {
                        value: status.id,
                        text: status.title
                    });
                    //statusForwardId.append(option);
                    statusId.append(option);
                });

                type.statuses.forEach(function (status) {
                    const option = $('<option>', {
                        value: status.id,
                        text: status.title
                    });
                    statusForwardId.append(option);
                    //statusId.append(option);
                });
            }
        });

        typeId.trigger('change');
        setTimeout(() => {
            @if(isset($factorStatusForward))
            statusId.val(parseInt('{{ $factorStatusForward->project_status_id }}'));
            statusForwardId.val(parseInt('{{ $factorStatusForward->project_status_forward_id }}'));
            @endif
        }, 200)
    }



</script>