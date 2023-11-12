@extends('ajax::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('ajax.name') !!}</p>
@endsection
