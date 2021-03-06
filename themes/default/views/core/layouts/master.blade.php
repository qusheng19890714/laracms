<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Laravel Shop') - Laravel 电商教程</title>
    <!-- 样式 -->
    <link href="{{Theme::asset('css/app.css')}}" rel="stylesheet">
    <link href="{{Theme::asset('css/master.css')}}" rel="stylesheet">
</head>
<body>
<div id="app" class="{{ route_class() }}-page">
    @include('core::layouts._header')
    <div class="container">
        @yield('content')
    </div>
    @include('core::layouts._footer')
</div>
<!-- JS 脚本 -->
<script src="{{Theme::asset('js/jquery.min.js')}}"></script>
<script src="{{Theme::asset('js/popper.min.js')}}"></script>
<script src="{{Theme::asset('js/global.js')}}"></script>
<script src="{{Theme::asset('js/bootstrap.min.js')}}"></script>
</body>
</html>



