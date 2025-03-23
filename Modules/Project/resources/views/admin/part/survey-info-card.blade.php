@if($project->survey && $project->survey->survey)
    <div class="card mb-3">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title">اطلاعات نظرسنجی</h3>
            <a href="{{ route('admin.survey.meta.index', ['surveyable_type'=>'Project','surveyable_id'=>$project->id]) }}" class="btn btn-sm btn-primary btn-sm">
                <i class="fe fe-edit"></i> ویرایش
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <td style="width: 200px;">عنوان نظرسنجی</td>
                        <td>{{ $project->survey->survey->title }}</td>
                    </tr>
                    @if($project->survey->survey->description)
                        <tr>
                            <td>توضیحات</td>
                            <td>{{ $project->survey->survey->description }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td>وضعیت</td>
                        <td>
                            @if($project->survey->survey->is_active)
                                <span class="badge bg-success">فعال</span>
                            @else
                                <span class="badge bg-danger">غیرفعال</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>تاریخ شروع</td>
                        <td>{{ $project->survey->survey->start_date ? $project->survey->survey->start_date->toJalali()->format(formatJalaliDate()) : 'تعیین نشده' }}</td>
                    </tr>
                    <tr>
                        <td>تاریخ پایان</td>
                        <td>{{ $project->survey->survey->end_date ? $project->survey->survey->end_date->toJalali()->format(formatJalaliDate()) : 'تعیین نشده' }}</td>
                    </tr>
                    <tr>
                        <td>تعداد سوالات</td>
                        <td>{{ $project->survey->survey->questions->count() }}</td>
                    </tr>
                    <tr>
                        <td>تعداد پاسخ‌ها</td>
                        <td>{{ $project->survey->survey->responses->count() }}</td>
                    </tr>
                    <tr>
                        <td>توکن دسترسی</td>
                        <td>
                            <div class="input-group">
                                <input type="text" class="form-control" id="survey-token" value="{{ route('survey.public.show',[$project->survey->survey->access_token,'meta'=>$project->survey->access_token]) }}" readonly>
                                <button class="btn btn-sm btn-outline-secondary" type="button" onclick="copyToClipboard('survey-token')">
                                    <i class="fal fa-copy"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="mt-3">
                <a href="{{ route('admin.survey.report.index', $project->survey->survey->id) }}" class="btn btn-sm btn-primary">
                    <i class="fe fe-eye"></i> مشاهده جزئیات نظرسنجی
                </a>

                @if($project->survey->survey->is_active)
                    <a href="{{ route('admin.survey.response.index', $project->survey->survey->id) }}" class="btn btn-sm btn-info">
                        <i class="fa fa-list"></i> مشاهده پاسخ‌ها
                    </a>
                @endif
            </div>
        </div>
    </div>
@else
    <div class="card mb-3">
        <div class="card-header">
            <h3 class="card-title">نظرسنجی</h3>
        </div>
        <div class="card-body">
            <p class="text-muted mb-3">هیچ نظرسنجی برای این پروژه ثبت نشده است.</p>
            <a href="{{ route('admin.survey.meta.index', ['surveyable_type'=>'Project','surveyable_id'=>$project->id]) }}" class="btn btn-sm btn-primary">
                <i class="fe fe-plus"></i> افزودن نظرسنجی
            </a>
        </div>
    </div>
@endif

<script>
    function copyToClipboard(elementId) {
        const copyText = document.getElementById(elementId);
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
    }
</script>
