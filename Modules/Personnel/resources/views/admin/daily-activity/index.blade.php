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
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">درخواست‌های ویرایش ساعت کاری</h3>
                </div>

                <div class="card-body">
                    @include('admin.partial.message')

                    <div class="table-responsive">
                        <table id="data-table" class="table table-bordered text-nowrap">
                            <thead>
                                <tr>
                                    <th>نام کاربر</th>
                                    <th>تاریخ</th>
                                    <th>زمان فعلی شروع</th>
                                    <th>زمان فعلی پایان</th>
                                    <th>زمان درخواستی شروع</th>
                                    <th>زمان درخواستی پایان</th>
                                    <th>دلیل درخواست</th>
                                    <th>تاریخ درخواست</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activities as $activity)
                                    <tr>
                                        <td>{{ $activity->user->name }}</td>
                                        <td>{{ Verta::instance($activity->date)->format('Y/m/d') }}</td>
                                        <td>{{ $activity->start_time ? Verta::instance($activity->start_time)->format('H:i:s') : '-' }}</td>
                                        <td>{{ $activity->end_time ? Verta::instance($activity->end_time)->format('H:i:s') : '-' }}</td>
                                        <td>{{ $activity->edit_request_data['requested_start_time'] }}</td>
                                        <td>{{ $activity->edit_request_data['requested_end_time'] }}</td>
                                        <td>{{ $activity->edit_request_data['reason'] }}</td>
                                        <td>{{ Verta::instance($activity->edit_request_data['requested_at'])->format('Y/m/d H:i:s') }}</td>
                                        <td>
                                            <button type="button" 
                                                    class="btn btn-sm btn-success approve-btn"
                                                    data-activity-id="{{ $activity->id }}">
                                                تایید
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger reject-btn"
                                                    data-activity-id="{{ $activity->id }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#rejectModal">
                                                رد
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $activities->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">رد درخواست ویرایش</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="rejectForm">
                        <input type="hidden" id="rejectActivityId">
                        <div class="mb-3">
                            <label for="rejectReason" class="form-label">دلیل رد درخواست</label>
                            <textarea class="form-control" id="rejectReason" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                    <button type="button" class="btn btn-danger" id="submitReject">ثبت</button>
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
        // Handle approve
        document.querySelectorAll('.approve-btn').forEach(button => {
            button.addEventListener('click', function() {
                const activityId = this.dataset.activityId;
                
                if (confirm('آیا از تایید این درخواست اطمینان دارید؟')) {
                    fetch(`/admin/personnel/active-hours/${activityId}/approve`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
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
                }
            });
        });

        // Handle reject
        const rejectModal = document.getElementById('rejectModal');
        if (rejectModal) {
            rejectModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const activityId = button.getAttribute('data-activity-id');
                document.getElementById('rejectActivityId').value = activityId;
            });

            document.getElementById('submitReject').addEventListener('click', function() {
                const activityId = document.getElementById('rejectActivityId').value;
                const reason = document.getElementById('rejectReason').value;

                if (!reason) {
                    alert('لطفاً دلیل رد درخواست را وارد کنید');
                    return;
                }

                fetch(`/admin/personnel/active-hours/${activityId}/reject`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        reject_reason: reason
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