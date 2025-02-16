<script>
    $('.quill-editor').each(function(i) {
        let el = $(this), id = 'quilleditor-' + i, val = el.val(), editor_height = 200;
        let div = $('<div/>').attr('id', id).css('height', editor_height + 'px').html(val);
        el.addClass('d-none');
        el.parent().append(div);

        let quill = new Quill('#' + id, {
            modules: { toolbar: true },
            theme: 'snow'
        });
        quill.on('text-change', function() {
            el.val(quill.root.innerHTML);
        });
    });
</script>
