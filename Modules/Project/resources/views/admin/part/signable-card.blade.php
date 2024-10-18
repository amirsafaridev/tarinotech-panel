<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">قرارداد</h3>
        <div>
            <a class="btn btn-sm btn-info" target="_blank" href="{{ makeRouteContractPreview($project->target_type,$project->target_id) }}">PDF</a>
            <a class="btn btn-sm btn-success" href="{{ makeRouteSignRequest($project->target_type,$project->target_id) }}">درخواست امضاء</a>
        </div>
    </div>
    <div class="card-body">
        @if($project->target->signable)
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>وضعیت</th>
                    <th>درخواست</th>
                    <th>امضاء</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{!! \App\Helpers\Helper::renderSignableStatus($project->target->signable->status) !!}</td>
                    <td>{{ $project->target->signable->created_at->toJalali()->format(formatJalaliDateTime())  }}</td>
                    @if($project->target->signable->sign_at)
                        <td>{{ $project->target->signable->sign_at->toJalali()->format(formatJalaliDateTime())  }}</td>
                    @else
                        <td>-</td>
                    @endif

                </tr>

                <tr>
                    <td colspan="3">{{ $project->target->signable->note }}</td>
                </tr>
                </tbody>
            </table>
        @endif

        @if($project->target?->signable?->files)
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>تاریخ</th>
                    <th>دانلود</th>
                </tr>
                </thead>
                <tbody>
                @foreach($project->target->signable->files as $file)
                    <tr>
                        <td>{{ $file->created_at->toJalali()->format(formatJalaliDateTime()) }}</td>
                        <td>
                            <a class="btn btn-success btn-sm" href="">دانلود PDF</a>
                        </td>
                    </tr>

                @endforeach

                </tbody>
            </table>
        @endif
    </div>
</div>
