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
            if (chat.data('chat-logo') !== '{{ asset('') }}') {
                messageHeader.logo.removeClass('d-none');
                messageHeader.logo.attr('src', chat.data('chat-logo'));
            } else {
                messageHeader.logo.addClass('d-none');
            }

            messageHeader.title.html(chat.data('chat-title'));
            messageHeader.description.html(chat.data('chat-description'));
            chatId.val(chat.data('chat-id'));

            messageContainer.html('');
            messagePage = 1;

            loadMessage(chatId.val());
        })
    }

    /* Message Script */
    const messageContainer = $('#message-container');
    const replayContainer = $('#replay-container');
    const messageForm = $('#message-form');

    const btnMessageSend = $('#btn-message-send');
    const btnCancelEdit = $('#btn-cancel-edit');

    const chatId = $('#chat_id');
    const parentId = $('#parent_id');

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
        setupReplay();
        setupReplayNavigate();
        setupCancelEdit();
    })


    function setupCancelEdit(){
        btnCancelEdit.click(function (){
            resetSend();
        });
    }

    function setupReplay() {
        messageContainer.on('click', '.btn-replay', function () {
            parentId.val($(this).data('id'));
            let message = $(this).data('message');
            replayContainer.html(`<div class="d-flex p-2 align-items-center justify-content-between">
            <div><i class="fal fa-reply text-info me-2"></i><span>${message}</span></div>
            <button  class="btn btn-sm btn-danger btn-replay-remove" type="button"><i class="fal fa-trash"></i></button>
            </div>`);
        })

        replayContainer.on('click', '.btn-replay-remove', function () {
            replayContainer.html('');
            parentId.val('');
        });
    }

    function setupReplayNavigate() {
        messageContainer.on('click', '.replay', function () {
            let container = document.getElementById('message-container');
            let targetElement = document.getElementById(`message-${$(this).data('parent-id')}`);
            container.scrollTop = targetElement.offsetTop - container.offsetTop;
        });

    }

    function validationErrorPars(response) {
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

    function loadMessage(chatId) {
        messageContainer.append(loadingMotion);
        $.ajax({
            type: 'POST',
            url: '{{ route('admin.chat.message.index') }}',
            data: {
                'page': messagePage,
                'chat_id': chatId
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
                btnMessageSend.prop('disabled', true);
            },
            success: function (response) {
                $.toast({
                    ...toastConfig,
                    heading: 'موفق',
                    text: response.message,
                    allowToastClose: false,
                    icon: 'success'
                });

                /* Reload Message On Update */
                if(response.action === 'update'){
                    $("#message-" + response.messageId).replaceWith(response.htmlRender);
                }
                else{
                    messageContainer.append(response.htmlRender);
                    messageContainer.scrollTop(messageContainer.prop("scrollHeight"));
                }

                btnMessageSend.html(icons.send);
                btnMessageSend.prop('disabled', false);

                resetSend();
            },
            error: function (response) {
                if (response.status === 422) {
                    validationErrorPars(response);
                }
                btnMessageSend.html(icons.send);
                btnMessageSend.prop('disabled', false);
            }
        };
        messageForm.ajaxForm(formOptions);
    }

    function resetSend(){
        /* Reset Attachment */
        attachmentContainer.html('');

        /* Reset Form */
        messageForm[0].reset();

        /* Reset If Edit Mode */
        messageForm.prop('action','{{ route('admin.chat.message.store') }}');
        btnCancelEdit.addClass('d-none');

        /* Reset Replay */
        replayContainer.html('');
        parentId.val('');
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

    /* Edit Modal Message */
    $(document).ready(function () {
        setupEditModal();
    })

    function setupEditModal() {
        messageContainer.on('click', '.btn-edit', function () {
            fetchMessageById($(this).data('id'));
        })
    }

    function fetchMessageById(messageId) {

        let formData = new FormData();
        formData.append('message_id', messageId);
        formData.append('chat_id', chatId.val());

        $.ajax({
            url: '{{ route('admin.chat.message.edit') }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                attachmentContainer.html('');
                attachmentContainer.append(response.attachmentHtmlRender);
                messageForm.prop('action',response.updateUrl);
                messageForm.find('textarea[name="message"]').val(response.message.content);
                btnCancelEdit.removeClass('d-none');
            },
            error: function (xhr, status, error) {

            }
        });
    }

    /* Attachment */
    const fileAttachment = $('#file-attachment');
    const attachmentContainer = $('#attachment-container');

    $(document).ready(function () {
        setupFile();
        setupDeleteFile();
    })

    function setupDeleteFile(){
        attachmentContainer.on('click','.btn-delete-attachment',function (){
            const messageId = $(this).data('chat-message-id');
            const fileId = $(this).data('id');
            deleteFile(messageId,fileId);
        })
    }

    function deleteFile(messageId,fileId) {

        let formData = new FormData();
        formData.append('message_id', messageId);
        formData.append('file_id', fileId);

        $.ajax({
            url: '{{ route('admin.chat.attachment.destroy') }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $(`#attachment-${fileId}`).remove();
            },
            error: function (xhr, status, error) {
                console.log(error);
                if (xhr.status === 422) {
                    validationErrorPars(xhr)
                }
            }
        });
    }

    function setupFile() {
        fileAttachment.change(function () {
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

        fileAttachment.prop('disabled', true);
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
            success: function (response) {
                attachmentContainer.append(response.htmlRender);
                fileAttachment.prop('disabled', false);
            },
            error: function (xhr, status, error) {
                fileAttachment.prop('disabled', false);
                if (xhr.status === 422) {
                    validationErrorPars(xhr)
                }
            }
        });
    }

    /* Recorder */
    const btnMicrophone = $('#btn-microphone');

    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
    let recorder;
    const audioChunks = [];

    let stateRecord = 'recording';

    $(document).ready(function () {
        setupBtnMicrophone();
    });

    function setupBtnMicrophone() {
        btnMicrophone.on('click', function () {
            if (stateRecord === 'recording') {
                startRecording();
            } else if (stateRecord === 'stop') {
                stopRecording();
            }
        });

        function startRecording() {
            stateRecord = '';
            navigator.mediaDevices.getUserMedia({audio: true})
                .then(function (stream) {
                    recorder = new Recorder(audioContext.createMediaStreamSource(stream), {
                        sampleRate: 16000,
                        numChannels: 1
                    });
                    recorder.record();
                    updateMicrophoneButton('<span class="fal fa-pause"></span>');
                    stateRecord = 'stop';
                })
                .catch(function (err) {
                    stateRecord = 'recording';
                    console.error('Error accessing microphone:', err);
                });
        }

        function stopRecording() {
            stateRecord = '';
            recorder.stop();
            recorder.exportWAV(function (blob) {
                audioChunks.push(blob);
                recorder.clear();
                sendAudioRecorder();
                updateMicrophoneButton('<span class="fal fa-microphone"></span>');
            }, "audio/wav");
        }

        function updateMicrophoneButton(htmlContent) {
            btnMicrophone.html(htmlContent);
        }
    }


    function sendAudioRecorder() {
        if (audioChunks.length > 0) {
            let audioBlob = new Blob(audioChunks, {type: 'audio/wav'});

            let reader = new FileReader();
            reader.onload = function (event) {
                let wavData = new Uint8Array(event.target.result);
                let mp3Blob = convertWavToMp3Blob(wavData);
                let formData = new FormData();

                formData.append('file', mp3Blob, 'recording.mp3');
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
                    success: function (response) {
                        attachmentContainer.append(response.htmlRender);
                        stateRecord = 'recording';
                    },
                    error: function (error) {
                        console.error('Error sending recording:', error);
                        stateRecord = 'recording';
                    }
                });
            };

            reader.readAsArrayBuffer(audioBlob);
        } else {
            console.warn('No audio recording available to send.');
        }
    }

    function convertWavToMp3Blob(wavData) {
        let mp3Encoder = new lamejs.Mp3Encoder(1, audioContext.sampleRate, 64);
        let wavSamples = new Int16Array(wavData.buffer);
        let mp3DataChunks = [];
        let sampleBlockSize = 1152;

        for (let i = 0; i < wavSamples.length; i += sampleBlockSize) {
            let wavChunk = wavSamples.subarray(i, i + sampleBlockSize);
            let mp3Buffer = mp3Encoder.encodeBuffer(wavChunk);

            if (mp3Buffer.length > 0) {
                mp3DataChunks.push(new Int8Array(mp3Buffer));
            }
        }

        let finalMp3Buffer = mp3Encoder.flush();
        if (finalMp3Buffer.length > 0) {
            mp3DataChunks.push(new Int8Array(finalMp3Buffer));
        }

        return new Blob(mp3DataChunks, {type: 'audio/mp3'});
    }
</script>