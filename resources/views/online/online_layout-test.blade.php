<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <title>计算机科学与技术导论</title>
  
    <link rel="stylesheet" href="//g.alicdn.com/msui/sm/0.6.2/css/sm.min.css">
</head>
<body >
  <div class="page-group">
        <!-- 单个page ,第一个.page默认被展示-->
        <div class="page">
            <!-- 标题栏 -->
            <header class="bar bar-nav">
                <a class="icon icon-me pull-left open-panel"></a>
                <h1 class="title">标题</h1>
            </header>

            <!-- 工具栏 -->
            <nav class="bar bar-tab">
                <a class="tab-item external active" href="#">
                    <span class="icon icon-home"></span>
                    <span class="tab-label">首页</span>
                </a>
                <a class="tab-item external" href="#">
                    <span class="icon icon-star"></span>
                    <span class="tab-label">收藏</span>
                </a>
                <a class="tab-item external" href="#">
                    <span class="icon icon-settings"></span>
                    <span class="tab-label">设置</span>
                </a>
            </nav>

            <!-- 这里是页面内容区 -->
            <div class="content">
                <div class="content-block">这里是content</div>
            </div>
        </div>

        <!-- 其他的单个page内联页（如果有） -->
        <div class="page">...</div>
    </div>

    <!-- popup, panel 等放在这里 -->
    <div class="panel-overlay"></div>
    <!-- Left Panel with Reveal effect -->
    <div class="panel panel-left panel-reveal">
        <div class="content-block">
            <p>这是一个侧栏</p>
            <p></p>
            <!-- Click on link with "close-panel" class will close panel -->
            <p><a href="#" class="close-panel">关闭</a></p>
        </div>
    </div>



        <script src="{{ CDN_PATH }}/jquery/jquery-2.1.4.min.js"></script>
    <!--     <script src="{{ CDN_PATH }}/bootstrap/js/bootstrap.min.js"></script>
        <script src="{{ CDN_PATH }}/bootstrap-table/bootstrap-table.min.js"></script> -->
        <!-- <script src="{{ CDN_PATH }}/admin-lte/js/app.js"></script> -->
       <script src="{{ CDN_PATH }}/nprogress/nprogress.js"></script>
        <script src="{{ CDN_PATH }}/route/mm.routes.js"></script>
        <script src="{{ CDN_PATH }}/bootstrap-validator/bootstrapValidator.min.js"></script>
        <script src="{{ CDN_PATH }}/icheck/icheck.min.js"></script>
        <script src="{{ ASSETS_PATH }}/lt.js"></script>
        <script type='text/javascript' src='//g.alicdn.com/sj/lib/zepto/zepto.min.js' charset='utf-8'></script>
    <script type='text/javascript' src='//g.alicdn.com/msui/sm/0.6.2/js/sm.min.js' charset='utf-8'></script>
    <script type='text/javascript' src='//g.alicdn.com/msui/sm/0.6.2/js/sm-extend.min.js' charset='utf-8'></script>
    <script src="{{ ASSETS_PATH }}/online/demo.js"></script>       
</body>