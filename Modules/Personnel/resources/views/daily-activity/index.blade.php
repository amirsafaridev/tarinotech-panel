@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
    @include('admin.partial.loader.style', ['load' => [\App\Enums\Assets\StyleLoader::DataTable()]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">فعالیت روزانه</h3>

                </div>
                <div class="card-body">
                    @include('admin.partial.message')
                    <div class="table-responsive">
                        <table id="data-table" class="table">
                            <thead>
                                <tr>

                                <th>تاریخ</th>
                                    <th>شروع</th>
                                    <th>پایان</th>
                                    <th>مدت زمان</th>
                                    <th>وضعیت</th>
                                    <th>عملیات</th>

                                </tr>
                            </thead>
                            <tbody>
                                @if ($activities->isNotEmpty())
                                @foreach($activities as $activity)
                                    <tr class="{{ $activity->status === 'incorrect_entry' ? 'table-danger' : '' }}">
                                        <td>{{ Verta::instance($activity->date)->format('Y/m/d') }}</td>
                                        <td>{{ $activity->start_time ? Verta::instance($activity->start_time)->format('H:i:s') : '-' }}</td>
                                        <td>{{ $activity->end_time ? Verta::instance($activity->end_time)->format('H:i:s') : '-' }}</td>
                                        <td>{{ $activity->total_duration ? gmdate('H:i:s', $activity->total_duration) : '-' }}</td>
                                        <td>
                                            @switch($activity->status)
                                                @case('active')
                                                    <span class="badge bg-success">فعال</span>
                                                    @break
                                                @case('inactive')
                                                    <span class="badge bg-secondary">غیرفعال</span>
                                                    @break
                                                @case('incorrect_entry')
                                                    <span class="badge bg-danger">عدم درج صحیح</span>
                                                    @break
                                                @case('absent')
                                                    <span class="badge bg-warning">غایب</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>
                                            @if($activity->canRequestEdit())
                                                <button type="button" 
                                                        class="btn btn-sm btn-primary edit-request-btn"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editRequestModal"
                                                        data-activity-id="{{ $activity->id }}"
                                                        data-activity-date="{{ Verta::instance($activity->date)->format('Y/m/d') }}">
                                                    درخواست ویرایش
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script', ['load' => [\App\Enums\Assets\ScriptLoader::DataTable()]])
    @include('admin.partial.datatable_offline')
    <script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Edit request modal handling
    const editModal = document.getElementById('editRequestModal');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const activityId = button.getAttribute('data-activity-id');
            const activityDate = button.getAttribute('data-activity-date');
            
            document.getElementById('activityId').value = activityId;
            document.getElementById('activityDate').textContent = activityDate;
        });

        document.getElementById('submitEditRequest').addEventListener('click', function() {
            const activityId = document.getElementById('activityId').value;
            const startTime = document.getElementById('startTime').value;
            const endTime = document.getElementById('endTime').value;
            const reason = document.getElementById('editReason').value;

            if (!startTime || !endTime || !reason) {
                alert('لطفاً تمام فیلدها را پر کنید');
                return;
            }

            fetch(`admin/personnel/daily-activity/${activityId}/request-edit`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    start_time: startTime,
                    end_time: endTime,
                    reason: reason
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('خطا در ارتباط با سرور');
            });
        });
    }
});
</script>
@endsection

