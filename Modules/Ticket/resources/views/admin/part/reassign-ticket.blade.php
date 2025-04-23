<div class="card mt-3">
    <div class="card-header">
        <h3 class="card-title">انتقال تیکت به ادمین دیگر</h3>
    </div>
    <div class="card-body">
        <form id="reassignForm" action="{{ route('admin.ticket.reassign', $chat->id) }}" method="POST" class="request-form">
            @csrf
            
            <div class="form-group">
                <label for="admin_id" class="form-label">انتخاب پشتیبان</label>
                <select name="admin_id" id="admin_id" class="form-control select2">
                    @foreach($availableAdmins as $admin)
                        <option value="{{ $admin->id }}" {{ $ticketDetail->assigned_to == $admin->id ? 'selected' : '' }}>
                            {{ $admin->display_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary btn-sm w-100 has-spinner">
                    <i class="fe fe-user-check me-1"></i> انتقال تیکت
                </button>
            </div>
        </form>
    </div>
</div> 