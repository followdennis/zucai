@extends('frontend.layouts.main')
@section('STYLE')
<link rel="stylesheet" href="{{ asset('vendor/font-awesome/css/font-awesome.min.css') }}">
<style>
    .betting-btn{
        position: fixed;
        bottom: 50px;
        right: 50px;
        display:none;
    }
    .calculate-result{
        position: fixed;
        bottom: 90px;
        right: 50px;
        display:none;
    }
    .clear-select{
        position:fixed;
        bottom:50px;
        left:10px;
        display:none;
        z-index:1
    }
    .rate_line1 span{
        cursor:pointer;
    }
    .rate_line2 span{
        cursor:pointer;
    }
   td.rate_line1{
       height:37px;
   }
    td.rate_line2{
        height:37px;
    }
</style>
@endsection
@section('SCRIPT')
<script>
    $(function(){
//        $('[data-toggle="popover"]').popover();

        //让球部分隐藏
        $("#hide_give_score").click(function(){
            if($(this).is(":checked") == true){
                $('.rate_line2').find('span').hide();
            }else{
                $('.rate_line2').find('span').show();
            }
        });
        $("#hide_not_give_score").click(function(){
            if($(this).is(":checked") == true){
                $('.rate_line1').find('span').hide();
            }else{
                $('.rate_line1').find('span').show();
            }
        })
        //重置查询表单
        $("#reset_input_val").click(function(){
            $("#form1 input").each(function(){
                $(this).removeAttr('value');
            })
            $("#form1 select").each(function(){
                $(this).attr('value','0');
            })
        });
        //选中单元格
        $("#match-list-table tbody th").click(function(){
           var checkbox = $(this).find('input[type=checkbox]');
            if(checkbox.is(":checked") == true){
               checkbox.attr("checked",false);
            }else{
                checkbox.attr("checked",true);
            }
            var len = $("#match-list-table input[type='checkbox']:checked").length;
            if( len < 2){
                $(".betting-btn").css('display','none');
                $(".calculate-result").css('display','none');
            } else if(  len < 9){
                $(".betting-btn").css('display','block');
                $('.calculate-result').css('display','block');
            }else{
                $(".betting-btn").css('display','none');
                $('.calculate-result').css('display','none');
                  alert('所选比赛超过8个');
            }
            $(".clear-select").find('button span').text('(' + len + ')');//记录选中了多少
            //清除功能
            if( len > 0){
                $('.clear-select').css('display','block');
            }else{
                $('.clear-select').css('display','none');
            }
        });
        $('.clear-select').click(function(){

            $("#match-list-table input[type='checkbox']:checked").each(function(){
                $(this).attr('checked',false);
            });
            $(".clear-select").css('display','none');
        })
        //计算 赔率和回报
        $(".calculate-result").click(function(){
            var total = 1;
            $("#match-list-table input[type='checkbox']:checked").each(function(){
               var rate = $(this).parent().parent().find('td:eq(8)').find('span').data('rate');
               var rate = parseFloat(rate);
               total *= rate;
            });
            var money = (total * 20).toFixed(2);
            var html = '<div style="margin:10px auto;width:350px;">';
            html += '<span>投注金额: 20 元<span><br/>';
            html += '总赔率 :<font color="red">' + total.toFixed(2) + '</font><br/>';
            html += '回报总额:' + '<font color="red">' +money + "</font><br/>";
            html += '</div>';
            layer.open({
                type: 1,
                skin: 'layui-layer-rim', //加上边框
                area: ['420px', '240px'], //宽高
                content: html
            });
        })
        //投注
        $(".betting-btn").click(function(){
            var len = $("input[type='checkbox']:checked").parent().parent().find('td.rate_line1[data-finish="0"]').length;
            //选中的未开始比赛的数量
            if(len < 2){
                alert('未完赛的比赛数量不能小于两个');
                return false;
            }
            var url = '/betting';
            layer.open({
                type:2,
                title:'投注',
                skin:'layui-layer-rim',
                area:['800px','580px'],
                btn: ['确定', '取消'],
                yes:function(index,layero){
                    var childPage = layer.getChildFrame('body');
                    var len = childPage.find("#select_match tbody tr").length;
                    if(len < 2){
                        layer.alert('需要至少两条数据才可以提交哦');
                        return false;
                    }
                    var data = {};
                    data['list'] = [];
                    var num = 0;
                    var sum_rate = 1;  //总赔率
                    var max_time = '2000-01-01 00:00:00'; //求最大的时间
                    childPage.find("#select_match tbody tr").each(function(i,item){
                        var item_id = $(this).attr('item-id');
                        var give_score = $(this).find('td:eq(4)').text();
                        var res = $(this).find('td:eq(5)').data('betting');
                        var rate = $(this).find('td:eq(5) span').text();
                        var total = $(this).find('td:eq(6)').find('select option:selected').val();
                        var match_time = $(this).find('td:eq(7)').text();
                        var remark = $(this).find('td:eq(8)').data('remark');
                        sum_rate *= parseFloat(rate);
                        if(match_time > max_time){
                            max_time = match_time;
                        }
                        data['list'].push({itemId:item_id,giveScore:give_score,res:res,rate:rate,total:total,matchTime:match_time,remark:remark});
                        num = i+1;
                    });
                    data['total'] = num;
                    data['maxTime'] = max_time;
                    data['sumRate'] = sum_rate.toFixed(2);
                    data['comment'] = childPage.find("#comment").val();
                    data['isImportant'] = childPage.find("input[name='is_important']").is('checked');
                    data = JSON.stringify(data);
                    $.ajax({
                        url:'/betting_save',
                        type:'get',
                        dataType:'json',
                        data:{data:data},
                        success:function(data){
                            console.log(data);
                            if(data.code == 0){
                                layer.msg(data.msg,{icon:1});
                                layer.close(index);
                            }else{
                                layer.msg(data.msg,{icon:5});
                                layer.close(index);
                            }
                        }
                    });
                },
                content:url,
                success:function(layero,index){
                    var ids_arr = {};
                    var base_money = 20;
                    var total_rate = 1;
                    var payback = 0;
                    var body = layer.getChildFrame('body', index);
                    $("input[type='checkbox']:checked").each(function(i,obj){

                        var item_id = $(this).val();
                        var tr = $(this).parent().parent();
                        var line = tr.parent().find('tr[item-id="'+ item_id +'"]').find('td[class^="rate_line"] span[class]');
                        var rate = line.text(); //获取赔率
                        var res =  line.attr('res'); //获取胜平负
                        var line_class = line.parent().attr('class');
                        var line_num = tr.find('th').text();
                        if(line_class == undefined){
                            layer.alert('序号为'+ line_num + '的比赛未押注比赛结果，请选好再提交');
                            layer.close(index);
                            return false;
                        }
                        var is_give_score = parseInt(line_class.match(/\d+/)) -1; //0 非让球  1 让球
                        var finish_status = line.parent().data('finish');

                        if(finish_status == 0){
                            var match_info = tr.find('td:eq(2) span').text() + tr.find('td:eq(0)').text();
                            var match_time = tr.find('td:eq(1)').text();
                            var give_score = parseInt(line.parent().prev('td').text());
                            var host_team = tr.find('td:eq(3)').html().trim();
                            var guest_team = tr.find('td:eq(5)').html().trim();
                            var host_team_name = host_team.substring(0,host_team.indexOf('<br>'));
                            var guest_team_name = guest_team.substring(0,guest_team.indexOf('<br>'));
                            var select_html = '<select class="total_score" name="total">';
                            tr.find('td:eq(8)').find('select option').each(function(i,item){
                                if($(this).is(':selected')){
                                    $(this).prop('selected',true);
                                    select_html += '<option value="'+ i +'" selected>'+ i +'</option>';
                                }else{
                                    select_html += '<option value="'+ i +'">'+ i +'</option>';
                                }
                            });  //拼接出默认的选中球
                            select_html += '</select>';
                            var index_i = i + 1;
                            var res_name = '';
                            if(res == 1){
                                res_name = '胜';
                            }else if(res == 2){
                                res_name = '平';
                            }else if(res == 3){
                                res_name = '负';
                            }
                            total_rate *= parseFloat(rate);

                            var html = '<tr item-id="'+ item_id +'">' +
                                '<td>' + index_i + '</td>' +
                                '<td>' + match_info +  '</td>' +
                                '<td>' + host_team_name + '</td>' +
                                '<td>'+ guest_team_name +'</td>' +
                                '<td>'+ give_score +'</td>' +
                                '<td data-betting="'+ res +'">' + res_name + '(<span>'+ rate +'</span>)</td>' +
                                '<td>' +
                                select_html +
                                '</td>'+
                                '<td>'+ match_time +'</td>'+
                                '<td onclick="add_remark(this)" data-remark=""><font color="green"><i class="fa fa-plus" aria-hidden="true"></i></font></td>'+
                                '<td onclick="del_item(this)"><font color="red"><i class="fa fa-close" aria-hidden="true"></i></font></td>'+
                                '</tr>';
                            body.find('#select_match tbody').append(html);
                        }
                    });
                    payback = (total_rate * 20).toFixed(2);
                    total_rate = total_rate.toFixed(2);
                    body.find("#base_money").val(base_money);
                    body.find("#total_rate").val(total_rate);
                    body.find("#payback").val(payback);

                }
            });


        });
        //选择胜平负
        $(".rate_line1 span").click(function(){
            var finish = $(this).parent('td').data('finish');
            if(finish == 0){
                var style = $(this).attr('class');
                if(style == undefined){
                    $(this).addClass('btn btn-info btn-sm');
                    $(this).siblings().removeAttr('class');
                    $(this).parent().parent().next().find('td.rate_line2 span').removeAttr('class');
                }else{
                    $(this).removeAttr('class');
                }
            }else{
                console.log('该比赛已经结束，不可投注');
            }
        });
        //让球投注
        $(".rate_line2 span").click(function(){
            var finish = $(this).parent('td').data('finish');
            if(finish == 0){
                var style = $(this).attr('class');
                if(style == undefined){
                    $(this).addClass('btn btn-success btn-sm');
                    $(this).siblings().removeAttr('class');
                    $(this).parent().parent().prev().find('td.rate_line1 span').removeAttr('class');
                }else{
                    $(this).removeAttr('class');
                }
            }else{

            }
        });
        //分析
        $('.remark_detail').click(function(){
            var item_id = $(this).parents('tr').attr('item-id');
            var id = $(this).parents('tr').find('th:eq(0)').text();
            var url = '/match/judge?item_id=' + item_id;
            layer.open({
                type:2,
                title:'比赛详情-编号'+ id,
                skin:'layui-layer-rim',
                area:['1200px','700px'],
                btn:['关闭'],
                yes:function(layero,index){
                    layer.close(layero);
                },
                content:url,
                success:function(layero,index){

                }
            })
        });

        //获取统计数据
        calculate();
    })

    function calculate(){
        var url = '/calculate';
        var data = {
            'competitionName':$("#competition_name").val(),
            'teamName':$("#team_name").val(),
            'bettingTime':$("select[name='bettingTime'] option:selected").val(),
            'matchStatus':$("select[name='matchStatus'] option:selected").val(),
            'totalScore':$("select[name='totalScore'] option:selected").val(),
            'pageSize':$("select[name='pageSize'] option:selected").val(),
            'matchResult':$("select[name='matchResult'] option:selected").val(),
            'page':'{{ $page }}'
        };
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
        });
        $.ajax({
            url:url,
            type:'post',
            data:data,
            dataType:'json',
            success:function(data,status){
                $("#hope_rate span").text(data.hopeRate);
                $("#single_match_vest span").text(data.vestTotal);
                $("#single_match_repay span").text(data.repayTotal);
                $("#feedback_rate span").text(data.feedback);
                $("#total_score_feedback").find('td:eq(0)').find('span').text(data.scoreFeedback.score0Rate);
                $("#total_score_feedback").find('td:eq(1)').find('span').text(data.scoreFeedback.score1Rate);
                $("#total_score_feedback").find('td:eq(2)').find('span').text(data.scoreFeedback.score2Rate);
                $("#total_score_feedback").find('td:eq(3)').find('span').text(data.scoreFeedback.score3Rate);
                $("#total_score_feedback").next().find('td:eq(0)').find('span').text(data.scoreFeedback.score4Rate);
                $("#total_score_feedback").next().find('td:eq(1)').find('span').text(data.scoreFeedback.score5Rate);
                $("#total_score_feedback").next().find('td:eq(2)').find('span').text(data.scoreFeedback.score6Rate);
                $("#total_score_feedback").next().find('td:eq(3)').find('span').text(data.scoreFeedback.score7Rate);

                $("#final_expect_rate").find('td:eq(0)').find('span').text(data.winRate);
                $("#final_expect_rate").find('td:eq(1)').find('span').text(data.drawRate);
                $("#final_expect_rate").find('td:eq(2)').find('span').text(data.failRate);
                $("#final_expect_rate").find('td:eq(3)').find('span').text(data.scoreNumRate);

                console.log(data.scoreFeedback);
            }
        });
    }
