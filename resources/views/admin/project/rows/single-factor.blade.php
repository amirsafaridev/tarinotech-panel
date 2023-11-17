<div class="table-responsive">
    <table class="table border text-nowrap text-md-nowrap table-hover mb-0">
        <tbody>
        <tr>
            <td>کارفرما</td>
            <td>
                <a href="{{{ route('admin.user.show',$project->user_id) }}}">{{ $project->user->first_name }} {{ $project->user->last_name }}</a>
            </td>
            <td>دامنه</td>
            <td>
                <a target="_blank" href="{{ $project->domain }}">{{ $project->domain }}</a>
            </td>
        </tr>

        <tr>
            <td>عنوان</td>
            <td>{{ $project->title }}</td>
            <td>نوع پروژه</td>
            <td>{{ \App\Enums\Database\Project\ProjectBase::getDescription($project->project_base_id) }}</td>

        </tr>
        <tr>
            <td>نوع شخص</td>
            <td>{{ \Modules\User\app\Enums\PersonType::getDescription($project->user->person_type) }}</td>
        </tr>

        </tbody>
    </table>
</div>