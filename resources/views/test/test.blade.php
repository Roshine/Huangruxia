<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <title>计算机科学与技术导论</title>
    <link rel="stylesheet" href="{{ CDN_PATH }}/nprogress/nprogress.css">
    <link rel="stylesheet" href="//g.alicdn.com/msui/sm/0.6.2/css/sm.min.css">
    <style type="text/css">
        ul li{ list-style: none; height: 4rem; font-size: 2rem;}
    </style>
    <!-- <link rel="stylesheet" href="{{ CDN_PATH }}/custom/test.css">
 -->
</head>
<body >
    <div class="page-group">
        <div class="page page-current">
            <div class="content native-scroll" id="page-index">
                <div class="content-inner">
                  <div class="content-block-title">第五周</div>
                  <div class="list-block">
                    <ul>
                      <li>
                        <a href="/demos/tabs" class="item-link item-content">
                          <div class="item-inner">
                            <div class="item-title">课前预习</div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="/demos/card" class="item-link item-content">
                          <div class="item-inner">
                            <div class="item-title">课后作业</div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="/demos/grid" class="item-link item-content">
                          <div class="item-inner">
                            <div class="item-title">每周心得</div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="/demos/modal" class="item-link item-content">
                          <div class="item-inner">
                            <div class="item-title">我的成就</div>
                          </div>
                        </a>
                      </li>
                    </ul>
                  </div>
                  <div class="list-block media-list">
                    <ul>
                      <li>
                        <div class="item-content">
                          <div class="item-media"><img src="http://gqianniu.alicdn.com/bao/uploaded/i4//tfscom/i3/TB10LfcHFXXXXXKXpXXXXXXXXXX_!!0-item_pic.jpg_250x250q60.jpg" style='width: 3.2rem;'></div>
                          <div class="item-inner">
                            <div class="item-title-row">
                              <div class="item-title">标题</div>
                            </div>
                          </div>
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            <div class="wrapper">
                <!-- <header class="main-header">
                    <nav class="navbar navbar-inverse navheight" role="navigation">
                        <div>
                            <ul class="nav navbar-nav">
                                <li><a class="active" href="#/pre_class">课前预习</a></li>
                                <li><a href="#/homework">课后作业</a></li>
                                <li><a href="#/exp_test">实验测试</a></li>
                                <li><a href="#/week_sum">每周总结</a></li>
                                <li><a href="#/week_sum">我的成就</a></li>
                                <li><a href=""><h3>test界面</h3></a></li> -->
                            <!-- </ul>
                        </div>
                    </nav>
                </header>
                <div class="content-wrapper">
                    <div class="container-fluid" id="main">
                    </div>
                </div> -->

                <!-- <footer class="main-footer">
                    <div class="footer">
                        <p> 武汉理工大学计算机与科学技术学院   地址：湖北省武汉市武汉理工大学  邮编：430070  2015-2017</p>
                        <p>Copyright&nbsp;<span style="font-family:Arial, Helvetica, sans-serif;">©</span>computingdream.com </p>
                    </div>
                </footer> -->
            </div>
        </div>
    </div>

    <script src="{{ CDN_PATH }}/jquery/jquery-2.1.4.min.js"></script>
    <script src="{{ CDN_PATH }}/nprogress/nprogress.js"></script>
    <script src="{{ CDN_PATH }}/route/mm.routes.js"></script>
    <script type='text/javascript' src='//g.alicdn.com/sj/lib/zepto/zepto.min.js' charset='utf-8'></script>
    <script type='text/javascript' src='//g.alicdn.com/msui/sm/0.6.2/js/sm.min.js' charset='utf-8'></script>
    <!-- <script src="{{ ASSETS_PATH }}/online/route.js"></script> -->
</body>
</html>