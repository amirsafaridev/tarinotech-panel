<div class="table-responsive">
    <table class="table table-hover">
        <tbody>
        <tr>
            <td>شناسه</td>
            <td>{{ $factor->id }}</td>
        </tr>
        <tr>
            <td>عنوان</td>
            <td>{{ $factor->title }}</td>
        </tr>
        <tr>
            <td>کارشناس</td>
            <td>
                <a href="{{ route('admin.admin.show',$factor->admin_id) }}">{{ $factor->admin->first_name }} {{ $factor->admin->last_name }}</a>
            </td>
        </tr>
        <tr>
            <td>شناسه یکتا</td>
            <td>{{ $factor->identify }}</td>
        </tr>
        <tr>
            <td>شناسه تراکنش</td>
            <td>{{ $factor->transaction_id ?? 'بدونه شناسه' }}</td>
        </tr>
        <tr>
            <td>پروژه</td>
            <td>
                @php
                    $typeProject;
                    switch ($factor->project->project_base_id){
                        case \App\Enums\Database\Project\ProjectBase::Web:{
                            $typeProject = 'web';
                            break;
                        }
                        case \App\Enums\Database\Project\ProjectBase::Seo:{
                            $typeProject = 'seo';
                            break;
                        }
                        case \App\Enums\Database\Project\ProjectBase::Ads:{
                            $typeProject = 'ads';
                            break;
                        }
                    }
                @endphp
                <a href="{{ route('admin.project.'.$typeProject.'.show',$factor->project_id) }}">{{ $factor->project->title }}</a>
            </td>
        </tr>
        <tr>
            <td>قیمت نهایی (ریال)</td>
            <td>{{ number_format($factor->final_price) }}</td>
        </tr>
        <tr>
            <td>وضعیت</td>
            <td>{{ \App\Enums\Database\Factor\FactorStatus::getDescription($factor->status) }}</td>
        </tr>
        <tr>
            <td>به صوری رسمی</td>
            <td>@include('admin.partial.bool_badge',['value'=> $factor->is_official])</td>
        </tr>
        <tr>
            <td>تاریخ انقضاء</td>
            <td>{{ verta($factor->expired_at)->format(formatJalaliDate()) }}</td>
        </tr>
        <tr>
            <td>تاریخ پرداخت</td>
            <td>{{ $factor->paid_at ? verta($factor->paid_at)->format(formatJalaliDateTime()) : 'پرداخت نشده' }}</td>
        </tr>
        <tr>
            <td>تاریخ ایجاد</td>
            <td>{{ verta($factor->created_at)->format(formatJalaliDateTime()) }}</td>
        </tr>

        </tbody>
    </table>
</div>