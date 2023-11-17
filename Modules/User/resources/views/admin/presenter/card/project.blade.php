<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">پروژه ها</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            @if($projects->isNotEmpty())
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>شناسه</th>
                    <th>نام پروژه</th>
                    <th>تاریخ ایجاد</th>
                    <th>عملیت</th>
                </tr>
                </thead>
                <tbody>

                    @foreach($projects as $project)
                        <tr>
                            <td>{{ $project->id }}</td>
                            <td>{{ $project->title }}</td>
                            <td>{{ $project->created_at->toJalali()->format(formatJalaliDateTime()) }}</td>
                            <td>
                                <a target="_blank" class="btn btn-info btn-sm" href="{{ route('admin.project.'.getRouteProjectType($project->project_base_id).'.show',$project->id) }}">نمایش</a>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            @else
                <div class="alert alert-info">
                    <p>پروژه ای متصل نشده</p>
                </div>
            @endif
        </div>
    </div>
</div>