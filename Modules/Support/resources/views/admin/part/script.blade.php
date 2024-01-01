<script>
    const chatGroupContainer = $('#chat-group-container');
    const chatContainer = $('#chat-container');
    let scrollLoad = false;
    let chatPage = 1;

    const loadingMotion = '<div class="d-flex justify-content-center h-4 align-items-center loading"><i class="fal fa-spinner fa-spin"></i></div>';
    $(document).ready(function () {
        fetchChatGroups();
        setScrollPagination();
    })

    function setScrollPagination() {
        chatContainer.scroll(function () {
            let container = $(this);
            if (scrollLoad && container.scrollTop() + container.height() >= container[0].scrollHeight - 100) {
                scrollLoad = false;
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
                if(chatPage < data.pagination.last_page){
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
            scrollLoad = true;
        }, 200)
    }
</script>