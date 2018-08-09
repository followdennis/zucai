@extends('frontend.layouts.common')
@section('STYLE')
    <link rel="stylesheet" href="{{ asset('vendor/font-awesome/css/font-awesome.min.css') }}">
@endsection
@section('SCRIPT')
<script>

</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <table class="table table-bordered table-hover ">
            <thead class="thead-light">
            <tr>
                <th>序号</th>
                <th>编号</th>
                <th>主队</th>
                <th>客队</th>
                <th>让球</th>
                <th>结果</th>
                <th>比赛时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>1</td>
                <td>001</td>
                <td>日本</td>
                <td>韩国</td>
                <td>是</td>
                <td><span>胜</span><span class="rate">(3.21)</span></td>

                <td>2018-08-08 12:30:00</td>
                <td><font color="red"><i class="fa fa-close" aria-hidden="true"></i></font></td>
            </tr>
            <tr>
                <td>2</td>
                <td>001</td>
                <td>日本</td>
                <td>韩国</td>
                <td>否</td>
                <td>胜</td>

                <td>2018-08-08 12:30:00</td>
                <td><font color="red"><i class="fa fa-close" aria-hidden="true"></i></font></td>
            </tr>
            <tr>
                <td>3</td>
                <td>001</td>
                <td>日本</td>
                <td>韩国</td>
                <td>胜</td>
                <td>胜</td>

                <td>2018-08-08 12:30:00</td>
                <td><font color="red"><i class="fa fa-close" aria-hidden="true"></i></font></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection