<div class="card">
    <div class="card-header">
        <h3 class="card-title">پروژه</h3>
        <div class="card-options">
            <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i class="fal fa-chevron-up"></i></a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <tbody>
            <tr>
                <td>شناسه</td>
                <td>{{ $project->id }}</td>
            </tr>
            <tr>
                <td>عنوان پروژه</td>
                <td>{{ $project->title }}</td>
            </tr>
            @if(hasAdminPermission('PROJECT_PRICE_SHOW'))
                <tr>
                    <td>مبلغ پروژه (ریال)</td>
                    <td>{{ number_format($project->price) }}</td>
                </tr>
            @endif
            <tr>
                <td>دامنه</td>
                <td>{{ $project->domain }}</td>
            </tr>
            <tr>
                <td>کارشناس فروش</td>
                <td>
                    <a href="{{ route('admin.admin.show',$project->admin_id) }}">{{ $project->admin->first_name }} {{ $project->admin->last_name }}</a>
                </td>
            </tr>
            <tr>
                <td>کارشناس فروش (موبایل)</td>
                <td>{{ $project->admin->mobile }}</td>
            </tr>
            <tr>
                <td>کارشناس فروش (ایمیل)</td>
                <td>{{ $project->admin->email }}</td>
            </tr>
            <tr>
                <td>کارفرما</td>
                <td>
                    <a href="{{ route('admin.user.show',$project->user_id) }}">{{ $project->user->first_name }} {{ $project->user->last_name }}</a>
                </td>
            </tr>
            <tr>
                <td>کارفرما (موبایل)</td>
                <td>{{ $project->user->mobile }}</td>
            </tr>
            <tr>
                <td>نوع</td>
                <td>{{ $project->type->title }}</td>
            </tr>
            <tr>
                <td>پایه</td>
                <td>{{ $project->base->title }}</td>
            </tr>
            <tr>
                <td>وضعیت پروژه</td>
                <td>{{ $project->status->title }}</td>
            </tr>
            @if($project->businessDomain)
                <tr>
                    <td>زمینه کاری</td>
                    <td>{{ $project->businessDomain->title }}</td>
                </tr>
            @endif

            <tr>
                <td>زمینه کاری (متنی)</td>
                <td>{{ $project->business_domain }}</td>
            </tr>
            <tr>
                <td>تاریخ قرارداد</td>
                <td>{{ $project->agreement_at->toJalali()->format(formatJalaliDate()) }}</td>
            </tr>
            @if($project->deadline_at)
                <tr>
                    <td>تاریخ تحویل</td>
                    <td>{{ $project->deadline_at->toJalali()->format(formatJalaliDate()) }}</td>
                </tr>
            @endif
            <tr>
                <td>تاریخ ایجاد</td>
                <td>{{ $project->created_at->toJalali()->format(formatJalaliDateTime()) }}</td>
            </tr>
            <tr>
                <td>تاریخ به روزرسانی</td>
                <td>{{ $project->updated_at->toJalali()->format(formatJalaliDateTime()) }}</td>
            </tr>
            <tr>
                <td>توضیحات</td>
                <td>{{ $project->note }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>