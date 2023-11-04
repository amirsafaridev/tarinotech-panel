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

    function getAjax(url) {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: "GET",
                url: url,
                success: function (response) {
                    showToast(response.message, 'success');
                    resolve(response);
                },
                error: function (response) {
                    reject(response);
                }
            });
        });
    }

    function makeInputPrice(inputSelect, haveDot = false) {
        function formatNumberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        inputSelect.click(function () {
            let inputValue = $(this).val();
            inputValue = numberWithCommas(inputValue);
            $(this).val(inputValue);
        });

        inputSelect.on("input", function () {
            let inputValue = $(this).val();
            const regex = haveDot ? /[^0-9.]/g : /[^0-9]/g;
            inputValue = inputValue.replace(regex, "");
            inputValue = inputValue.replace(/(\..*?)\./g, '$1');
            inputValue = numberWithCommas(inputValue);
            $(this).val(inputValue);
        });
    }

    function makeInputOnlyAlpha(inputSelect) {
        inputSelect.on("input", function () {
            let inputValue = $(this).val();
            let sanitizedValue = inputValue.replace(/[^a-zA-Z\s]/g, '');
            $(this).val(sanitizedValue);
        });
    }

    function makeInputOnlyAlphaNumber(inputSelect) {
        inputSelect.on("input", function () {
            let inputValue = $(this).val();
            let sanitizedValue = inputValue.replace(/[^a-zA-Z0-9\s]/g, '');
            $(this).val(sanitizedValue);
        });
    }

    function makeInputNumber(inputSelect) {
        inputSelect.on("input", function () {
            let inputValue = $(this).val();
            let sanitizedValue = inputValue.replace(/[^0-9\s]/g, '');
            $(this).val(sanitizedValue);
        });
    }

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

    function printMe(){
        window.print();
    }
</script>
