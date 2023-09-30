<script>
    function showToast(text, icon = 'warning') {
        const iconConfig = {
            warning: {bgColor: '#ff8100', heading: 'اخطار'},
            success: {bgColor: '#00a65a', heading: 'موفق'},
            error: {bgColor: '#ff0000', heading: 'خطا'},
        };
        const config = iconConfig[icon] || {bgColor: '#ff8100', heading: 'Warning'};
        $.toast({
            heading: config.heading,
            bgColor: config.bgColor,
            text: text,
            position: 'bottom-left',
            hideAfter: 4400,
            textAlign: 'right',
            icon: icon,
            loaderBg: '#ffffff'
        });
    }

    function postAjax(url, postData) {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: "POST",
                url: url,
                data: postData,
                success: function (response) {
                    showToast(response.message, 'success');
                    resolve(response);
                },
                error: function (response) {
                    if (response.status === 422) {
                        const errors = Object.values(response.responseJSON.errors).join('<br>');
                        showToast(errors, 'warning');
                    } else {
                        showToast('Error', response.responseJSON.message, 'error');
                    }
                    reject(response);
                }
            });
        });
    }

    function makeInputPrice(inputSelect, haveDot = false) {
        inputSelect.on("input", function () {
            let inputValue = $(this).val();

            // Use a single regular expression for cleaning
            const regex = haveDot ? /[^0-9.]/g : /[^0-9]/g;
            inputValue = inputValue.replace(regex, "");

            // Remove multiple dots
            inputValue = inputValue.replace(/(\..*?)\./g, '$1');

            // Add thousands separator (comma) to the number
            inputValue = numberWithCommas(inputValue);

            $(this).val(inputValue);
        });
    }

    function makeInputOnlyAlpha(inputSelect) {
        inputSelect.on("input", function () {
            // Get the current input value
            let inputValue = $(this).val();

            // Use a regular expression to remove any characters that are not alphabetic or spaces
            let sanitizedValue = inputValue.replace(/[^a-zA-Z\s]/g, '');

            // Update the input field with the sanitized value
            $(this).val(sanitizedValue);
        });
    }

    // Helper function to add thousands separator (comma)
    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function makeSelect2Remote(inputSelect, url,keys) {

        $(inputSelect).select2({
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        term: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            dir: 'rtl',
            language: 'fa',
            minimumInputLength: 2,
            templateResult: function (result) {
                if (!result.id) {
                    return result.text;
                }
                let finalResult = [];
                keys.forEach(function (element) {
                    finalResult.push(result[element])
                })
                return finalResult.join(' ');
            },
            templateSelection: function (result) {
                if (!result.id) {
                    return result.text;
                }
                let finalResult = [];
                keys.forEach(function (element) {
                    finalResult.push(result[element])
                })
                return finalResult.join(' ');
            }
        });
    }
</script>
