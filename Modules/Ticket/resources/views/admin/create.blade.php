@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
       \App\Enums\Assets\StyleLoader::Toast(),
       \App\Enums\Assets\StyleLoader::Select2(),
   ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.ticket.index') }}">تیکت ها</a></li>
                <li class="breadcrumb-item active">ایجاد تیکت</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-8 col-md-8 col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">ایجاد تیکت جدید</h3>
                </div>
                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.ticket.store') }}">
                        @csrf

                        <x-admin.input identify="title" title="عنوان تیکت" />

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user_id">کاربر</label>
                                    <select name="user_id" id="user_id" class="form-control select2 @error('user_id') is-invalid @enderror" required>
                                        <option value="">انتخاب کاربر</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->first_name }} {{ $user->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="project_id">پروژه</label>
                                    <select name="project_id" id="project_id" class="form-control select2 @error('project_id') is-invalid @enderror">
                                        <option value="">انتخاب پروژه</option>
                                    </select>
                                    @error('project_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <x-admin.select-model
                                    title="موضوع تیکت"
                                    identify="subject_id"
                                    :items="$subjects"
                                    key="id"
                                    value="title"
                                    :is-select2="true" />
                            </div>
                            <div class="col-md-6">
                                <x-admin.select-model
                                    title="وضعیت تیکت"
                                    identify="status_id"
                                    :items="$statuses"
                                    key="id"
                                    value="name"
                                    :is-select2="true" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <x-admin.select-model
                                    title="اولویت تیکت"
                                    identify="priority_id"
                                    :items="$priorities"
                                    key="id"
                                    value="name"
                                    :is-select2="true" />
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="assigned_to">پشتیبان</label>
                                    <select name="assigned_to" id="assigned_to" class="form-control select2">
                                        <option value="">انتخاب پشتیبان (خودم)</option>
                                        @foreach(\Modules\Admin\app\Models\Admin::orderBy('first_name')->get() as $admin)
                                            <option value="{{ $admin->id }}" {{ old('assigned_to') == $admin->id ? 'selected' : '' }}>
                                                {{ $admin->first_name }} {{ $admin->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('assigned_to')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="initial_message">پیام اولیه</label>
                            <textarea name="initial_message" id="initial_message" rows="5" class="form-control @error('initial_message') is-invalid @enderror">{{ old('initial_message') }}</textarea>
                            @error('initial_message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <x-admin.button title="{{ trans('panel.create') }}"/>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">راهنمای ایجاد تیکت</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5 class="alert-heading">راهنمای ایجاد تیکت</h5>
                        <p>برای ایجاد تیکت جدید، فرم مقابل را با دقت تکمیل کنید:</p>
                        <hr>
                        <ol class="mb-0">
                            <li>یک عنوان مناسب برای تیکت انتخاب کنید.</li>
                            <li>ابتدا کاربر مورد نظر را انتخاب کنید.</li>
                            <li>سپس از پروژه‌های کاربر، پروژه مورد نظر را انتخاب کنید.</li>
                            <li>موضوع، وضعیت و اولویت تیکت را مشخص کنید.</li>
                            <li>در صورت تمایل، می‌توانید پشتیبان مشخصی را انتخاب کنید (اگر انتخاب نکنید، خودتان به عنوان پشتیبان انتخاب می‌شوید).</li>
                            <li>یک پیام اولیه برای شروع گفتگو وارد کنید.</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
       \App\Enums\Assets\ScriptLoader::Select2(),
   ]])
    @include('admin.partial.request')
    <script>
        $(document).ready(function (){
            $('.select2').select2();
        });
    </script>
    <script>
        // Handle user selection change
        $(document).ready(function (){
            $('#user_id').on('change', function() {
                var userId = $(this).val();
                var projectSelect = $('#project_id');

                // Clear existing options
                projectSelect.empty().append('<option value="">انتخاب پروژه</option>');

                if (userId) {
                    // Load projects for the selected user
                    $.ajax({
                        url: "{{ route('admin.ajax.project.remote-select-by-user') }}",
                        type: 'GET',
                        data: {
                            user_id: userId
                        },
                        success: function(data) {
                            if (data && data.length > 0) {
                                $.each(data, function(index, project) {
                                    projectSelect.append('<option value="' + project.id + '">' + project.title + '</option>');
                                });
                            }
                            projectSelect.trigger('change');
                        },
                        error: function(xhr) {
                            console.error('Error loading projects:', xhr);
                        }
                    });
                }
            });
        });
    </script>
@endsection
