<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">قرارداد - مشتری</h3>
    </div>
    <div class="card-body">
        @if($project?->target?->userSignable)
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>وضعیت</th>
                    <th>درخواست</th>
                    <th>آخرین تغییر</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{!! \App\Helpers\Helper::renderUserSignableStatus($project->target->userSignable->status) !!}</td>
                    <td>{{ $project->target->userSignable->created_at->toJalali()->format(formatJalaliDateTime())  }}</td>
                    <td>{{ $project->target->userSignable->updated_at->toJalali()->format(formatJalaliDateTime())  }}</td>
                </tr>
                </tbody>
            </table>
        @endif

        <p>ضمیمه ها</p>
    </div>
</div>