@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
       \App\Enums\Assets\StyleLoader::Datepicker(),
       \App\Enums\Assets\StyleLoader::Select2(),
   ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">گزارش ورود</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item active">گزارش ورود</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    @include('admin.partial.message')
                    <form method="get" action="{{ route('admin.report.login') }}">
                        <div class="row mb-3">

                            <div class="col-12 col-md-3">
                                <div>
                                    <label for="user_type" class="form-label">نوع کاربر</label>
                                    <select  class="form-control" name="user_type" id="user_type">
                                        <option value="admin">پرسنل</option>
                                        <option value="user">کاربر</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <div>
                                    <label for="user_id" class="form-label">انتخاب کاربر</label>
                                    <select  class="form-control" name="user_id" id="user_id">
                                        <option value="">همه کاربران</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <div>
                                    <label for="sort" class="form-label">مرتب سازی</label>
                                    <select  class="form-control" name="sort" id="sort">
                                        <option value="date-desc">تاریخ - صعودی</option>
                                        <option value="date-asc">تاریخ - نزولی</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <x-admin.input identify="start_at" title="از تاریخ" type="text" :is-date-picker="true" />
                            </div>

                            <div class="col-12 col-md-3">
                                <x-admin.input identify="end_at" title="تا تاریخ" type="text" :is-date-picker="true"/>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-12 col-md-3">
                                <button class="btn btn-md btn-success" name="action" value="filter">اعمال فیلتر</button>
                                <button class="btn btn-md btn-primary" name="action" value="excel">خروجی اکسل</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">نتایج جستجو</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table border text-nowrap text-md-nowrap table-striped mb-0">
                            <thead>
                            <tr>
                                <th>نوع کاربر</th>
                                <th>نام و نام حانوادگی</th>
                                <th>تاریخ ورود</th>
                                <th>IP</th>
                                <th>عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($logins as $login)
                                    <tr>
                                        <td>
                                            @include('admin.partial.detect_user_type',['type'=>$login->user_type ])
                                        </td>
                                        <td>{{ $login->user->first_name }} {{ $login->user->last_name }}</td>
                                        <td>{{ $login->login_at->toJalali()->format('d F Y - H:i') }}</td>
                                        <td>{{ $login->ip }}</td>
                                        <td>
                                            <a class="btn btn-success btn-sm" href="{{ route('admin.report.login-show',$login->id) }}">نمایش</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{ $logins->appends(request()->query())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
       \App\Enums\Assets\ScriptLoader::Datepicker(),
       \App\Enums\Assets\ScriptLoader::Select2(),
   ]])
    <script>

        // Define route
        const adminSelect2Url = '{{ route('admin.admin.ajax.select2.admin') }}';
        const userSelect2Url = '{{ route('admin.admin.ajax.select2.user') }}';
        let select2Remote = adminSelect2Url; // Default to admin URL

        $(document).ready(function (){
            makePersianDatePicker();
            makeSelect2Init();

            $('#user_type').change(function () {
                select2Remote = ($(this).val() === 'admin') ? adminSelect2Url : userSelect2Url;
            });

        })

        function makeSelect2Init() {
            $('#user_id').select2({
                ajax: {
                    url: select2Remote,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            term: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
                dir: 'rtl',
                language: 'fa',
                minimumInputLength: 3,
                templateResult: formatResult,
                templateSelection: formatSelection
            });
        }

        function formatResult(result) {
            if (!result.id) {
                return result.text;
            }
            return result.first_name + ' ' + result.last_name + ' (' + result.email + ')';
        }

        function formatSelection(result) {
            if (!result.id) {
                return result.text;
            }
            return result.first_name + ' ' + result.last_name + ' (' + result.email + ')';
        }

        function makePersianDatePicker() {
            jalaliDatepicker.startWatch();
        }
    </script>
@endsection
