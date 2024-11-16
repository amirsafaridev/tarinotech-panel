@extends('web.master')

@section('content')
    <div class="container d-flex flex-column align-items-center justify-content-center vh-100">
        <div class="text-center p-5 border rounded shadow-sm bg-white">
            <i class="fas fa-ban fa-4x text-danger mb-3"></i>
            <h1 class="display-5 fw-bold text-danger">Access Restricted</h1>
            <p class="lead mb-4">To protect your security, we have temporarily restricted access from your IP address
                due to multiple failed login attempts.</p>

            <p>If this was unintentional, please wait a few minutes and try again. For further assistance, contact our
                support team.</p>
        </div>
    </div>
@endsection
