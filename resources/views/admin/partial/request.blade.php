<script src="{{ asset('res-admin/assets/plugins/button-loader/jquery.buttonLoader.min.js') }}"></script>
<script src="{{ asset('res-admin/assets/plugins/toast/jquery.toast.min.js') }}"></script>
<script src="{{ asset('res-admin/assets/plugins/jquery.form/jquery.form.min.js') }}"></script>
<script type="text/javascript">
    let baseConfig = {
        position: 'bottom-left',
        hideAfter: 4400,
        textAlign: 'right',
    }
    //let hasSpinner = $('.has-spinner');
    let currentSubmitBtn = null;
    $(document).on('click', 'button[type="submit"]', function(event) {
        currentSubmitBtn = $(event.target);
    });


    let options = {
        beforeSubmit: function() {
            if (currentSubmitBtn) {
                currentSubmitBtn.buttonLoader('start');
            }
        },
        success: function(response) {
            if (currentSubmitBtn) {
                currentSubmitBtn.buttonLoader('stop');
            }
            if (response.result === 'created' || response.result === 'success' || response.result ===
                'updated') {

                $.toast({
                    heading: 'موفق',
                    text: response.message,
                    allowToastClose: false,
                    ...baseConfig,
                    icon: 'success'
                });
                setTimeout(() => {
                    if (response.refresh !== undefined) {
                        window.location.reload();
                    }
                    if (response.back !== undefined && response.back !== null) {
                        window.location = response.back;
                    }
                }, 1500)
            } else if (response.result === 'warning') {
                $.toast({
                    heading: 'اخطار',
                    text: response.message,
                    ...baseConfig,
                    icon: 'warning'
                })
            } else if (response.result === 'error') {
                $.toast({
                    heading: 'خطا',
                    text: response.message,
                    ...baseConfig,
                    icon: 'error'
                })
            }
        },
        error: function(response) {
            if (currentSubmitBtn) {
                currentSubmitBtn.buttonLoader('stop');
            }
            if (response.status === 422) {
                let errors = '';
                $.each(response.responseJSON.errors, function(key, value) {
                    errors += value + '<br>';
                });
                $.toast({
                    heading: 'اعتبار سنجی',
                    text: errors,
                    ...baseConfig,
                    icon: 'warning',
                    loaderBg: '#ffffff',
                    bgColor: '#ff8100'
                })
            } else {
                $.toast({
                    heading: 'اخطار',
                    text: response.responseJSON.message,
                    ...baseConfig,
                    loaderBg: '#ffffff',
                    bgColor: '#ff8100',
                    icon: 'warning'
                })
            }
            if (currentSubmitBtn) {
                currentSubmitBtn.buttonLoader('stop');
            }
        }
    };
    $('.request-form').ajaxForm(options);

    function confirmDelete() {
        swal({
            title: 'آیا مطمئن هستید؟',
            text: "این عمل قابل بازگشت نیست!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#ff0f3b',
            confirmButtonText: 'بله، حذف کن!',
            cancelButtonText: 'خیر، انصراف',
            closeOnConfirm: false
        }, function() {
            $('#deleteItem').submit();
        });
    }
</script>
