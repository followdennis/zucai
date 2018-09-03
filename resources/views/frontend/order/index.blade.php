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
            area:['600px','400px'],
            btn:['确定','取消'],
            yes:function(layero,index){
                var childPage = layer.getChildFrame('body');
                var analogue_id = childPage.find('#analogueId').val();
                var remark2 = childPage.find("#remark2").val();
                var save_url = '/order/remark_save';
                $.ajaxSetup({
                    headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
                });
                var data = {analogueId:analogue_id,remark2:remark2};
                $.ajax({
                    url:save_url,
                    type:'post',
                    dataType:'json',
                    data:data,
                    success:function(data){
                        if(data.code == 0){
                            layer.msg('添加成功');
                            layer.close(layero);
                        }else{
                            layer.msg('添加失败');
                            layer.close(layero);
                        }
                    }
                })
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