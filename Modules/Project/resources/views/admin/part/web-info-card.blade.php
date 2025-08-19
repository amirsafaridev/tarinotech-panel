<div class="card">
    <div class="card-header">
        <h3 class="card-title">طراحی سایت</h3>
        <div class="card-options">
            <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i
                        class="fal fa-chevron-up"></i></a>
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
                <td>پکیج</td>
                <td>{{ $project->target->package->title }}</td>
            </tr>

            <tr>
                <td>قیمت پکیج پروژه در زمان عقد قرارداد (ریال)</td>
                <td>
                    @php
                        $packagePrice = 0;
                        if ($project
                            && $project->target
                            && $project->target->package
                            && $project->agreement_at
                        ) {
                            $packagePriceResult = $project->target->package->getPriceForDate($project->agreement_at);
                            if ($packagePriceResult && $packagePriceResult->getPrice()) {
                                $packagePrice = $packagePriceResult->getPrice()->price;
                            }
                        }
                    @endphp
                    {{ number_format($packagePrice) }}
                </td>
            </tr>


            <tr>
                <td>تعداد صفحات</td>
                <td>{{ $project->target->pages }}</td>
            </tr>

            <!-- Domain -->
            @if(!empty($project->target->domains['have_domain']))
                <tr>
                    <td>دامنه دارد؟</td>
                    <td>{{ $project->target->domains['have_domain'] ? 'دارد' : 'ندارد' }}</td>
                </tr>
            @endif

            @if(!empty($project->target->domains['other_domain']))
                <tr>
                    <td>نام دامنه دیگر را وارد کنید</td>
                    <td>{{ $project->target->domains['other_domain'] }}</td>
                </tr>
            @endif

            @if(!empty($project->target->domains['domain_primary']))
                <tr>
                    <td>نام دامنه اصلی</td>
                    <td>{{ $project->target->domains['domain_primary'] }}</td>
                </tr>
            @endif

            @if(!empty($project->target->domains['domain_password']))
                <tr>
                    <td>رمز عبور دامنه</td>
                    <td>{{ decrypt($project->target->domains['domain_password']) }}</td>
                </tr>
            @endif

            @if(!empty($project->target->domains['domain_username']))
                <tr>
                    <td>نام کاربری دامنه</td>
                    <td>{{ $project->target->domains['domain_username'] }}</td>
                </tr>
            @endif

            @if(!empty($project->target->domains['domains_required']))
                <tr>
                    <td>دامنه های موردنیاز جهت خرید</td>
                    <td>{{ implode(', ',$project->target->domains['domains_required']) }}</td>
                </tr>
            @endif

            @if(!empty($project->target->domains['domain_provider_website']))
                <tr>
                    <td>ادرس سایت ارائه دهنده دامنه</td>
                    <td>{{ $project->target->domains['domain_provider_website'] }}</td>
                </tr>
            @endif

            <!-- Host -->
            @if(isset($project->target->host['have_host']))
                <tr>
                    <td> هاست دارد؟</td>
                    <td>{{ $project->target->host['have_host'] ? 'دارد' : 'ندارد' }}</td>
                </tr>
            @endif

            @if(!empty($project->target->host['host_provider']))
                <tr>
                    <td>هاستینگ (از چه سایتی خریداری شده؟)</td>
                    <td>{{ $project->target->host['host_provider'] }}</td>
                </tr>
            @endif

            @if(!empty($project->target->host['host_username']))
                <tr>
                    <td>نام کاربری</td>
                    <td>{{ $project->target->host['host_username'] }}</td>
                </tr>
            @endif

            @if(!empty($project->target->host['host_password']))
                <tr>
                    <td>رمز عبور</td>
                    <td>{{ decrypt($project->target->host['host_password']) }}</td>
                </tr>
            @endif

            @if(!empty($project->target->host['host_location']))
                <tr>
                    <td>لوکیشن هاست؟</td>
                    <td>{{ \Modules\Project\app\Enums\WebHostLocation::getDescription($project->target->host['host_location']) }}</td>
                </tr>
            @endif

            @if(!empty($project->target->host['host_most_visit']))
                <tr>
                    <td> آیا پروژه نیاز به هاست پربازدید دارد؟</td>
                    <td>{{ $project->target->host['host_most_visit'] }}</td>
                </tr>
            @endif

            <!-- Languages -->
            @if(!empty($project->target->language['languages']))
                <tr>
                    <td>زبان ها</td>
                    <td>{{ implode(', ',$project->target->language['languages']) }}</td>
                </tr>
            @endif

            @if(!empty($project->target->language['primary_language']))
                <tr>
                    <td>زبان اصلی</td>
                    <td>{{ $project->target->language['primary_language'] }}</td>
                </tr>
            @endif

            <!-- Sample -->
            @if(!empty($project->target->sample['similar_sites']))
                <tr>
                    <td>سایت های مشابه</td>
                    <td>{{ implode(', ',$project->target->sample['similar_sites']) }}</td>
                </tr>
            @endif

            @if(!empty($project->target->sample['favorite_sites']))
                <tr>
                    <td>سایت های مورد پسند</td>
                    <td>{{ implode(', ',$project->target->sample['favorite_sites']) }}</td>
                </tr>
            @endif 
            <tr>
                <td>مدت زمان (روز کاری)</td>
                <td>{{ $project->target->package->duration + $project->facilities->sum('duration');  }}</td>
            </tr>

            </tbody>
        </table>
    </div>
</div>
