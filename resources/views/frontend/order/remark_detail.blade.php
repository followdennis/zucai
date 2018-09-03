@extends('frontend.layouts.common')
@section('STYLE')
    <link rel="stylesheet" href="{{ asset('vendor/font-awesome/css/font-awesome.min.css') }}">
    <style>

    </style>
@endsection
@section('SCRIPT')
    <script>

    </script>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <form class="form-horizontal col-md-12 remark-info" role="form">
                <input type="hidden" name="analogueId" id="analogueId" value="{{ $data->id }}" >
                <div class="form-group">
                    <label for="comment" >备注:1 {{ $data->created_at }}</label>
                    <textarea class="form-control" rows="2" name="remark1" id="remark1">{{ $data->remark }}</textarea>
                </div>
                <div class="form-group">
                    <label for="comment" >备注:2</label>
                    <textarea class="form-control" rows="2" name="remark2" id="remark2">{{ $data->remark2 }}</textarea>
                </div>
            </form>
        </div>
    </div>
@endsection