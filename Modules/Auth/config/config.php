<?php

return [
    'development_otp' => env('OTP_CODE_DEVELOPMENT', '1234'),
    'otp_code_session_key' => env('OTP_CODE_SESSION_KEY', 'SESSION_OTP_CODE'),
];
