<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">قرارداد - کارفرما</h3>
    </div>
    <div class="card-body">
        @if($project?->target?->userSignable)
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>وضعیت</th>
                    <th>درخواست</th>
                    <th>آخرین تغییر</th>
                    @can('ADMIN_CONTRACT_SIGN_USER_EDIT')
                        <th>عملیات</th>
                    @endcan
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{!! \App\Helpers\Helper::renderUserSignableStatus($project->target->userSignable->status) !!}</td>
                    <td>{{ $project->target->userSignable->created_at->toJalali()->format(formatJalaliDateTime())  }}</td>
                    <td>{{ $project->target->userSignable->updated_at->toJalali()->format(formatJalaliDateTime())  }}</td>
                    @can('ADMIN_CONTRACT_SIGN_USER_EDIT')
                        <td>
                            <a class="btn btn-sm btn-warning" href="{{ route('admin.contract.sign.user.edit',$project->target->userSignable->id) }}">ویرایش</a>
                        </td>
                    @endcan
                </tr>
                </tbody>
            </table>
        @endif

        @if($project->target?->userSignable?->files)
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>تاریخ</th>
                    <th>دانلود</th>
                </tr>
                </thead>
                <tbody>
                @foreach($project->target->userSignable->files as $file)
                    <tr>
                        <td>{{ $file->created_at->toJalali()->format(formatJalaliDateTime()) }}</td>
                        <td>
                            <a class="btn btn-success btn-sm" target="_blank" href="{{ route('admin.contract.file.download',$file->id) }}">دانلود PDF</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
