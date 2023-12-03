<script>

    const userId = $('#user_id');

    $(document).ready(function () {
        jalaliDatepicker.startWatch();
        select2Setup();
        typeStatusSetup();
    })


    function select2Setup() {
        userId.select2();
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