<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <title>计算机科学与技术导论</title>
    <link rel="stylesheet" href="{{ CDN_PATH }}/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ CDN_PATH }}/bootstrap-table/bootstrap-table.min.css">
    <link rel="stylesheet" href="{{ CDN_PATH }}/admin-lte/css/AdminLTE.min.css">
    <link rel="stylesheet" href="{{ CDN_PATH }}/admin-lte/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="{{ CDN_PATH }}/icheck/skins/square/blue.css">
    <link rel="stylesheet" href="{{ CDN_PATH }}/nprogress/nprogress.css">
    <link rel="stylesheet" href="{{ CDN_PATH }}/custom/online.css">
    
</head>
<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <header class="main-header">
            <nav class="navbar navbar-inverse navheight" role="navigation">
               <div>
                  <ul class="nav navbar-nav">
                     <li><a class="active" href="#/pre_class">课前预习</a></li>
                    <!--  <li><a href="#/class_discuss">课堂讨论</a></li> -->
                     <li><a href="#/homework">课后作业</a></li>
                     <li><a href="#/exp_test">实验测试</a></li>
                     <li><a href="#/week_sum">每周总结</a></li>
                     <li><a href="#/my_grades">我的成就</a></li>
                  </ul>
                  <ul class="nav navbar-nav navbar-right">
                      <li style="margin-right:20px;"><a>欢迎{{Auth::user()->name}}同学</a></li>
                      <li style="margin-right:20px;"><a href="#/stuInfo">个人信息 </a></li>
                      <li id="logout" style="margin-right:20px;"><a href="auth/logout">注销 </a></li>
                  </ul>
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
        <script src="{{ CDN_PATH }}/bootstrap-table/bootstrap-table.min.js"></script>
        <script src="{{ CDN_PATH }}/admin-lte/js/app.js"></script>
       <script src="{{ CDN_PATH }}/nprogress/nprogress.js"></script>
        <script src="{{ CDN_PATH }}/route/mm.routes.js"></script>
        <script src="{{ CDN_PATH }}/bootstrap-validator/bootstrapValidator.min.js"></script>
        <script src="{{ CDN_PATH }}/icheck/icheck.min.js"></script>
        <script src="{{ ASSETS_PATH }}/lt.js"></script>
        <script src="{{ ASSETS_PATH }}/online/route.js"></script>
        <script src="{{ CDN_PATH }}/echarts/echarts.js"></script>
    </div>
</body>