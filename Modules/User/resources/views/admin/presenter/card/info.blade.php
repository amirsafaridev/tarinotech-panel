<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">اطلاعات کاربر</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <tbody>
                <tr>
                    <td>شناسه</td>
                    <td>{{ $user->id }}</td>
                </tr>
                <tr>
                    <td>موبایل</td>
                    <td>{{ $user->mobile }}</td>
                </tr>
                <tr>
                    <td>نام</td>
                    <td>{{ $user->first_name }}</td>
                </tr>
                <tr>
                    <td>نام خانوادگی</td>
                    <td>{{ $user->last_name }}</td>
                </tr>

                <tr>
                    <td>تلفن</td>
                    <td>
                        <a href="tel:{{ $user->tel }}">{{ $user->tel }}</a>
                    </td>
                </tr>
                <tr>
                    <td>ایمیل</td>
                    <td>
                        <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                    </td>
                </tr>

                <tr>
                    <td>تایید شده در</td>
                    <td>
                        @if($user->verify_at)
                            <span>{{ $user->verify_at->toJalali()->format('d Y m') }}</span>
                        @else
                            <span>تایید نشده</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>بلاک شده</td>
                    <td>
                        @include('admin.partial.bool_badge',['value' => $user->is_block])
                    </td>
                </tr>
                <tr>
                    <td>نوع کاربر</td>
                    <td>{{ \Modules\User\app\Enums\UserType::getDescription($user->user_type) }}</td>
                </tr>

                <tr>
                    <td>آخرین ورود</td>
                    <td>
                        @if($user->latestLogin)
                            <span>{{ $user->latestLogin->login_at->toJalali()->format(formatJalaliDate()) }}</span>
                        @else
                            <span>بدون ورود</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>تاریخ ایجاد</td>
                    <td>{{$user->created_at->toJalali()->format(formatJalaliDateTime())}}</td>
                </tr>

                </tbody>
            </table>

        </div>
    </div>
</div>