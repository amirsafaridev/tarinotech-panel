<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">پروژه ها</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>نام پروژه</th>
                    <th>نوع پروژه</th>
                    <th>دامنه</th>
                    <th>عملیت</th>
                </tr>
                </thead>
                <tbody>
                @if($projects->isNotEmpty())
                    @foreach($projects as $project)
                        <tr>
                            <td>{{ $project->title }}</td>
                            <td>{{ $project->base->title }}</td>
                            <td>{{ $project->domain }}</td>
                            <td>
                                <a target="_blank" class="btn btn-info btn-sm" href="{{ route('admin.project.'.getRouteProjectType($project->project_base_id).'.show',$project->id) }}">نمایش</a>

                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>

        </div>
    </div>
</div>