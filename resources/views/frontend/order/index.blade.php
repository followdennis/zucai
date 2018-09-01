@extends('frontend.layouts.main')
@section('STYLE')
<link rel="stylesheet" href="{{ asset('vendor/font-awesome/css/font-awesome.min.css') }}">
@endsection
@section('SCRIPT')

@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
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
                    <th>平均球</th>
                    <th>进球赔率</th>
                    <th style="width:101px;">查看比赛</th>
                </thead>
                <tbody>
            @foreach($groups as $k => $group)
                        <tr class="bg-info text-white">
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
                                        <span class="font-weight-bold">中奖金额</span>
                                        <span class="text-danger">{{ $group->money }}</span>
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
                        <tr>
                            <th scope="row">{{ $kk+1}}</th>
                            <td>{{ $item->match->host_team_name }}({{ $item->match->host_average }})</td>
                            <td>
                                <span @if($item->match->status == 2) class="font-weight-bold text-danger" @endif>{{ $item->match->host_team_score }}: {{ $item->match->guest_team_score }}</span>
                            </td>
                            <td>{{ $item->match->guest_team_name }} ({{ $item->match->guest_average }})</td>
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
                            <td>
                                {{ $item->match->host_average + $item->match->guest_average }}
                            </td>
                            <td>{{ $item->match->total_rate }}</td>
                            <td class="align-content-center"><button class="btn btn-sm btn-primary">比赛</button>&nbsp;<button class="btn btn-sm btn-info">详情</button></td>
                        </tr>
                    @endforeach
                        <tr>
                            <td colspan="11"><span style="float:left;display:block;margin-right:20px;">统计数据</span>
                            <ul class="order-statistics">
                                @if($group->is_finish == 1)
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
        </div>
    </div>
</div>
@endsection