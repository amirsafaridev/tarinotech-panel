@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
@endsection
@section('content')
    <div class="page-header">
        <h1 class="page-title">{{ trans('panel.dashboard.title') }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
            </ol>
        </div>
    </div>

    <div class="row">

        <div class="col-12 mb-2">
            @include('admin.partial.message')
        </div>
        <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="card-order">
                        <h2 class="text-end">
                            <i class="fal fa-user icon-size float-start text-danger text-danger-shadow p-3"></i>
                            <span>{{ number_format($data['admins_count']) }}</span>
                        </h2>
                        <div class="mb-0 pt-5">
                            <span>{{ trans('panel.dashboard.total_admin') }}</span>
                            <span class="float-end">
                                <a class="btn btn-sm btn-light"
                                    href="{{ route('admin.admin.index') }}">{{ trans('panel.dashboard.show_link') }}</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="card-order">
                        <h2 class="text-end">
                            <i class="fal fa-globe icon-size float-start text-success text-success-shadow p-3"></i>
                            <span>{{ number_format($data['project_web_count']) }}</span>
                        </h2>
                        <div class="mb-0 pt-5">
                            <span>پروژه های وب</span>
                            <span class="float-end">
                                <a class="btn btn-sm btn-light"
                                    href="{{ route('admin.project.web.index') }}">{{ trans('panel.dashboard.show_link') }}</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="card-order">
                        <h2 class="text-end">
                            <i class="fal fa-list-numeric icon-size float-start text-info text-info-shadow p-3"></i>
                            <span>{{ number_format($data['project_seo_count']) }}</span>
                        </h2>
                        <div class="mb-0 pt-5">
                            <span>پروژه های سئو</span>
                            <span class="float-end">
                                <a class="btn btn-sm btn-light"
                                    href="{{ route('admin.project.seo.index') }}">{{ trans('panel.dashboard.show_link') }}</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="card-order">
                        <h2 class="text-end">
                            <i class="fal fa-bullhorn icon-size float-start text-warning text-warning-shadow p-3"></i>
                            <span>{{ number_format($data['project_ads_count']) }}</span>
                        </h2>
                        <div class="mb-0 pt-5">
                            <span>پروژه های ادوورز</span>
                            <span class="float-end">
                                <a class="btn btn-sm btn-light"
                                    href="{{ route('admin.project.ads.index') }}">{{ trans('panel.dashboard.show_link') }}</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="card-order">
                        <div id="desktop-only" class="text-center mb-4" style="display: none;">
                            <button id="activity-toggle"
                                class="btn btn-lg {{ $currentActivity && $currentActivity->status === 'active' ? 'btn-success' : '' }}"
                                style="min-width: 200px; min-height: 60px;background-color:grey;color:white;">
                                {{ $currentActivity && $currentActivity->status === 'active' ? 'پایان کار' : 'شروع کار' }}
                            </button>
                        </div>
                
                        <div id="mobile-warning" class="alert alert-warning text-center" style="display: none;">
                            ثبت فعالیت فقط از طریق کامپیوتر امکان‌پذیر است
                        </div>
                
                        @if ($currentActivity && $currentActivity->status === 'active')
                            <div class="text-center mb-4">
                                <h4 id="activity-timer" class="text-success">00:00:00</h4>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
   

       
        <div class="row">

        </div>
    @endsection
    @section('script')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Check if mobile device
                const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator
                    .userAgent);
                const desktopOnly = document.getElementById('desktop-only');
                const mobileWarning = document.getElementById('mobile-warning');

                if (isMobile) {
                    desktopOnly.style.display = 'none';
                    mobileWarning.style.display = 'block';
                } else {
                    desktopOnly.style.display = 'block';
                    mobileWarning.style.display = 'none';
                }

                const activityButton = document.getElementById('activity-toggle');
                let timer = null;
                let startTime = @json($currentActivity && $currentActivity->status === 'active' ? $currentActivity->start_time : null);

                // Update current time every second
                setInterval(function() {
                    const now = new Date();
                    document.getElementById('current-time').textContent =
                        now.getHours().toString().padStart(2, '0') + ':' +
                        now.getMinutes().toString().padStart(2, '0') + ':' +
                        now.getSeconds().toString().padStart(2, '0');
                }, 1000);

                // Update activity timer if active
                if (startTime) {
                    startTimer(new Date(startTime));
                }

                if (activityButton) {
                    activityButton.addEventListener('click', function() {
                        if (@json($isPhysicalDay)) {
                            if (!navigator.geolocation) {
                                alert('مرورگر شما از موقعیت‌یابی پشتیبانی نمی‌کند');
                                return;
                            }

                            navigator.geolocation.getCurrentPosition(
                                position => toggleActivity(position.coords.latitude, position.coords
                                    .longitude),
                                error => {
                                    alert('لطفاً اجازه دسترسی به موقعیت مکانی را بدهید');
                                }
                            );
                        } else {
                            toggleActivity(null, null);
                        }
                    });
                }

                function toggleActivity(latitude, longitude) {
                    fetch('{{ route('admin.personnel.daily-activity.toggle') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                latitude: latitude,
                                longitude: longitude
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'error') {
                                alert(data.message);
                                return;
                            }

                            if (data.status === 'active') {
                                activityButton.classList.remove('btn-secondary');
                                activityButton.classList.add('btn-success');
                                activityButton.textContent = 'پایان کار';
                                startTimer(new Date());
                            } else {
                                activityButton.classList.remove('btn-success');
                                activityButton.classList.add('btn-secondary');
                                activityButton.textContent = 'شروع کار';
                                stopTimer();
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('خطا در ارتباط با سرور');
                        });
                }

                function startTimer(start) {
                    const timerElement = document.getElementById('activity-timer');
                    if (!timerElement) {
                        const div = document.createElement('div');
                        div.className = 'text-center mb-4';
                        div.innerHTML = '<h4 id="activity-timer" class="text-success">00:00:00</h4>';
                        activityButton.parentNode.after(div);
                    }

                    timer = setInterval(function() {
                        const now = new Date();
                        const diff = Math.floor((now - start) / 1000);
                        const hours = Math.floor(diff / 3600);
                        const minutes = Math.floor((diff % 3600) / 60);
                        const seconds = diff % 60;

                        document.getElementById('activity-timer').textContent =
                            hours.toString().padStart(2, '0') + ':' +
                            minutes.toString().padStart(2, '0') + ':' +
                            seconds.toString().padStart(2, '0');
                    }, 1000);
                }

                function stopTimer() {
                    if (timer) {
                        clearInterval(timer);
                        timer = null;
                    }
                    const timerElement = document.getElementById('activity-timer');
                    if (timerElement) {
                        timerElement.parentNode.remove();
                    }
                }


            });
        </script>
    @endsection
