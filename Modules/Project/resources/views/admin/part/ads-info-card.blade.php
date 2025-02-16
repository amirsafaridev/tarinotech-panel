<div class="card">
    <div class="card-header">
        <h3 class="card-title">گوگل ادورز</h3>
        <div class="card-options">
            <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i class="fal fa-chevron-up"></i></a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <tbody>
            <tr>
                <td>شناسه</td>
                <td>{{ $project->target->id }}</td>
            </tr>

            <tr>
                <td>زمینه فعالیت</td>
                <td>{{ $project->target->field_activity }}</td>
            </tr>




            <tr>
                <td>طراحی سایت پروژه</td>
                <td>{{ \Modules\Project\app\Enums\ProjectDesignBy::getDescription($project->target->designed_by) }}</td>
            </tr>

            </tbody>
        </table>
    </div>
</div>