</script>
@endsection
@section('content')
<div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
        <div class="row">
            <div class="col-md-12">
                <form class="form-inline" id="form1" action="/index" method="get">
                    <div class="form-group">
                        <label for="select4">单页条数</label>
                        <select class="form-control" name="pageSize" id="select4">
                            <option value="15" @if($pageSize == 15) selected @endif>15</option>
                            <option value="20" @if($pageSize == 20) selected @endif>20</option>
                            <option value="50" @if($pageSize == 50) selected @endif>50</option>
                            <option value="100" @if($pageSize == 100) selected @endif>100</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="competition_name">比赛:</label>
                        <select class="form-control" name="competitionName" id="competition_name">
                            <option value="">请选择</option>
                            @foreach($competitions as $v)
                                <option value="{{ $v->competition_name }}"  @if(!empty($competitionName) && $competitionName == $v->competition_name) selected @endif >{{ $v->competition_name  }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="team_name">队伍名称:</label>
                        <input type="text" name="teamName" class="form-control" @if(!empty($teamName)) value="{{ $teamName }}" @endif id="team_name" placeholder="队伍名称">
                    </div>
                    <div class="form-group">
                        <label for="exampleSelect1">投注时间</label>
                        <select class="form-control" name="bettingTime" id="exampleSelect1">
                            <option value="">所有</option>
                            @foreach($dateList as $k => $date)
                                <option value="{{ $date }}" @if($date == $bettingTime) selected @endif>{{ $date }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleSelect2">比赛状态</label>
                        <select class="form-control" name="matchStatus" id="exampleSelect2">
                            <option value="2" @if($matchStatus == 2) selected @endif>已结束</option>
                            <option value="1" @if($matchStatus == 1) selected @endif>进行中</option>
                            <option value="0" @if($matchStatus == 0) selected @endif>未开赛</option>
                            <option value="3" @if($matchStatus == 3) selected @endif>所有</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleSelect3">总球</label>
                        <select class="form-control" name="totalScore" id="exampleSelect3">
                            <option value="8">所有</option>
                            <option value="0" @if($totalScore == 0) selected @endif>0</option>
                            <option value="1" @if($totalScore == 1) selected @endif>1</option>
                            <option value="2" @if($totalScore == 2) selected @endif>2</option>
                            <option value="3" @if($totalScore == 3) selected @endif>3</option>
                            <option value="4" @if($totalScore == 4) selected @endif>4</option>
                            <option value="5" @if($totalScore == 5) selected @endif>5</option>
                            <option value="6" @if($totalScore == 6) selected @endif>6</option>
                            <option value="7" @if($totalScore == 7) selected @endif>7</option>

                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleSelect4">结果</label>
                        <select class="form-control" name="matchResult" id="exampleSelect4">
                            <option value="">所有</option>
                            <option value="1" @if($matchResult == 1) selected @endif>胜</option>
                            <option value="2" @if($matchResult == 2) selected @endif>平</option>
                            <option value="3" @if($matchResult == 3) selected @endif>负</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-xs  btn-primary">搜索</button>
                    <button type="reset"  class="btn  btn-success" id="reset_input_val">重置</button>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 form-inline">
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" id="hide_not_give_score" value="">隐藏
                    </label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" id="hide_give_score" value="">让球隐藏
                    </label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered  table-hover table-sm text-center" >
                    <thead class="bg-info text-light text-center">
                    <tr>
                        <th colspan="8">数据统计分析</th>
                    </tr>
                    </thead>
                    <tbody class="text-left text-secondary" id="data_calculate">
                    <tr>
                        <th>冷门率</th>
                        <td id="hope_rate"><span class="text-danger font-weight-bold"></span></td>
                        <th >单场投入</th>
                        <td id="single_match_vest"><span class="text-danger font-weight-bold"></span></td>
                        <th>单场返奖</th>
                        <td id="single_match_repay"><span class="text-danger font-weight-bold"></span></td>
                        <th>单场返奖率</th>
                        <td id="feedback_rate"><span class="text-danger font-weight-bold"></span></td>
                    </tr>
                    <tr id="total_score_feedback">
                        <th>总进球 0</th>
                        <td><span class="text-danger font-weight-bold"></span></td>
                        <th>总进球 1</th>
                        <td><span class="text-danger font-weight-bold"></span></td>
                        <th>总进球 2</th>
                        <td><span class="text-danger font-weight-bold"></span></td>
                        <th>总进球 3</th>
                        <td><span class="text-danger font-weight-bold"></span></td>
                    </tr>
                    <tr>
                        <th>总进球 4</th>
                        <td><span class="text-danger font-weight-bold"></span></td>
                        <th>总进球 5</th>
                        <td><span class="text-danger font-weight-bold"></span></td>
                        <th>总进球 6</th>
                        <td><span class="text-danger font-weight-bold"></span></td>
                        <th>总进球 7</th>
                        <td><span class="text-danger font-weight-bold"></span></td>
                    </tr>
                    <tr id="final_expect_rate">
                        <th>胜比率</th>
                        <td><span class="text-primary font-weight-bold"></span></td>
                        <th>平比率</th>
                        <td><span class="text-primary font-weight-bold"></span></td>
                        <th>负比率</th>
                        <td><span class="text-primary font-weight-bold"></span></td>
                        <th>平均进球数</th>
                        <td><span class="text-primary font-weight-bold"></span></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                注释 <i aria-hidden="true" class="fa fa-star"></i> 赔率与排名相同 <i class="fa fa-heart" aria-hidden="true"></i> 赔率与排名相反
            </div>
        </div>
    </div>
</div>
<div class="clear-select">
    <button class="btn btn-info btn-md">清空<span></span></button>
</div>
<div class="calculate-result">
    <button class="btn btn-info btn-md">计算</button>
</div>
<div class="betting-btn">
    <button class="btn btn-primary btn-md">投注</button>
</div>
<div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10" style="margin-top:15px;">
        @include('frontend.partial.match_table',['type'=>'match_list'])
        {{ $list->links() }}
    </div>
</div>
@endsection