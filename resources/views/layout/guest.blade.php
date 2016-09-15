<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ PRODUCT_NAME }}</title>
    <link rel="stylesheet" href="/cdn/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/cdn/admin-lte/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/cdn/admin-lte/css/skins/_all-skins.min.css">
    <link rel='stylesheet' href='/cdn/nprogress/nprogress.css'/>
</head>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
    <header class="main-header">
        <nav class="navbar navbar-default" role="navigation">
           <div class="navbar-header">
              <a class="navbar-brand active" href="#">课前预习</a>
           </div>
           <div>
              <ul class="nav navbar-nav">
                 <li><a href="./class_discuss.html">课堂讨论</a></li>
                 <li><a href="./unit_test.html">单元测试</a></li>
                 <li><a href="./exp_test.html">实验测试</a></li>
                 <li><a href="./learning_style.html">学习风格</a></li>
              </ul>
           </div>
        </nav>
    </header>
    @yield('content')

    <footer class="main-footer">
        <div class="container-fluid">
            <div class="pull-right hidden-xs">
                <b>Version</b> {{ PRODUCT_VERSION }}
            </div>
            <strong>Copyright &copy; 2015-2016<a href="{{ url('/home') }}"> Analyses Company</a>.</strong> All rights reserved.
        </div>
    </footer>


    <script src="/cdn/jquery/jquery-2.1.4.min.js"></script>
    <script src="/cdn/bootstrap/js/bootstrap.min.js"></script>
    <script src="/cdn/admin-lte/js/app.js"></script>
    <script src='/cdn/nprogress/nprogress.js'></script>
    @yield('footer')


</div>
</body>

</html>
