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
                <li class="breadcrumb-item"><a href="{{ route('admin.ticket.statuses.index') }}">وضعیت تیکت ها</a></li>
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-3">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.ticket.statuses.update', $ticketStatus) }}">
                        @csrf
                        @method('PATCH')

                        <x-admin.input identify="name" title="نام وضعیت" :old="$ticketStatus->name"/>

                        <div class="mb-3">
                            <label for="color" class="form-label">رنگ</label>
                            <input type="color" 
                                   class="form-control" 
                                   name="color" 
                                   id="color" 
                                   value="{{ old('color', $ticketStatus->color) }}" />
                        </div>

                        <x-admin.input identify="order" title="ترتیب" type="number" :old="old('order', $ticketStatus->order)"/>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_auto_changing" id="is_auto_changing" value="1" {{ old('is_auto_changing', $ticketStatus->is_auto_changing) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_auto_changing">تغییر خودکار وضعیت</label>
                            </div>
                        </div>

                        <x-admin.textarea identify="description" title="توضیحات" :old="$ticketStatus->description"/>

                        <x-admin.button title="{{ trans('panel.update') }}"/>

                        <x-admin.button title="{{ trans('panel.delete') }}" type="button" color="danger" on-click="confirmDelete()"/>
                    </form>

                    <form id="deleteItem" action="{{ route('admin.ticket.statuses.destroy', $ticketStatus) }}" method="post" class="form-inline">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',[
        'load'=>[
            \App\Enums\Assets\ScriptLoader::Alert(),
        ],
    ])
    @include('admin.partial.request')
    <script>
        $(document).ready(function (){
            activeParentUl('{{ route('admin.ticket.statuses.index') }}');
        });

        function confirmDelete() {
            Swal.fire({
                title: 'آیا مطمئن هستید؟',
                text: "این عملیات قابل بازگشت نیست!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'بله، حذف شود!',
                cancelButtonText: 'خیر'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteItem').submit();
                }
            })
        }
    </script>
@endsection