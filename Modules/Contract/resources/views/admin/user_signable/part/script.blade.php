<script>
    const userSignableForm = $('#userSignableForm');
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    Dropzone.options.myDropzone = {
        paramName: "file",
        maxFilesize: 10, // Maximum file size in MB
        acceptedFiles: "image/*", // Accept only image files
        dictDefaultMessage: "فایل‌ها را اینجا بکشید و رها کنید یا برای آپلود کلیک کنید",
        dictFileTooBig: "اندازه فایل بزرگ‌تر از حد مجاز است. حداکثر اندازه: 10 مگابایت.",
        dictInvalidFileType: "این نوع فایل مجاز نیست.",
        addRemoveLinks: true,
        dictRemoveFile: "حذف فایل",
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },

        init: function() {
            this.on("success", handleSuccess);
            this.on("removedfile", handleFileRemoval);
            this.on("error", handleError);
        }
    };

    function handleSuccess(file, response) {
        const attachmentId = response.attachment.ulid;
        const hiddenInput = $('<input>')
            .attr('type', 'hidden')
            .attr('name', 'attachments[]')
            .attr('value', attachmentId);

        userSignableForm.append(hiddenInput);
        file.attachmentId = attachmentId;
    }

    function handleFileRemoval(file) {
        const attachmentId = file.attachmentId;

        if (attachmentId) {
            const confirmDelete = confirm("آیا مطمئن هستید که می‌خواهید این فایل را حذف کنید؟");

            if (confirmDelete) {
                $.ajax({
                    url: '{{ route('admin.contract.attachment.destroy') }}',
                    type: 'DELETE',
                    data: {
                        id: attachmentId
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(result) {
                        $('input[name="attachment[]"][value="' + attachmentId + '"]').remove();
                        console.log("فایل با موفقیت حذف شد.");
                    },
                    error: function(xhr) {
                        console.log("خطا در حذف فایل.");
                    }
                });
            }
        }
    }

    function handleError(file, errorMessage) {
        console.log("خطا در آپلود فایل: ", errorMessage);
    }
</script>