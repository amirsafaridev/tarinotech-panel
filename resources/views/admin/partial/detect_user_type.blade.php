@if($type === \App\Models\Admin::class)
    <span class="badge bg-success">پرسنل</span>
@else
    <span class="badge bg-warning">کاربر</span>
@endif