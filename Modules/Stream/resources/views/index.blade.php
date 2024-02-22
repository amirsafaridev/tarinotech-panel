@extends('stream::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('stream.name') !!}</p>
@endsection
