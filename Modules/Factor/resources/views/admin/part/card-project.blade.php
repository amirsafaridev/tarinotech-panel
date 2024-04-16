<div class="card">
    <div class="card-header">
        <span class="bold">اطلاعات پروژه</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <tbody>
                @if($factor->project)
                    <tr>
                        <td>کارفرما</td>
                        <td>
                            <a href="{{ route('admin.user.show',$factor->project->user_id) }}">{{ $factor->project->user->first_name }} {{ $factor->project->user->last_name }}</a>
                        </td>
                    </tr>
                    <tr>
                        <td>نوع شخص</td>
                        <td>
                            {{ \Modules\User\app\Enums\PersonType::getDescription($factor->project->user->person_type) }}
                        </td>
                    </tr>
                    <tr>
                        <td>پروژه</td>
                        <td>
                            @php
                                $typeProject;
                                switch ($factor->project->project_base_id){
                                    case \Modules\Project\app\Enums\ProjectBase::Web:{
                                        $typeProject = 'web';
                                        break;
                                    }
                                    case \Modules\Project\app\Enums\ProjectBase::Seo:{
                                        $typeProject = 'seo';
                                        break;
                                    }
                                    case \Modules\Project\app\Enums\ProjectBase::Ads:{
                                        $typeProject = 'ads';
                                        break;
                                    }
                                }
                            @endphp
                            <a href="{{ route('admin.project.manage',$factor->project_id) }}">{{ $factor->project->title }}</a>
                        </td>
                    </tr>
                    <tr>
                        <td>نوع پروژه</td>
                        <td>{{ $factor->project->type->path }}</td>
                    </tr>
                @endif


                @if(!$factor->project && $factor->meta)
                    <tr>
                        <td>عنوان پروژه</td>
                        <td>{{ $factor->meta->project_title }}</td>
                    </tr>
                    <tr>
                        <td>نوع پروژه</td>
                        <td>{{ $factor->meta->type->path }}</td>
                    </tr>
                    <tr>
                        <td>کارفرما</td>
                        <td>{{ $factor->meta->customer_fullname }}</td>
                    </tr>
                    <tr>
                        <td>شماره موبایل</td>
                        <td>{{ $factor->meta->customer_mobile }}</td>
                    </tr>
                @endif

                </tbody>
            </table>
        </div>
    </div>
</div>
