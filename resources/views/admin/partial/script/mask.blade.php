<script>
    function applyShabaMask(inputSelect) {
        inputSelect.mask('IR000000000000000000000000', {
            translation: {
                'I': {pattern: /[Ii]/, optional: false},
                'R': {pattern: /[Rr]/, optional: false},
                '0': {pattern: /\d/, optional: false}
            },
            placeholder: "IR________________________",
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
