@extends('frontend.layouts.common')
@section('STYLE')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/font-awesome/css/font-awesome.min.css') }}">
    <style>
        .total_score{
            width: 50px;
            line-height: 20px;
            padding: 2px;
        }
    </style>
@endsection
@section('SCRIPT')
<script>
layui.use(['layer', 'form'], function(){
    var layer = layui.layer
        ,form = layui.form;

});
function del_item(obj){
    layer.confirm('确定删除？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        var len = $("#select_match tbody tr").length;
        if(len <= 2){
            layer.msg('最少要保留两场比赛才可以提交哦');
            return false;
        }
        $(obj).parent().remove();
        var total_rate = 1;
        var payback = 0;
        $("#select_match tbody tr").each(function(){
            var rate = $(this).find('td:eq(5) span').text();
            rate = parseFloat(rate);
            total_rate *= rate;
        });

        total_rate = total_rate.toFixed(2);
        payback = (parseInt($("#base_money").val())*total_rate).toFixed(2);
        $("#total_rate").val(total_rate);
        $("#payback").val(payback);
        layer.msg('删除成功', {icon: 1});
    }, function(){

    });
}
function add_remark(obj){
    layer.prompt({title: '备注内容', formType: 2}, function(text, index){
        $(obj).attr('data-remark',text);
        layer.close(index);
        layer.msg('添加成功');
    });
}
</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <table id="select_match" class="table table-bordered table-hover ">
            <thead class="thead-light">
            <tr>
                <th>序号</th>
                <th>编号</th>
                <th>主队</th>
                <th>客队</th>
                <th>让球</th>
                <th>结果</th>
                <th>进球数</th>
                <th>比赛时间</th>
                <th>备注</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="row">
        <form class="form-inline">
            <div class="input-group mb-3 input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text">本金</span>
                </div>
                <input type="text" id="base_money" class="form-control col-sm-8 text-danger" value="20" readonly>
            </div>
            <div class="input-group mb-3 input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text">理论赔率</span>
                </div>
                <input type="text" id="total_rate" class="form-control col-sm-8 text-danger" value="20" readonly>
            </div>
            <div class="input-group mb-3 input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text">理论奖金</span>
                </div>
                <input type="text" id="payback" class="form-control col-sm-8 text-danger" value="20" readonly>
            </div>
        </form>
    </div>
    <form class="form-horizontal remark-info" role="form">
        <div class="form-group">
            <label for="comment" >备注:</label>
            <textarea class="form-control" rows="2" id="comment"></textarea>
        </div>
        <div class="form-group">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="is_important" id="is_important">
                </label>
                标记为重点
            </div>
        </div>
    </form>
</div>
@endsection