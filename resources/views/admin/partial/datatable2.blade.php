<script>
    let table = $('#data-table').DataTable({
        processing: true,
        serverSide: true,
        responsive:true,
        bAutoWidth : false,
        language: {
            url: '{{ asset('res-admin/assets/plugins/datatable/persian.json') }}'
        },
        ajax: '{{ $routeData }}',
        "columns": [
            @foreach ($columns as $column)
                {
                    'data': '{{ $column['name'] }}',
                    'name': '{{ $column['name'] }}',
                    @if ($column['sortable'])
                        "bSortable": true,
                    @else
                        "bSortable": false,
                    @endif

                    @if ($column['searchable'])
                        "searchable": true,
                    @else
                        "searchable": false,
                    @endif
                },
            @endforeach
        ],
        "columnDefs": [
            @foreach ($columns as $column)
                @if($column['name'] == 'is_seen')
                {
                    "targets": parseInt({{$loop->index}}),
                    "data": "{{ $select }}",
                    "render": function (data, type, row, meta) {
                        if (data === 0) {
                            return '<span class="badge bg-danger">دیده نشده</span>';
                        } else {
                            return '<span class="badge bg-success">دیده شده</span>';
                        }
                    }
                },
                @endif
                @if($column['name'] == 'is_active')
                {
                    "targets": parseInt({{$loop->index}}),
                    "data": "{{ $select }}",
                    "render": function (data, type, row, meta) {
                        if (data === 0) {
                            return '<span class="badge bg-danger">غیر فعال</span>';
                        } else {
                            return '<span class="badge bg-success">فعال</span>';
                        }
                    }
                },
                @endif

                @if($column['name'] == 'is_publish' || $column['name'] == 'is_public')
                {
                    "targets": parseInt({{$loop->index}}),
                    "data": "{{ $select }}",
                    "render": function (data, type, row, meta) {
                        if (data === 0) {
                            return '<span class="badge bg-info">عدم انتشار</span>';
                        } else {
                            return '<span class="badge bg-success">منتشر شده</span>';
                        }
                    }
                },
                @endif
                @if($column['name'] == 'is_block')
                {
                    "targets": parseInt({{$loop->index}}),
                    "data": "{{ $select }}",
                    "render": function (data, type, row, meta) {
                        if (data === 1) {
                            return '<span class="badge bg-danger">بلاک شده</span>';
                        } else {
                            return '<span class="badge bg-success">اکانت فعال</span>';
                        }
                    }
                },
                @endif
                @if($column['name'] == 'in_home')
                {
                    "targets": parseInt({{$loop->index}}),
                    "data": "{{ $select }}",
                    "render": function (data, type, row, meta) {
                        if (data === 0) {
                            return '<span class="badge bg-danger">بدون نمایش</span>';
                        } else {
                            return '<span class="badge bg-success">نمایش</span>';
                        }
                    }
                },
                @endif
                @if($column['name'] == 'rate')
                {
                    "targets": parseInt({{$loop->index}}),
                    "data": "{{ $select }}",
                    "render": function (data, type, row, meta) {
                        return '<img src="{{ env('app_url') }}/res-share/rates/'+data+'.jpg" />';
                    }
                },
                @endif
            @endforeach
        ],


        "order": [[0, "desc"]],
    });
    function jsNumberFormat(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
</script>
