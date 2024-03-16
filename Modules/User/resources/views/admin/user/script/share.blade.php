<script>
    $(document).ready(function () {
        activeParentUl('{{ route('admin.user.index') }}');

        applyNationalNumberMask($('#national_id'));
        makeInputNumber($('#document_id'));
        applyPostalCodeMask($('#postal_code'));
        applyTelMask($('#tel'));

        jalaliDatepicker.startWatch();

        stateCompanyContainer('{{ $user->person_type ?? '' }}');
        $('#person_type').change(function (){
            stateCompanyContainer($(this).val());
        });

        stateIrnicContainer('{{ $user->irnic->status ?? '' }}');
        $('#irnic_status').change(function (){
            stateIrnicContainer($(this).val());
        });

        makeInputOnlyAlpha($('#en_first_name'));
        makeInputOnlyAlpha($('#en_last_name'));
    })

    const communications = $('#communications');
    communications.select2();

    const companyContainer = $('#company_container');
    function stateCompanyContainer(status){
        if(status === '{{ \Modules\User\app\Enums\PersonType::Legal }}'){
            companyContainer.removeClass('d-none');
        }
        else{
            companyContainer.addClass('d-none');
        }
    }

    const irnicContainer = $('#irnic_container');
    function stateIrnicContainer(status){
        if(status === '{{ \Modules\User\app\Enums\IrnicStatus::HasIt }}'){
            irnicContainer.removeClass('d-none');
        }
        else{
            irnicContainer.addClass('d-none');
        }
    }
</script>