<script>
    let table = $('#data-table').DataTable({
        "order": [[0, "desc"]],
        language: {
            url: '{{ asset('res-admin/assets/plugins/datatable/persian.json') }}'
        },
    });
</script>
