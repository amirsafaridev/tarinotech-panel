@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    <style>
        .card-img-container {
            width: 100%;
            height: 200px; /* Fixed height for all images */
            overflow: hidden; /* Hide overflow to crop the image */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-img-top {
            object-fit: cover; /* Ensures the image covers the container */
            width: 100%;
            height: 100%;
        }
    </style>
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.contract.sign.user.index') }}">درخواست ها</a></li>
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-3">
                    @include('admin.partial.message')
                    <div class="row mt-5 mb-5">
                        @foreach($userSignable->attachments as $attachment)
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-img-container">
                                    @if(str_contains($attachment->file_type, 'image/'))
                                        <!-- Render image for image file types -->
                                            <img src="{{ route('stream.read', $attachment->file_path) }}" class="card-img-top" alt="Attachment Image">
                                    @elseif($attachment->file_type == 'application/pdf')
                                        <!-- Render PDF preview box for PDF files -->
                                            <div class="pdf-preview-container d-flex justify-content-center align-items-center">
                                                <div class="text-center">
                                                    <i class="fas fa-file-pdf fa-4x text-danger"></i>
                                                    <p class="mt-2">PDF Document</p>
                                                </div>
                                            </div>
                                    @else
                                        <!-- Render placeholder or message for unsupported file types -->
                                            <div class="text-center">
                                                <p>Unsupported file type</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-body d-flex gap-2">
                                        <a href="{{ route('stream.read', $attachment->file_path) }}" target="_blank" class="btn btn-primary btn-sm">
                                            دانلود
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($userSignable->files)
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>تاریخ</th>
                                <th>دانلود</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($userSignable->files as $file)
                                <tr>
                                    <td>{{ $file->created_at->toJalali()->format(formatJalaliDateTime()) }}</td>
                                    <td>
                                        <a href="{{ route('admin.contract.file.download', $file->id) }}" class="btn btn-success btn-sm">دانلود PDF</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
