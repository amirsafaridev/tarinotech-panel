@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',[
        'load'=>[
            \App\Enums\Assets\StyleLoader::Toast(),
            \App\Enums\Assets\StyleLoader::Alert(),
        ]
    ])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.contract.sign.index') }}">درخواست ها</a></li>
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-3">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.contract.sign.update',$signable->id) }}">
                        @csrf
                        @method('PATCH')

                        <x-admin.input identify="id" type="hidden" :old="$signable->id"/>

                        <x-admin.select-enum identify="status" title="وضغیت امضاء"
                                             :enum-class="\Modules\Contract\app\Enums\SignableStatus::class"
                                             :old="$signable->status"
                        />

                        <x-admin.textarea identify="note" :rows="6" :old="$signable->note" placeholder="توضیحات"/>

                        <x-admin.button title="{{ trans('panel.update') }}"/>

                        <x-admin.button title="{{ trans('panel.delete') }}" type="button" color="danger" on-click="confirmDelete()"/>

                    </form>

                    <form id="deleteItem" action="{{ route('admin.contract.sign.destroy',$signable->id) }}" method="post" class="form-inline mb-4">
                        @csrf
                        @method('DELETE')
                    </form>

                    @if($signable->files)
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>تاریخ</th>
                                <th>دانلود</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($signable->files as $file)
                                <tr>
                                    <td>{{ $file->created_at->toJalali()->format(formatJalaliDateTime()) }}</td>
                                    <td>
                                        <a href="{{ route('admin.contract.file.download', $file->id) }}" class="btn btn-success btn-sm">دانلود PDF</a>
                                        <form action="{{ route('admin.contract.file.destroy', $file->id) }}" method="POST" class="d-inline" id="delete-form-{{ $file->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmFileDelete({{ $file->id }})">حذف</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
    \App\Enums\Assets\ScriptLoader::Alert(),
    ]])
    @include('admin.partial.request')

    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.contract.sign.index') }}');
        })
    </script>

    <script>
        function confirmFileDelete(fileId) {
            swal({
                title: 'آیا مطمئن هستید؟',
                text: "این عمل قابل بازگشت نیست!",
                type: "warning",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#ff0f3b',
                confirmButtonText: 'بله، حذف کن!',
                cancelButtonText: 'خیر، انصراف'
            }, function(){
                $('#delete-form-' + fileId).submit();
            });
        }
    </script>
@endsection
