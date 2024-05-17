<script>
    $(document).ready(function () {
        typeStatusSetup();
    })

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



</script>