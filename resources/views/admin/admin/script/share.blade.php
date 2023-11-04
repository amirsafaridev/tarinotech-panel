<script>
    $(document).ready(function () {
        CKEDITOR.replace('description');

        const dataPickerConfig = {
            format: 'YYYY/MM/DD',
            initialValueType: 'persian',
            initialValue: false,
            autoClose: true
        };

        $('#dob').persianDatepicker(dataPickerConfig);
        $('#start_cooperation').persianDatepicker(dataPickerConfig);
        $('#start_last_contract').persianDatepicker(dataPickerConfig);
        $('#end_last_contract').persianDatepicker(dataPickerConfig);
        $('#role').select2();

    });

    makeInputPrice($('#promissory'));
    applyShabaMask($('#shaba_number'));
    applyTelMask($('#tel'));
    applyNationalNumberMask($('#national_code'));
    applyCartNumberMask($('#cart_number'));
    applyMobileMask($('#mobile'));
    applyMobileMask($('#mobile_company'));
    applyPostalCodeMask($('#postal_code'));
</script>