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
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.package.index') }}">پکیج ها</a></li>
                <li class="breadcrumb-item active">ایجاد</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.package.store') }}">
                        @csrf

                        <x-admin.select-model identify="base_id" :items="$bases" key="id" value="title"/>

                        <x-admin.input identify="title" title="عنوان"/>

                        <x-admin.input identify="price" title="قیمت"/>

                        <x-admin.button-submit/>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.request')
    @include('admin.partial.script.global')
    <script>
        $(document).ready(function (){
            activeParentUl('{{ route('admin.package.index') }}');
            makeInputPrice($('#price'));
        })
    </script>
@endsection