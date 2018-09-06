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
                data:['主队','客队','累计']
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            toolbox: {
                feature: {
                    saveAsImage: {}
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
                    name:'主队',
                    type:'line',
                    stack: '总量',
                    data:[{{ $host_score }}]
                },
                {
                    name:'客队',
                    type:'line',
                    stack: '总量',
                    data:[{{ $guest_score }}]
                },
                {
                    name:'累计',
                    type:'line',
                    stack: '总量',
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
                data:['主队总球','客队总球','累计']
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            toolbox: {
                feature: {
                    saveAsImage: {}
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
                    name:'主队',
                    type:'line',
                    stack: '总量',
                    data:[{{ $host_total }}]
                },
                {
                    name:'客队',
                    type:'line',
                    stack: '总量',
                    data:[{{ $guest_total }}]
                },
                {
                    name:'累计',
                    type:'line',
                    stack: '总量',
                    data:[{{ $history_total }}]
                }
            ]
        };
        // 使用刚指定的配置项和数据显示图表。
        myChart2.setOption(option2);

        var myChart3 = echarts.init(document.getElementById('host_pie'));
        app.title = '环形图';

        option3 = {
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b}: {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                x: 'left',
                data:['直接访问','邮件营销','联盟广告','视频广告','搜索引擎']
            },
            series: [
                {
                    name:'访问来源',
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
                        {value:335, name:'直接访问'},
                        {value:310, name:'邮件营销'},
                        {value:234, name:'联盟广告'},
                        {value:135, name:'视频广告'},
                        {value:1548, name:'搜索引擎'}
                    ]
                }
            ]
        };
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
                data:['直接访问','邮件营销','联盟广告','视频广告','搜索引擎']
            },
            series: [
                {
                    name:'访问来源',
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
                        {value:335, name:'直接访问'},
                        {value:310, name:'邮件营销'},
                        {value:234, name:'联盟广告'},
                        {value:135, name:'视频广告'},
                        {value:1548, name:'搜索引擎'}
                    ]
                }
            ]
        };
        myChart4.setOption(option4);
    </script>
@endsection

<div class="container-fluid">
    <div class="row">
        <div class="col-md-9">
            <div id="team_score" style="width: 900px;height:200px;"></div>
        </div>
        <div class="col-md-3">
b
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div id="host_and_guest_total" style="width: 900px;height:200px;"></div>
        </div>
        <div class="col-md-4">
            b
        </div>
    </div>
    <div class="row">
        <div class="com-md-3">
            <div id="host_pie" style="width:200px;height:200px;margin-left:50px;">

            </div>
        </div>
        <div class="col-md-3">
            <div id="guest_pie" style="width:200px;height:200px;margin-left:50px;">

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
aa
        </div>
        <div class="col-md-6">
            bb
        </div>
    </div>
</div>