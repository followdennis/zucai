@extends('frontend.layouts.common')
@section('STYLE')

@endsection
@section('SCRIPT')
    <script src="{{ asset('js/echarts.common.min.js') }}"></script>
    <script type="text/javascript">
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('team_score'));

        // 指定图表的配置项和数据
        var host_data = '{{ $host_score }}';
        var option = {
            title: {
                text: '进球走势'
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data:['{{ $data->host_team_name }}','{{ $data->guest_team_name }}','累计']
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            toolbox: {
                feature: {
                    saveAsImage: false
                }
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                data: ['1','2','3','4','5','6','7','8','9','10']
            },
            yAxis: {
                type: 'value'
            },
            series: [
                {
                    name:'{{ $data->host_team_name }}',
                    type:'line',

                    data:[{{ $host_score }}]
                },
                {
                    name:'{{ $data->guest_team_name }}',
                    type:'line',

                    data:[{{ $guest_score }}]
                },
                {
                    name:'累计',
                    type:'line',

                    data:[{{ $total_score }}]
                }
            ]
        };
        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);

        // 基于准备好的dom，初始化echarts实例
        var myChart2 = echarts.init(document.getElementById('host_and_guest_total'));

        // 指定图表的配置项和数据
        var option2 = {
            title: {
                text: '主队和客队总进球趋势'
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data:['{{ $data->host_team_name }}','{{ $data->guest_team_name }}','累计']
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            toolbox: {
                feature: {
                    saveAsImage: false
                }
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                data: ['1','2','3','4','5','6','7','8','9','10']
            },
            yAxis: {
                type: 'value'
            },
            series: [
                {
                    name:'{{ $data->host_team_name }}',
                    type:'line',
                    data:[{{ $host_total }}]
                },
                {
                    name:'{{ $data->guest_team_name }}',
                    type:'line',
                    data:[{{ $guest_total }}]
                },
                {
                    name:'累计',
                    type:'line',
//                    stack: '总量',
                    data:[{{ $history_total }}]
                }
            ]
        };
        // 使用刚指定的配置项和数据显示图表。
        myChart2.setOption(option2);

        //主队胜平负比例
        var myChart3 = echarts.init(document.getElementById('host_pie'));
        myChart3.title = '{{ $data->host_team_name }}';

        option3 = {
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b}: {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                x: 'left',
                data:['胜','平','负']
            },
            series: [
                {
                    name:'{{ $data->host_team_name }}',
                    type:'pie',
                    radius: ['50%', '70%'],
                    avoidLabelOverlap: false,
                    label: {
                        normal: {
                            show: false,
                            position: 'center'
                        },
                        emphasis: {
                            show: true,
                            textStyle: {
                                fontSize: '30',
                                fontWeight: 'bold'
                            }
                        }
                    },
                    labelLine: {
                        normal: {
                            show: false
                        }
                    },
                    data:[
                        {value:{{ $host_times['win'] }}, name:'胜'},
                        {value:{{ $host_times['draw'] }}, name:'平'},
                        {value:{{ $host_times['fail'] }}, name:'负'}
                    ]
                }
            ]
        };
        //客队胜平负比例
        myChart3.setOption(option3);
        //客队饼状图
        var myChart4 = echarts.init(document.getElementById('guest_pie'));
        myChart4.title = '客队胜平负情况';

        option4 = {
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b}: {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                x: 'left',
                data:['胜','平','负']
            },
            series: [
                {
                    name:'{{ $data->guest_team_name }}',
                    type:'pie',
                    radius: ['50%', '70%'],
                    avoidLabelOverlap: false,
                    label: {
                        normal: {
                            show: false,
                            position: 'center'
                        },
                        emphasis: {
                            show: true,
                            textStyle: {
                                fontSize: '30',
                                fontWeight: 'bold'
                            }
                        }
                    },
                    labelLine: {
                        normal: {
                            show: false
                        }
                    },
                    data:[
                        {value:{{ $guest_times['win'] }}, name:'胜'},
                        {value:{{ $guest_times['draw'] }}, name:'平'},
                        {value:{{ $guest_times['fail'] }}, name:'负'}
                    ]
                }
            ]
        };
        myChart4.setOption(option4);
        //主客队胜平负趋势
        var myChart5 = echarts.init(document.getElementById('match_result'));
        option5 = {
            title: {
                text: '主客队胜负趋势'
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data:['{{ $data->host_team_name }}', '{{ $data->guest_team_name }}']
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            toolbox: {
                feature: {
                    saveAsImage: false
                }
            },
            xAxis: {
                type: 'category',
                data: ['1', '2', '3', '4', '5', '6', '7','8','9','10']
            },
            yAxis: {
                type: 'value'
            },
            series: [
                {{--{--}}
                    {{--name:'{{ $data->host_team_name }}',--}}
                    {{--type:'bar',--}}
                    {{--step: 'start',--}}
                    {{--data:[{{ $host_result }}]--}}
                {{--},--}}
                {{--{--}}
                    {{--name:'{{ $data->guest_team_name }}',--}}
                    {{--type:'bar',--}}
                    {{--step: 'middle',--}}
                    {{--data:[{{ $guest_result }}]--}}
                {{--},--}}
                {
                    name:'{{ $data->host_team_name }}',
                    type:'line',
                    data:[{{ $host_result }}]
                },
                {
                    name:'{{ $data->guest_team_name }}',
                    type:'line',
                    data:[{{ $guest_result }}]
                }
            ]
        };
        myChart5.setOption(option5);
    </script>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div id="team_score" style="width: 750px;height:200px;"></div>
        </div>
        <div class="col-md-4">
            <table class="table table-bordered table-md">
                <thead>
                    <th colspan="4" class="text-center bg-warning text-white">统计数据</th>
                </thead>
                <tbody>
                    <tr>
                        <th class="text-primary">主队平均进球</th>
                        <td class="text-danger font-weight-bold">{{ $host_math['average'] }}</td>
                        <td>{{ $host_math['variance'] }}</td>
                        <td>{{ $host_math['square'] }}</td>
                    </tr>
                    <tr>
                        <th class="text-primary">客队平均进球</th>
                        <td class="text-danger font-weight-bold">{{ $guest_math['average'] }}</td>
                        <td>{{ $guest_math['variance'] }}</td>
                        <td>{{ $guest_math['square'] }}</td>
                    </tr>
                    <tr>
                        <td>总计</td>
                        <td>{{ $host_math['average']+ $guest_math['average'] }}</td>
                        <td>{{ $host_math['variance'] - $guest_math['variance'] }}</td>
                        <td>{{ $host_math['square'] - $guest_math['square'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div id="match_result" style="width:750px;height:200px;">
            </div>
        </div>
        <div class="col-md-4">
            <table class="table table-bordered table-sm ">
                <thead>
                    <tr>
                        <th colspan="3" class="text-center">{{ $data->host_team_name }} vs {{ $data->guest_team_name }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>编号</td>
                        <td>联盟</td>
                        <td>时间</td>
                    </tr>
                    <tr>
                        <td class="text-danger font-weight-bold">{{ $data->match_number }}</td>
                        <td class="text-primary">{{ $data->competition_name }}</td>
                        <td class="text-warning">{{ date('m-m H:i',strtotime($data->match_time)) }}</td>
                    </tr>
                    <tr>
                        <td>胜</td>
                        <td>平</td>
                        <td>负</td>
                    </tr>
                    <tr>
                        <td>{{ $data->win_rate_1 }}</td>
                        <td>{{ $data->draw_rate_1 }}</td>
                        <td>{{ $data->fail_rate_1 }}</td>
                    </tr>
                    <tr>
                        <th>主队平均</th>
                        <td>{{$host_total_average}}</td>
                        <td rowspan="2" class="text-center font-weight-bold text-success">{{ $host_total_average + $guest_total_average }}</td>
                    </tr>
                    <tr>
                        <th>客队平均</th>
                        <td>{{ $guest_total_average }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div id="host_and_guest_total" style="width: 750px;height:200px;"></div>
        </div>
        <div class="com-md-2">
            <div id="host_pie" style="width:150px;height:150px;margin-top:20px;">
            </div>
            主队:{{ $data->host_team_name }} (<font class="text-primary font-weight-bold">{{$data->host_team_rank}}</font>)
        </div>
        <div class="col-md-2">
            <div id="guest_pie" style="width:150px;height:150px;margin-top:20px;">
            </div>
            客队:{{ $data->guest_team_name }} (<font class="text-primary font-weight-bold">{{ $data->guest_team_rank }}</font>)
        </div>
    </div>
</div>
@endsection