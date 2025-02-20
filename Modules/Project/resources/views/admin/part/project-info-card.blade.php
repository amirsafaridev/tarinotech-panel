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
            @role(\App\Enums\Database\Role\RoleName::SUPER_ADMIN)
            <tr>
                <td>کارشناس فروش (موبایل)</td>
                <td>{{ $project->admin->mobile }}</td>
            </tr>
            <tr>
                <td>کارشناس فروش (ایمیل)</td>
                <td>{{ $project->admin->email }}</td>
            </tr>
            @endrole
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

            @if($project->facilities->isNotEmpty())
                <tr>
                    <td>ویژگی‌ها</td>
                    <td>
                        <span>{{ $project->facilities->pluck('title')->implode(', ') }}</span>
                    </td>
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
            @can('ADMIN_PROJECT_RENEWAL')
                @php
                    $renewalStatus = $project->getRenewalStatus();
                @endphp
                <tr>
                    <td>وضعیت تمدید</td>
                    <td>
                        @if($renewalStatus['canRenew'])
                            <a class="btn btn-success" href="{{ route('admin.project.renewal',$project->id) }}">
                                ایجاد تمدید
                            </a>
                        @else
                            <div class="alert alert-warning">
                                @if($renewalStatus['message'])
                                    <i class="fa fa-exclamation-triangle"></i>
                                    {{ $renewalStatus['message'] }}
                                @else
                                    <i class="fa fa-clock-o"></i>
                                    {{ $renewalStatus['daysUntilRenewal'] }} روز تا زمان تمدید باقی مانده است
                                    <br>
                                    <small class="text-muted">
                                        تاریخ تمدید: {{ verta($renewalStatus['renewalDate'])->format('Y/m/d') }}
                                    </small>
                                @endif
                            </div>
                        @endif
                    </td>
                </tr>
            @endcan
            </tbody>
        </table>
    </div>
</div>
