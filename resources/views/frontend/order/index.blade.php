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
                    <th>进球赔率</th>
                </thead>
                <tbody>
            @foreach($groups as $k => $group)
                        <tr class="bg-info text-white">
                            <td colspan="9">
                                <div class="row">
                                    <div class="col-md-12">
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
                                </div>

                            </td>
                        </tr>
                    @foreach($group->items as $kk => $item )
                        <tr>
                            <th scope="row">{{ $kk+1}}</th>
                            <td>{{ $item->match->host_team_name }}</td>
                            <td>
                                <span @if($item->match->status == 2) class="font-weight-bold text-danger" @endif>{{ $item->match->host_team_score }}: {{ $item->match->guest_team_score }}</span>
                            </td>
                            <td>{{ $item->match->guest_team_name }}</td>
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
                        </tr>
                    @endforeach
                        <tr>
                            <td colspan="9">统计数据</td>
                        </tr>
            @endforeach
                    </tbody>
            </table>
        </div>
    </div>
</div>
@endsection