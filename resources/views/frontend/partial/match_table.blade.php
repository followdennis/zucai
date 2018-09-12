<table class="table table-bordered  table-hover table-sm text-center" border="1" id="match-list-table">
    <thead class="bg-secondary text-light text-center">
    <tr>
        @if($type == 'match_list')
            <th scope="col" >序号</th>
            <th scope="col">场次</th>
            <th scope="col" style="width:50px;">比赛时间</th>
        @endif
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
        <th scope="col">平均进球</th>
        @if($type== 'match_list')<th scope="col">最后更新</th>@endif
        <th scope="col">状态</th>
        <th scope="col">推荐/指数</th>
        @if($type == 'match_list')
        <th scope="col">操作</th>
        @endif
    </tr>
    </thead>
    <tbody>
    @foreach($list as $k => $item)
        <tr item-id="{{ $item->id }}">
            @if($type == 'match_list')
            <th scope="row" rowspan="2" >
                {{ $k+1 }}
                <input type="checkbox" name="check[]" value="{{ $item->id }}" aria-label="Checkbox for following text input" >
            </th>
            <td rowspan="2">{{ $item->match_number }}</td>
            <td rowspan="2">{{ $item->match_time }}</td>
            @endif
            <td rowspan="2" class="text-center small"  @if($type == 'match_detail')  title="{{ $item->match_time }}" @endif>
                <span class="@if($item->hope == 1) text-success @endif">{{ $item->competition_name }} </span><br/>
                @php
                    $rank_pos = $item->rankDiff > 0 ? 3: ($item->rankDiff == 0 ? 2 : 1);
                @endphp
                @if($rank_pos == $item->match_result && $item->hope == 1)
                    <span style="color:red;"><i class="fa fa-star" aria-hidden="true"></i></span>
                @elseif($rank_pos == $item->rateHopeTeam)
                    <span><i class="fa fa-star" aria-hidden="true"></i></span>
                @endif
                @if($item->isOpposite && $item->match_result == 2)
                    <font color="blue"><i class="fa fa-heart" aria-hidden="true"></i></font>
                @elseif($item->isOpposite)
                    <i class="fa fa-heart-o" aria-hidden="true"></i>
                @endif
            </td>
            <td rowspan="2" class="small host_team">
                <div class="history_info" style="display:none;">
                    <ul class="history_list">
                        @foreach($item->host_history_score as $kk => $tt)
                            <li><span @if($tt->aim_team_id == $tt->host_team_id) class="{{ $tt->color }} font-weight-bold" @else  class="text-secondary" @endif>{{ $tt->host_team_name }}</span><span class="{{ $tt->host_score_color }} font-weight-bold">{{ $tt->host_score}}</span>: <span class="{{ $tt->guest_score_color }} font-weight-bold">{{ $tt->guest_score }}</span><span @if($tt->aim_team_id == $tt->guest_team_id) class="{{ $tt->color }} font-weight-bold" @else  class="text-secondary" @endif>{{ $tt->guest_team_name }}</span><span class="match-date">{{ $tt->date }}</span></li>
                        @endforeach
                    </ul>
                </div>
                {{ $item->host_team_name }}<br/>
                {{ $item->host_team_rank }}
            </td>
            <td rowspan="2" class="text-center">
                <span class="@if($item->status == 2)text-danger  @endif font-weight-bold">{{ $item->host_team_score }}</span>
                <span class="@if($item->status == 2)text-danger @endif">-</span>
                <span class="@if($item->status == 2)text-danger @endif font-weight-bold">{{ $item->guest_team_score }}</span>
            </td>
            <td rowspan="2" class="text-center small guest_team">
                <div class="history_info" style="display:none;">
                    <ul class="history_list">
                        @foreach($item->guest_history_score as $kk => $tt)
                            <li><span @if($tt->aim_team_id == $tt->host_team_id) class="{{ $tt->color }} font-weight-bold" @else  class="text-secondary"  @endif>{{ $tt->host_team_name }}</span><span class="{{ $tt->host_score_color }}">{{ $tt->host_score}}</span>:<span class="{{ $tt->guest_score_color }}"> {{ $tt->guest_score }}</span><span @if($tt->aim_team_id == $tt->guest_team_id) class="{{ $tt->color }} font-weight-bold" @else  class="text-secondary" @endif>{{ $tt->guest_team_name }}</span><span class="match-date">{{ $tt->date }}</span></li>
                        @endforeach
                    </ul>
                </div>
                {{ $item->guest_team_name }}<br/>
                {{ $item->guest_team_rank }}
            </td>
            <td>{{ $item->give_score_1 }}</td>
            <td class="rate_line1" data-finish="{{ $item->status }}" style="background-color:cornsilk">
                <span @if($item->match_result == 1)class="{{ $item->color1 }} font-weight-bold"  @endif  res="1">{{ $item->win_rate_1 }}</span>&nbsp;
                <span @if($item->match_result == 2)class=" {{ $item->color1 }} font-weight-bold" @endif  res="2">{{ $item->draw_rate_1 }}</span>&nbsp;
                <span @if($item->match_result == 3)class=" {{ $item->color1 }} font-weight-bold" @endif res="3">{{ $item->fail_rate_1 }}</span>
            </td>
            <td class="small"  style="background-color:cornsilk">

                @if($item->match_result == 1)
                    <span class="text-danger" data-rate="{{ $item->final_rate }}">胜</span>({{ $item->final_rate }})
                @elseif($item->match_result == 2)
                    <span class="text-primary" data-rate="{{ $item->final_rate }}">平</span>({{ $item->final_rate }})
                @elseif($item->match_result == 3)
                    <span class="text-success" data-rate="{{ $item->final_rate }}">负</span>({{ $item->final_rate }})
                @elseif($item->status == 0)
                    @include('frontend.partial.score_select')
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
                {{ $item->host_average }} - {{ $item->guest_average }} 差  <font color="red"><b>{{ $item->host_average - $item->guest_average }} </b></font> 和  <font  color="blue"><b>{{ $item->host_average + $item->guest_average }} </b></font>
            </td>
            @if($type == 'match_list')
            <td rowspan="2" title="{{ $item->detail_url }}">
                {{ $item->updateDate }}
            </td>
            @endif
            <td rowspan="2" class="small">@if($item->status == 2) <font color="green">结束</font> @elseif($item->status == 3) <font color="red">异常</font> @elseif($item->status == 0)<font color="silver">未</font>   @endif</td>
            <td rowspan="2">
                   <span  @if($item->match_result == $item->average_res) class="btn btn-success btn-sm"  @endif>
                           @if($item->average_res == 1)
                           胜
                       @elseif($item->average_res == 2)
                           平
                       @elseif($item->average_res == 3)
                           负
                       @endif

                   </span> <br/>
                62
            </td>
            @if($type == 'match_list')
            <td rowspan="2">
                <button type="button" class="btn btn-danger btn-sm remark_detail">分析</button>
            </td>
            @endif
        </tr>
        <tr item-id="{{ $item->id }}">
            <td>{{ $item->give_score_2 }}</td>
            <td class="rate_line2"  data-finish="{{ $item->status }}">
                <span @if($item->match_give_score_result == 1) class="{{ $item->color2 }}  font-weight-bold " @endif res="1">{{ $item->win_rate_2 }}</span>&nbsp;
                <span @if($item->match_give_score_result == 2)class="{{ $item->color2 }} font-weight-bold" @endif res="2">{{ $item->draw_rate_2 }}</span>
                <span @if($item->match_give_score_result == 3)class="{{ $item->color2 }} font-weight-bold" @endif res="3">{{ $item->fail_rate_2 }}</span>
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