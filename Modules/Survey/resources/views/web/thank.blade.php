@extends('survey::layouts.web_master')

@section('styles')

@endsection

@section('content')
    <div class="tank-wrapper">
        <div class="tank-container">
            <!-- Decorative elements -->
            <div class="tank-decoration tank-decoration-1"></div>
            <div class="tank-decoration tank-decoration-2"></div>

            <div class="tank-header">
                <!-- Success icon with animation -->
                <div class="tank-icon-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1 class="tank-title">با تشکر از شما!</h1>

                <!-- Confetti elements that will be created by JS -->
            </div>

            <div class="tank-body">
                @if(session('message'))
                    <p class="tank-message">{{ session('message') }}</p>
                @else
                    <p class="tank-message">پاسخ‌های شما با موفقیت ثبت شد. از مشارکت شما در این نظرسنجی سپاسگزاریم.<br>نظر
                        شما برای ما ارزشمند است.</p>
                @endif

                <a href="https://tarinotech.com" class="tank-btn-primary">
                    <i class="fas fa-home me-2"></i> بازگشت به صفحه اصلی
                </a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            // Create confetti elements
            const colors = ['#28a745', '#20c997', '#75b798', '#40916c', '#52b788', '#b7e4c7'];
            const confettiCount = 50;

            for (let i = 0; i < confettiCount; i++) {
                const left = Math.random() * 100;
                const width = Math.random() * 12 + 8;
                const height = width * 0.4;
                const color = colors[Math.floor(Math.random() * colors.length)];

                const confetti = $('<div class="tank-confetti"></div>');
                confetti.css({
                    'left': left + '%',
                    'width': width + 'px',
                    'height': height + 'px',
                    'background-color': color,
                    'top': '50%',
                    'animation-delay': Math.random() * 3 + 's',
                    'animation-duration': Math.random() * 2 + 3 + 's'
                });

                $('.tank-header').append(confetti);
            }

            // Animate the success icon with a bounce effect
            $('.tank-icon-success').css({
                'animation': 'tank-bounce 0.8s ease-in-out'
            });

            // Add bounce animation
            $('<style>')
                .prop('type', 'text/css')
                .html(`
                    @keyframes tank-bounce {
                        0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
                        40% {transform: translateY(-20px);}
                        60% {transform: translateY(-10px);}
                    }
                `)
                .appendTo('head');
        });
    </script>
@endsection
