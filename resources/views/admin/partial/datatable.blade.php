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
            @foreach ($selects as $select)
                {
                    'data': '{{ $select }}',
                    'name': '{{ $select }}',
                    @if (isset($skipSort) && in_array($select, $skipSort))
                        "bSortable": false,
                    @else
                        "bSortable": true,
                    @endif

                    @if (isset($skipSearch) && in_array($select, $skipSearch))
                        "searchable": false,
                    @else
                        "searchable": true,
                    @endif
                },
            @endforeach {
                'data': 'action',
                'name': 'action',
                "bSortable": false,
                "searchable": false
            }
        ],
        "columnDefs": [
            @foreach($selects as $select)
                @if($select == 'is_seen')
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
                @if($select == 'is_active')
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

                @if($select == 'is_publish' || $select == 'is_public')
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
                @if($select == 'is_block')
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
                @if($select == 'in_home')
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
                @if($select == 'rate')
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
