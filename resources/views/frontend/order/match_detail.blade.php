@extends('frontend.layouts.common')
@section('STYLE')
    <link rel="stylesheet" href="{{ asset('vendor/font-awesome/css/font-awesome.min.css') }}">
    <style>

    </style>
@endsection
@section('SCRIPT')
<script>
    $(function(){
        //tips
        $('.host_team').click(function(){
            var html = $(this).find('.history_info').html();
            layer.tips(html, this, {
                tips: [1, '#0FA6D8'], //还可配置颜色
                time:0,
                closeBtn:1
            });
        });

        $('.guest_team').click(function(){
            var html = $(this).find('.history_info').html();
            layer.tips(html, this, {
                tips: [1, '#0FA6D8'], //还可配置颜色
                time:0,
                closeBtn:1
            });
        });
    })
</script>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('frontend.partial.match_table',['type'=>'match_detail'])
        </div>
    </div>
@endsection