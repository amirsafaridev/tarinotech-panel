<script>
    $(document).ready(function () {
        CKEDITOR.replace('description');
        $('#role').select2();

        jalaliDatepicker.startWatch();

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