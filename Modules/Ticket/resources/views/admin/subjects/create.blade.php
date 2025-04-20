@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
       \App\Enums\Assets\StyleLoader::Toast(),
   ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.ticket.subjects.index') }}">موضوعات تیکت</a></li>
                <li class="breadcrumb-item active">ایجاد</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.ticket.subjects.store') }}">
                        @csrf

                        <x-admin.input identify="title" title="عنوان موضوع"/>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_published" id="is_published" value="1" {{ old('is_published') ? 'checked' : 'checked' }}>
                                <label class="form-check-label" for="is_published">منتشر شود</label>
                            </div>
                        </div>

                        <x-admin.button title="{{ trans('panel.create') }}"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.request')
    <script>
        $(document).ready(function (){
            activeParentUl('{{ route('admin.ticket.subjects.index') }}');
        });
    </script>
@endsection 