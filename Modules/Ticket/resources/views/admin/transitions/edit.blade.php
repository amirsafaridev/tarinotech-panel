@php use App\Enums\Assets\StyleLoader; @endphp
@php use App\Enums\Assets\ScriptLoader; @endphp
@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
    @include('admin.partial.loader.style',[
        'load'=>[
            StyleLoader::Toast(),
            StyleLoader::Alert(),
        ]
    ])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.ticket.transitions.index') }}">انتقال وضعیت‌ها</a>
                </li>
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-3">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post"
                          action="{{ route('admin.ticket.transitions.update', $ticketTransition->id) }}">
                        @csrf
                        @method('put')

                        <x-admin.select-model identify="from_status_id"
                                              title="وضعیت مبدا"
                                              key="id"
                                              value="name"
                                              :items="$statuses"
                                              :old="$ticketTransition->from_status_id"/>

                        <x-admin.select-model identify="to_status_id"
                                              title="وضعیت مقصد"
                                              key="id"
                                              value="name"
                                              :items="$statuses"
                                              :old="$ticketTransition->to_status_id"/>

                        <x-admin.select-model identify="event_id"
                                              title="رویداد"
                                              key="id"
                                              value="name"
                                              :items="$events"
                                              :old="$ticketTransition->event_id"/>

                        <x-admin.input identify="days_trigger"
                                       title="تعداد روز (اختیاری)"
                                       type="number"
                                       min="0"
                                       :old="$ticketTransition->days_trigger"/>
                        <div class="form-text">در صورتی که رویداد زمانی است، تعداد روزهای مورد نیاز را وارد کنید</div>

                        <x-admin.checkbox identify="is_active"
                                          description="فعال"
                                          :old="$ticketTransition->is_active"/>

                        <x-admin.button title="{{ trans('panel.update') }}"/>

                        <x-admin.button title="{{ trans('panel.delete') }}" type="button" color="danger"
                                        on-click="confirmDelete()"/>
                    </form>

                    <form id="deleteItem" action="{{ route('admin.ticket.transitions.destroy', $ticketTransition) }}"
                          method="post" class="form-inline">
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
            ScriptLoader::Alert(),
        ],
    ])
    @include('admin.partial.request')
    <script>
        $(document).ready(function () {
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
