<script src="{{asset('res-admin/assets/plugins/button-loader/jquery.buttonLoader.min.js')}}"></script>
<script src="{{asset('res-admin/assets/plugins/toast/jquery.toast.min.js')}}"></script>
<script src="{{asset('res-admin/assets/plugins/jquery.form/jquery.form.min.js')}}"></script>
<script type="text/javascript">
    let baseConfig = {
        position:'bottom-left',
        hideAfter:4400,
        textAlign : 'right',
    }
    let hasSpinner = $('.has-spinner');
    let options =
        {
            beforeSubmit: function () {
                hasSpinner.buttonLoader('start');
            },
            success: function (response) {
                hasSpinner.buttonLoader('stop');
                if (response.result === 'created' || response.result === 'success' || response.result === 'updated') {

                    $.toast({
                        heading: 'موفق',
                        text: response.message ,
                        allowToastClose: false,
                        ...baseConfig,
                        icon: 'success'
                    });
                    setTimeout(()=>{
                        if(response.refresh !== undefined){
                            window.location.reload();
                        }
                        if(response.back !== null){
                            window.location = response.back;
                        }
                    },1500)
                }

                else if (response.result === 'warning') {
                    $.toast({
                        heading: 'اخطار',
                        text: response.message ,
                        ...baseConfig,
                        icon: 'warning'
                    })
                }
                else if (response.result === 'error') {
                    $.toast({
                        heading: 'خطا',
                        text: response.message ,
                        ...baseConfig,
                        icon: 'error'
                    })
                }
            },
            error: function (response) {
                hasSpinner.buttonLoader('stop');
                if(response.status === 422)
                {
                    let errors = '';
                    $.each(response.responseJSON.errors, function(key, value){
                        errors += value + '<br>';
                    });
                    $.toast({
                        heading: 'اعتبار سنجی',
                        text: errors ,
                        ...baseConfig,
                        icon: 'warning',
                        loaderBg: '#ffffff',
                        bgColor: '#ff8100'
                    })
                }
                else {
                    $.toast({
                        heading: 'اخطار',
                        text: response.responseJSON.message ,
                        ...baseConfig,
                        loaderBg: '#ffffff',
                        bgColor: '#ff8100',
                        icon: 'warning'
                    })
                }
                hasSpinner.buttonLoader('stop');
            }
        };
    $('.request-form').ajaxForm(options);
    function confirmDelete() {
        swal({
            title: "حذف",
            text: "آیا مطمئن هستید که میخواهید این مورد را حذف کنید؟",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#ff0f3b",
            confirmButtonText: "حذف",
            cancelButtonText: "صرفه نظر",
            closeOnConfirm: false
        }, function(){
            $('#deleteItem').submit();
        });
    }
</script>
