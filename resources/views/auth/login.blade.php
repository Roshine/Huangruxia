<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>登录</title>
    <link rel="stylesheet" href="{{ CDN_PATH }}/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ CDN_PATH }}/admin-lte/css/AdminLTE.min.css">
    <link rel="stylesheet" href="{{ CDN_PATH }}/admin-lte/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="{{ CDN_PATH }}/icheck/skins/square/blue.css">
    <link rel="stylesheet" href="{{ CDN_PATH }}/nprogress/nprogress.css">
    <style type="text/css">
        .navheight{ height: 60px;}
        .navbar-nav li a ,.nav_title{ line-height: 40px; font-size: 20px; font-weight: 500;}
        .footer{text-align: center;}
    </style>
</head>
<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <header class="main-header">
            <nav class="navbar navbar-inverse navbar-fixed-top">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a href="#" class="navbar-brand nav_title"><b>计算机科学与技术导论（在线学习）</b></a>
                    </div>
                    <div class="collapse navbar-collapse" id="navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li id="register"><a href="#/register">注册 </a></li>
                            <li id="login"><a href="#/login">登录 </a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <div class="content-wrapper">
            <div class="container-fluid" id="main">
            </div>
        </div>

        <footer class="main-footer">
            <div class="footer">
               <p> 武汉理工大学计算机与科学技术学院   地址：湖北省武汉市武汉理工大学  邮编：430070  2015-2017</p>
               <p>Copyright&nbsp;<span style="font-family:Arial, Helvetica, sans-serif;">©</span>computingdream.com </p>
            </div>
        </footer>

        <script src="{{ CDN_PATH }}/jquery/jquery-2.1.4.min.js"></script>
        <script src="{{ CDN_PATH }}/bootstrap/js/bootstrap.min.js"></script>
        <script src="{{ CDN_PATH }}/admin-lte/js/app.js"></script>
        <script src="{{ CDN_PATH }}/nprogress/nprogress.js"></script>
        <script src="{{ CDN_PATH }}/route/mm.routes.js"></script>
        <script src="{{ CDN_PATH }}/bootstrap-validator/bootstrapValidator.min.js"></script>
        <script src="{{ CDN_PATH }}/icheck/icheck.min.js"></script>
        <script src="{{ ASSETS_PATH }}/lt.js"></script>
        <script src="{{ ASSETS_PATH }}/auth/route.js"></script>


    </div>
</body>