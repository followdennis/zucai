<table class="table table-hover table-bordered table-sm">
    <thead>
    <th scope="row">编号</th>
    <th>主队</th>
    <th>比分</th>
    <th>客队</th>
    <th>让球数</th>
    <th>投注结果</th>
    <th>赔率</th>
    <th>总进球数</th>
    <th>进球赔率</th>
    <th>平均球</th>
    <th style="width:101px;">查看比赛</th>
    </thead>
    <tbody>
    @foreach($groups as $k => $group)
        <tr class="bg-secondary text-white">
            <td colspan="11">
                <div class="row">
                    <div class="col-md-8">
                        <span class="font-weight-bold">编号</span>
                        <span>{{ $group->id }}</span>
                        <span class="font-weight-bold">下单时间</span>
                        <span>{{ $group->created_at }}</span>
                        <span class="font-weight-bold">比赛数目</span>
                        <span class="text-danger">{{ $group->match_num }}</span>
                        <span class="font-weight-bold">命中个数</span>
                        <span class="text-danger">{{ $group->correct_num }}</span>
                        <span class="font-weight-bold">是否中奖</span>
                        <span class="text-danger">{{ $group->is_correct }}</span>
                        <span class="font-weight-bold text-warning">理论奖金</span>
                        <span class="text-white">{{ $group->money }}</span>
                    </div>
                    <div class="col-md-4">
                        <ul class="order-res">
                            <li>结束时间:<span class="font-weight-bold" style="color:#e4e8ab!important;">{{ $group->end_time }}</span> </li>
                            @if($group->is_finish)
                                <li>是否结束 <span class="text-white"><i aria-hidden="true" class="fa fa-check"></i></span></li>
                            @else
                                <li>是否结束 <span class="text-danger"><i aria-hidden="true" class="fa fa-close"></i></span></li>
                            @endif
                        </ul>
                    </div>
                </div>

            </td>
        </tr>
        @foreach($group->items as $kk => $item )
            <tr item-id="{{ $item->match_id }}">
                <th scope="row">{{ $kk+1}}</th>
                <td class="host_team">
                    <div class="history_info" style="display:none;">
                        <ul class="history_list">
                            @foreach($item->host_history_score as $kk => $tt)
                                <li><span @if($tt->aim_team_id == $tt->host_team_id) class="{{ $tt->color }} font-weight-bold" @else  class="text-secondary" @endif>{{ $tt->host_team_name }}</span><span class="{{ $tt->host_score_color }} font-weight-bold">{{ $tt->host_score}}</span>: <span class="{{ $tt->guest_score_color }} font-weight-bold">{{ $tt->guest_score }}</span><span @if($tt->aim_team_id == $tt->guest_team_id) class="{{ $tt->color }} font-weight-bold" @else  class="text-secondary" @endif>{{ $tt->guest_team_name }}</span><span class="match-date">{{ $tt->date }}</span></li>
                            @endforeach
                        </ul>
                    </div>
                    {{ $item->match->host_team_name }}({{ $item->match->host_average }})</td>
                <td>
                    <span @if($item->match->status == 2) class="font-weight-bold text-danger" @endif>{{ $item->match->host_team_score }}: {{ $item->match->guest_team_score }}</span>
                </td>
                <td class="guest_team">
                    <div class="history_info" style="display:none;">
                        <ul class="history_list">
                            @foreach($item->guest_history_score as $kk => $tt)
                                <li><span @if($tt->aim_team_id == $tt->host_team_id) class="{{ $tt->color }} font-weight-bold" @else  class="text-secondary"  @endif>{{ $tt->host_team_name }}</span><span class="{{ $tt->host_score_color }}">{{ $tt->host_score}}</span>:<span class="{{ $tt->guest_score_color }}"> {{ $tt->guest_score }}</span><span @if($tt->aim_team_id == $tt->guest_team_id) class="{{ $tt->color }} font-weight-bold" @else  class="text-secondary" @endif>{{ $tt->guest_team_name }}</span><span class="match-date">{{ $tt->date }}</span></li>
                            @endforeach
                        </ul>
                    </div>
                    {{ $item->match->guest_team_name }} ({{ $item->match->guest_average }})</td>
                <td>{{ $item->give_score }}</td>
                <td>{{ $item->win }}
                    @if( $item->match->status == 2 && $item->betting_result == $item->match->match_result )
                        <span class="text-success">
                                        <i class="fa fa-check" aria-hidden="true"></i>
                                    </span>
                    @endif
                    @if($item->match->status == 2 && $item->betting_result != $item->match->match_result)
                        <span class="text-danger"><i class="fa fa-close" aria-hidden="true"></i></span>
                    @endif
                </td>
                <td>{{ $item->rate }}</td>
                <td>{{ $item->total }}
                    @if($item->match->status == 2 && $item->total == $item->match->total)
                        <span class="text-success"><i class="fa fa-check" aria-hidden="true"></i></span>
                    @elseif($item->match->status == 2)
                        <span class="text-danger"><i class="fa fa-close" aria-hidden="true"></i>({{ $item->match->total }})</span>
                    @endif
                </td>
                <td>{{ $item->match->total_rate }}</td>
                <td>
                    <span class="font-weight-bold @if($item->match->total_average_diff == 1) text-danger @else text-primary @endif">{{ $item->match->total_average}} </span>
                </td>
                <td class="align-content-center"><button class="btn btn-sm btn-primary match_table">比赛</button>&nbsp;<button class="btn btn-sm btn-info remark_detail" analogue-id="{{ $item->id }}">详情</button></td>
            </tr>
        @endforeach
        <tr>
            <td colspan="11"><span style="float:left;display:block;margin-right:20px;">统计数据</span>
                <ul class="order-statistics">
                    @if($group->is_finish == 1)
                        <li>总投入:<span class="font-weight-bold text-warning">{{ $group->invest_total }}</span></li>
                        <li>总比赛个数:<span class="font-weight-bold text-danger">{{ $group->match_no }}</span></li>
                        <li>串子正确数:<span class="font-weight-bold text-success">{{ $group->win_correct_num }}</span></li>
                        <li>总求正确数:<span class="font-weight-bold text-info">{{ $group->score_correct_num }}</span> </li>
                        <li>总求中奖额:<span class="font-weight-bold text-primary"> {{ $group->score_money }}</span> </li>
                        <li>累计净中奖: <span class="font-weight-bold text-danger">{{ $group->sum }}</span></li>
                    @endif
                </ul>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>