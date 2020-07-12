<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <link rel="stylesheet" href="/static/admin/css/font.css">
    <link rel="stylesheet" href="/static/admin/css/xadmin.css">
    <script src="/static/admin/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/static/admin/js/xadmin.js"></script>
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <!-- 导航开始 -->
    @yield('nav')
    <!-- 导航结束 -->
    <!-- 中部开始 -->
    @yield('content')
    <!-- 中部结束 -->
    <!-- 底部开始 -->
    <div class="bottom">
        <!--底部内容，直接写在里面-->
    </div>
    <!-- 底部结束 -->
</body>
@yield('js')
<style type="text/css">
    .uploader-list {
        margin-left: -15px;
    }

    .uploader-list .info {
        position: relative;
        margin-top: -25px;
        background-color: black;
        color: white;
        filter: alpha(Opacity=80);
        -moz-opacity: 0.5;
        opacity: 0.5;
        width: 100px;
        height: 25px;
        text-align: center;
        display: none;
    }

    .uploader-list .handle {
        position: relative;
        background-color: black;
        color: white;
        filter: alpha(Opacity=80);
        -moz-opacity: 0.5;
        opacity: 0.5;
        width: 100px;
        text-align: right;
        height: 18px;
        margin-bottom: -18px;
        display: none;
    }

    .uploader-list .handle i {
        margin-right: 5px;
    }

    .uploader-list .handle i:hover {
        cursor: pointer;
    }

    .uploader-list .file-iteme {
        margin: 12px 0 0 15px;
        padding: 1px;
        float: left;
    }
    .layui-form-label{
        width: 100px;
    }
    .layui-input-block {
        margin-left: 130px;
        min-height: 36px
    }
</style>
</html>
