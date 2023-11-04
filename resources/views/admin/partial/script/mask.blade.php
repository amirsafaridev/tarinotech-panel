<script>
    function applyShabaMask(inputSelect) {
        inputSelect.mask('IR00 0000 0000 0000 0000 0000 00', {
            translation: {
                'I': {pattern: /[Ii]/, optional: false},
                'R': {pattern: /[Rr]/, optional: false},
                '0': {pattern: /\d/, optional: false}
            },
            placeholder: "IR__ ____ ____ ____ ____ ____ __",
            clearIfNotMatch: true
        });
    }

    function applyCartNumberMask(inputSelect) {
        inputSelect.mask('0000000000000000',{
            placeholder: "________________",
        });
    }

    function applyPostalCodeMask(inputSelect) {
        inputSelect.mask('0000000000',{
            placeholder: "__________",
        });
    }

    function applyNationalNumberMask(inputSelect) {
        inputSelect.mask('0000000000',{
            placeholder: "__________",
        });
    }

    function applyTelMask(inputSelect) {
        inputSelect.mask('00000000000',{
            placeholder: "___ ________",
        });
    }

    function applyMobileMask(inputSelect) {
        inputSelect.mask('00000000000',{
            placeholder: "09999999999",
        });
    }
</script>
