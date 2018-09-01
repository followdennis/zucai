<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('logo/icon.png') }}" media="screen" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/global.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/layui/layui/css/layui.css') }}">

    @yield('STYLE')
</head>
<body>
<div id="app">
        @yield('content')
</div>
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('vendor/layui/layui/layui.js') }}"></script>
<script>
//一般直接写在一个js文件中
layui.use(['layer', 'form'], function(){
    var layer = layui.layer
        ,form = layui.form;
});


</script>
@yield('SCRIPT')
</body>
</html>
