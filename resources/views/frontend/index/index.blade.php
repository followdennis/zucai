@extends('frontend.layouts.main')
@section('STYLE')
    <link rel="stylesheet" href="{{ asset('vendor/font-awesome/css/font-awesome.min.css') }}">
@endsection
@section('SCRIPT')
<script>
    $(function(){
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
        })

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
        }
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
                        <input type="text" name="competitionName" @if(!empty($competitionName)) value="{{ $competitionName }}" @endif class="form-control" id="competition_name" placeholder="比赛名称">
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
                <table class="table table-bordered  table-hover table-sm text-center">
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
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10" style="margin-top:15px;">
        <table class="table table-bordered  table-hover table-sm text-center" border="1">
            <thead class="bg-secondary text-light text-center">
            <tr>
                <th scope="col" >序号</th>
                <th scope="col">场次</th>
                <th scope="col" style="width:50px;">比赛时间</th>
                <th scope="col">赛事名称</th>
                <th scope="col">主队</th>
                <th scope="col">比分</th>
                <th scope="col">客队</th>
                <th scope="col">让球</th>
                <th scope="col">赔率</th>
                <th scope="col">赛果</th>
                <th scope="col">总球</th>
                <th scope="col">预期</th>
                <th scope="col">分差</th>
                <th scope="col">最后更新</th>
                <th scope="col">状态</th>
                <th scope="col">推荐/指数</th>
                <th scope="col">操作</th>

            </tr>
            </thead>
            <tbody>
            @foreach($list as $k => $item)
            <tr>
                <th scope="row" rowspan="2">
                    {{ $k+1 }}
                    <input type="checkbox" name="check[]" value="{{ $item->id }}" aria-label="Checkbox for following text input">
                </th>
                <td rowspan="2">{{ $item->match_number }}</td>
                <td rowspan="2">{{ $item->match_time }}</td>
                <td rowspan="2" class="text-center small">
                    <span class="@if($item->hope == 1) text-success @endif">{{ $item->competition_name }} </span><br/>
                    @php
                        $rank_pos = $item->rankDiff > 0 ? 3: ($item->rankDiff == 0 ? 2 : 1);
                    @endphp
                    @if($rank_pos == $item->match_result && $item->hope == 1)
                    <span style="color:red;"><i class="fa fa-star" aria-hidden="true"></i></span>
                    @endif
                </td>
                <td rowspan="2" class="small">
                    {{ $item->host_team_name }}<br/>
                    {{ $item->host_team_rank }}
                </td>
                <td rowspan="2" class="text-center">
                    <span class="@if($item->status == 2)text-danger  @endif font-weight-bold">{{ $item->host_team_score }}</span>
                    <span class="@if($item->status == 2)text-danger @endif">-</span>
                    <span class="@if($item->status == 2)text-danger @endif font-weight-bold">{{ $item->guest_team_score }}</span>
                </td>
                <td rowspan="2" class="text-center small">
                    {{ $item->guest_team_name }}<br/>
                    {{ $item->guest_team_rank }}
                </td>
                <td>{{ $item->give_score_1 }}</td>
                <td class="rate_line1" style="background-color:cornsilk">
                    <span class="@if($item->match_result == 1){{ $item->color1 }} font-weight-bold @endif ">{{ $item->win_rate_1 }}</span>&nbsp;
                    <span class="@if($item->match_result == 2) {{ $item->color1 }} font-weight-bold  @endif ">{{ $item->draw_rate_1 }}</span>&nbsp;
                    <span class="@if($item->match_result == 3) {{ $item->color1 }} font-weight-bold @endif ">{{ $item->fail_rate_1 }}</span>
                </td>
                <td class="small"  style="background-color:cornsilk">

                        @if($item->match_result == 1)
                            <span class="text-danger">胜</span>({{ $item->final_rate }})
                        @elseif($item->match_result == 2)
                            <span class="text-primary">平</span>({{ $item->final_rate }})
                        @elseif($item->match_result == 3)
                            <span class="text-success">负</span>({{ $item->final_rate }})
                        @endif

                </td>
                <td rowspan="2">
                    {{ $item->total }}<br/>
                    <font color="blue">{{ $item->total_rate }}</font>
                </td>
                <td rowspan="2">
                    @if($item->hope == 1)
                        <button type="button" class="btn btn-success btn-sm">yes</button>
                    @elseif($item->hope == 0)
                        <button type="button" class="btn btn-danger btn-sm" >no</button>
                    @endif
                </td>
                <td rowspan="2">
                    @if($item->status == 2)
                    <button type="button" class="btn {{ $item->bigScoreColor }} btn-sm">{{ $item->big_score }}</button>
                    @endif
                    <button type="button" class="btn btn-sm {{ $item->rankDiffColor }}" style="margin-left:2px;">{{ $item->rankDiff }}</button>
                </td>
                <td rowspan="2">
                    {{ $item->updateDate }}
                </td>
                <td rowspan="2" class="small">@if($item->status == 2) <font color="green">结束</font> @elseif($item->status == 3) <font color="red">异常</font> @elseif($item->status == 0)<font color="silver">未</font>   @endif</td>
                <td rowspan="2">
                    胜 <br/>
                    62
                </td>
                <td rowspan="2">
                    <button type="button" class="btn btn-danger btn-sm">分析</button>
                </td>
            </tr>
            <tr>
                <td>{{ $item->give_score_2 }}</td>
                <td class="rate_line2">
                    <span class="@if($item->match_give_score_result == 1) {{ $item->color2 }}  font-weight-bold  @endif">{{ $item->win_rate_2 }}</span>&nbsp;
                    <span class="@if($item->match_give_score_result == 2){{ $item->color2 }} font-weight-bold @endif">{{ $item->draw_rate_2 }}</span>
                    <span class="@if($item->match_give_score_result == 3) {{ $item->color2 }} font-weight-bold @endif">{{ $item->fail_rate_2 }}</span>
                </td>
                <td>
                    @if($item->match_give_score_result == 1)
                        <span class="text-danger">胜</span>({{ $item->final_give_score_rate }})
                    @elseif($item->match_give_score_result == 2)
                        <span class="text-primary">平</span>({{ $item->final_give_score_rate }})
                    @elseif($item->match_give_score_result == 3)
                        <span class="text-success">负</span>({{ $item->final_give_score_rate }})
                    @endif
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        {{ $list->links() }}
    </div>
</div>
@endsection