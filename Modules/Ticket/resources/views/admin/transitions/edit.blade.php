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
                <li class="breadcrumb-item"><a href="{{ route('admin.ticket.transitions.index') }}">انتقال وضعیت‌های تیکت</a></li>
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-3">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.ticket.transitions.update', $ticketStatusTransition) }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="from_status_id" class="form-label">وضعیت شروع:</label>
                            <select name="from_status_id" id="from_status_id" class="form-control @error('from_status_id') is-invalid @enderror">
                                <option value="">انتخاب کنید</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}" {{ old('from_status_id', $ticketStatusTransition->from_status_id) == $status->id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('from_status_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="to_status_id" class="form-label">وضعیت پایان:</label>
                            <select name="to_status_id" id="to_status_id" class="form-control @error('to_status_id') is-invalid @enderror">
                                <option value="">انتخاب کنید</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}" {{ old('to_status_id', $ticketStatusTransition->to_status_id) == $status->id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('to_status_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <x-admin.input identify="days_until_transition" title="تعداد روز تا انتقال" type="number" :old="old('days_until_transition', $ticketStatusTransition->days_until_transition)"/>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_active" id="is_active" value="1" {{ old('is_active', $ticketStatusTransition->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">فعال</label>
                            </div>
                        </div>

                        <x-admin.button title="{{ trans('panel.update') }}"/>

                        <x-admin.button title="{{ trans('panel.delete') }}" type="button" color="danger" on-click="confirmDelete()"/>
                    </form>

                    <form id="deleteItem" action="{{ route('admin.ticket.transitions.destroy', $ticketStatusTransition) }}" method="post" class="form-inline">
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
            activeParentUl('{{ route('admin.ticket.transitions.index') }}');
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