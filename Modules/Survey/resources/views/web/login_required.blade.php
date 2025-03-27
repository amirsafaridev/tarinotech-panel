@extends('survey::layouts.web_master')

@section('styles')

@endsection

@section('content')
    <div class="survey-auth-required-wrapper">
        <div class="survey-auth-required-container">
            <!-- Decorative elements -->
            <div class="survey-auth-required-decoration survey-auth-required-decoration-1"></div>
            <div class="survey-auth-required-decoration survey-auth-required-decoration-2"></div>

            <div class="survey-auth-required-header">
                <!-- Login required icon with animation -->
                <div class="survey-auth-required-icon">
                    <i class="fas fa-user-lock"></i>
                </div>
                <h1 class="survey-auth-required-title">ورود به حساب کاربری لازم است</h1>
            </div>

            <div class="survey-auth-required-body">
                <p class="survey-auth-required-message">برای شرکت در این پرسش نامه، ابتدا باید وارد حساب کاربری خود شوید.</p>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            // No additional scripts needed
        });
    </script>
@endsection
