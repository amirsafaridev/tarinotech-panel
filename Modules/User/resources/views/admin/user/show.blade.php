@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')

@endsection
@section('content')
    <div class="page-header">
        <h1 class="page-title">مدیریت مشتری ها</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">مشتری ها</a></li>
                <li class="breadcrumb-item active">نمایش مشتری</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            @include('user::admin.user.card.info')
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            @include('user::admin.user.card.project',['projects' => $user->projects])
        </div>
    </div>
@endsection
@section('script')
<script>
  $(document).ready(function (){
      activeParentUl('{{ route('admin.user.index') }}');
  });
</script>
@endsection
