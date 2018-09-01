@extends('frontend.layouts.common')
@section('STYLE')
    <link rel="stylesheet" href="{{ asset('vendor/font-awesome/css/font-awesome.min.css') }}">
    <style>

    </style>
@endsection
@section('SCRIPT')

@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('frontend.partial.match_table',['type'=>'match_detail'])
        </div>
    </div>
@endsection