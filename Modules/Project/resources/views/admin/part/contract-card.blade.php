<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">قرارداد</h3>
        <div>
            <a class="btn btn-sm btn-info" href="{{ makeRouteContractPreview($project->target_type,$project->target_id) }}">PDF</a>
            <a class="btn btn-sm btn-success" href="{{ makeRouteSignRequest($project->target_type,$project->target_id) }}">درخواست امضا</a>
        </div>
    </div>
    <div class="card-body">
        @if($project->target->signable)
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>تاریخ درخواست</th>
                    <th>تاریخ امضا</th>
                    <th>وضعیت</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{ $project->target->signable->created_at->toJalali()->format(formatJalaliDateTime())  }}</td>
                    @if($project->target->signable->sign_at)
                        <td>{{ $project->target->signable->sign_at->toJalali()->format(formatJalaliDateTime())  }}</td>
                    @else
                        <td>-</td>
                    @endif
                    <td>{{ \Modules\Contract\app\Enums\SignableStatus::getDescription($project->target->signable->status) }}</td>
                </tr>

                </tbody>
            </table>
        @endif
    </div>
</div>