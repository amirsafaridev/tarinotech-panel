<script>
    const chatGroupContainer = $('#chat-group-container');
    const chatContainer = $('#chat-container');

    const messageHeader = {
        container: $('#message-header-container'),
        logo: $('#message-header-container .m-logo'),
        title: $('#message-header-container .m-title'),
        description: $('#message-header-container .m-description'),
    }

    let chatScrollLoad = false;
    let chatPage = 1;

    const loadingMotion = '<div class="d-flex justify-content-center h-4 align-items-center loading"><i class="fal fa-spinner fa-spin"></i></div>';

    $(document).ready(function () {
        fetchChatGroups();
        setScrollPagination();
        setupSelectChat();
        messageForm[0].reset();
    })

    function setScrollPagination() {
        chatContainer.scroll(function () {
            let container = $(this);
            if (chatScrollLoad && container.scrollTop() + container.height() >= container[0].scrollHeight - 100) {
                chatScrollLoad = false;
                chatGroupContainer.append(loadingMotion);
                console.log('call scroll');
                fetchChatGroups();
            }
        });
    }

    function fetchChatGroups() {
        chatGroupContainer.append(loadingMotion);
        $.ajax({
            type: 'GET',
            url: '{{ route('admin.support.group.index') }}' + '?page=' + chatPage,
            dataType: 'json',
            success: function (data) {
                let htmlRows = '';
                data.chats.forEach(function (item) {
                    htmlRows += item.htmlRender;
                });
                chatGroupContainer.append(htmlRows)
                if (chatPage < data.pagination.last_page) {
                    chatPage++;
                    activeScroll();
                }

            },
            error: function (xhr, status, error) {

            },
            complete: function () {
                chatGroupContainer.find('.loading').remove();
            }
        });
    }

    function activeScroll() {
        setTimeout(() => {
            chatScrollLoad = true;
        }, 200)
    }

    function setupSelectChat() {
        chatContainer.on('click', '.aw-chat-header', function () {
            const chat = $(this);
            if(chat.data('chat-logo') !== '{{ asset('') }}'){
                messageHeader.logo.removeClass('d-none');
                messageHeader.logo.attr('src', chat.data('chat-logo'));
            }
            else{
                messageHeader.logo.addClass('d-none');
            }

            messageHeader.title.html(chat.data('chat-title'));
            messageHeader.description.html(chat.data('chat-description'));
            chatId.val(chat.data('chat-id'));

            messageContainer.html('');
            messagePage = 1;

            loadMessage(chatId);
        })
    }

    /* Message Script */
    const messageContainer = $('#message-container');
    const messageForm = $('#message-form');

    const btnMessageSend = $('#btn-message-send');
    const chatId = $('#chat_id');

    let messageScrollLoad = false;
    let messagePage = 1;

    const icons = {
        loading: '<span class="fal fa-spinner fa-spin"></span>',
        send: '<span class="fal mt-1 fa-send fa-rotate-180"></span>'
    };

    const toastConfig = {
        position: 'bottom-left',
        hideAfter: 4400,
        textAlign: 'right',
    }

    $(document).ready(function () {
        sendMessage();
        setMessagesScrollPagination();
    })

    function sendMessage() {
        let formOptions = {
            beforeSubmit: function () {
                btnMessageSend.html(icons.loading);
                if (!chatId.val()) {
                    $.toast({
                        ...toastConfig,
                        heading: 'اخطار',
                        text: 'یک گفتگو را انتخاب کنید',
                        allowToastClose: false,
                        icon: 'warning'
                    });
                    btnMessageSend.html(icons.send);
                    return;
                }
            },
            success: function (response) {
                $.toast({
                    ...toastConfig,
                    heading: 'موفق',
                    text: response.message,
                    allowToastClose: false,
                    icon: 'success'
                });
                messageContainer.append(response.htmlRender);
                messageContainer.scrollTop(messageContainer.prop("scrollHeight"));

                btnMessageSend.html(icons.send);
                attachmentContainer.html('');
                messageForm[0].reset();

            },
            error: function (response) {
                if (response.status === 422) {
                    let errors = '';
                    $.each(response.responseJSON.errors, function (key, value) {
                        errors += value + '<br>';
                    });
                    $.toast({
                        ...toastConfig,
                        heading: 'اعتبار سنجی',
                        text: errors,
                        icon: 'warning',
                    })
                }
                btnMessageSend.html(icons.send);
            }
        };
        messageForm.ajaxForm(formOptions);
    }

    function loadMessage(chatId) {
        messageContainer.append(loadingMotion);
        $.ajax({
            type: 'POST',
            url: '{{ route('admin.chat.message.index') }}',
            data: {
                'page': messagePage,
                'chat_id': chatId.val()
            },
            dataType: 'json',
            success: function (data) {
                let htmlRows = '';
                data.messages.forEach(function (item) {
                    htmlRows += item.htmlRender;
                });
                messageContainer.prepend(htmlRows)
                if (messagePage === 1) {
                    messageContainer.scrollTop(messageContainer.prop("scrollHeight"));
                }
                if (messagePage < data.pagination.last_page) {
                    messagePage++;
                    activeMessageScroll();
                }

            },
            error: function (xhr, status, error) {
                console.log(error);
            },
            complete: function () {
                messageContainer.find('.loading').remove();
            }
        });
    }

    function setMessagesScrollPagination() {
        messageContainer.scroll(function () {
            let container = $(this);
            if (messageScrollLoad && container.scrollTop() <= 100) {
                messageScrollLoad = false;
                messageContainer.prepend(loadingMotion);
                console.log('call scroll');
                loadMessage(chatId.val());
            }
        });
    }

    function activeMessageScroll() {
        setTimeout(() => {
            messageScrollLoad = true;
        }, 1000)
    }

    /* Attachment */
    const fileAttachment = $('#file-attachment');
    const attachmentContainer = $('#attachment-container');

    $(document).ready(function () {
       setupFile();
    })
    function setupFile(){
        fileAttachment.change(function() {
            let file = $(this).prop("files")[0];
            uploadFile(file);
        });
    }

    function uploadFile(file) {
        if (!chatId.val()) {
            $.toast({
                ...toastConfig,
                heading: 'اخطار',
                text: 'یک گفتگو را انتخاب کنید',
                allowToastClose: false,
                icon: 'warning'
            });
            return;
        }
        let formData = new FormData();
        formData.append('file', file);
        formData.append('chat_id', chatId.val());

        $.ajax({
            url: '{{ route('admin.chat.attachment.upload') }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Handle success response
                attachmentContainer.append(response.htmlRender)
            },
            error: function(xhr, status, error) {
                console.error('Error uploading file:', error);
            }
        });
    }


</script>