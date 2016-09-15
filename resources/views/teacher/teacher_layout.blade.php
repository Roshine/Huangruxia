<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>计算机科学与技术导论</title>
    <link rel="stylesheet" href="{{ CDN_PATH }}/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ CDN_PATH }}/bootstrap-table/bootstrap-table.min.css">
    <link rel="stylesheet" href="{{ CDN_PATH }}/admin-lte/css/AdminLTE.min.css">
    <link rel="stylesheet" href="{{ CDN_PATH }}/admin-lte/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="{{ CDN_PATH }}/icheck/skins/square/blue.css">
    <link rel="stylesheet" href="{{ CDN_PATH }}/nprogress/nprogress.css">
    <link rel="stylesheet" type="text/css" href="{{ CDN_PATH }}/custom/teacher.css">
</head>
<body class="hold-transition layout-top-nav">
<div class="wrapper">
    <header class="main-header">
        <nav class="navbar navbar-inverse navheight" role="navigation">
            <div>
                <ul class="nav navbar-nav">
                    <li><a class="active" href="#/pre_class">课前预习</a></li>
                    <!-- <li><a href="#/class_discuss">课堂讨论</a></li> -->
                    <li><a href="#/homework">课后作业</a></li>
                    <li><a href="#/exp_test">实验测试</a></li>
                    <li><a href="#/week_sum">每周心得</a></li>
                    <li><a href="#/fill_grades">成绩录入</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
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
            <p> 武汉理工大学计算机与科学技术学院   地址：湖北省武汉市武汉理工大学  邮编：430070  2012-2015</p>
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
    <script src="{{ ASSETS_PATH }}/teacher/route.js"></script>


</div>
</body>