@extends('frontend.layouts.main')
@section('STYLE')
<link rel="stylesheet" href="{{ asset('vendor/font-awesome/css/font-awesome.min.css') }}">
@endsection
@section('SCRIPT')
<script>
$(function(){

    $('.match_table').click(function(){
        var item_id = $(this).closest('tr').attr('item-id');
        var url = '/order/detail?itemId='+item_id;
        layer.open({
            type:2,
            title:'比赛详情',
            skin:'layui-layer-rim',
            area:['1000px','220px'],
            btn: ['确定', '取消'],
            yes:function(index,layero){

            },
            content:url,
            success:function(layero,index){

            }
        })
    });
    $('.remark_detail').click(function(){
        var analogue_id = $(this).attr('analogue-id');
        var url = '/order/remark?analogue_id=' + analogue_id;
        layer.open({
            type:2,
            title:'比赛详情',
            skin:'layui-layer-rim',
            area:['600px','300px'],
            btn:['确定','取消'],
            yes:function(layero,index){

            },
            content:url,
            success:function(layero,index){

            }
        })
    });
})
</script>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            @include('frontend.partial.order_table')
            {{ $groups->links() }}
        </div>
    </div>
</div>
@endsection