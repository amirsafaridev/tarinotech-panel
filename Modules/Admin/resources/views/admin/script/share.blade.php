<script>
    $(document).ready(function () {
        CKEDITOR.replace('description');

        @if(isset($admin) && $admin->roles->isNotEmpty())
        const roles = "{{ $admin->roles->pluck('id')->implode(',') }}";
        $.each(roles.split(","), function (i, e) {
            $("#role option[value='" + e + "']").prop("selected", true);
        })
        @endif

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