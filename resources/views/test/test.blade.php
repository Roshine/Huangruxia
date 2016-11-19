<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>计算机科学与技术导论</title>
    <!-- <link rel="stylesheet" href="{{ CDN_PATH }}/nprogress/nprogress.css">
    <link rel="stylesheet" href="//g.alicdn.com/msui/sm/0.6.2/css/sm.min.css"> -->
    <link rel="stylesheet" href="{{ CDN_PATH }}/bootstrap/css/bootstrap.min.css">
    <style type="text/css">
        ul li{ list-style: none; height: 4rem; font-size: 2rem;}
    </style>
    <!-- <link rel="stylesheet" href="{{ CDN_PATH }}/custom/test.css">
 -->
</head>
<body >
  <div class="wrapper">
      <header class="main-header">
          <nav class="navbar navbar-inverse navheight" role="navigation">
              <div>
                  <ul class="nav navbar-nav">
                      <li><a class="active" href="./online#/pre_class">课前预习</a></li>
                     <li><a href="./online#/homework">课后作业</a></li>
                     <li><a href="./online#/exp_test">实验测试</a></li>
                     <li><a href="./online#/week_sum">每周心得</a></li>
                     <li><a href="./online#/my_grades">我的成就</a></li>
                  </ul>
              </div>
          </nav>
      </header>
      <div class="content-wrapper">
          <div class="container-fluid" id="main">
             <div style="width: 400px; margin:0 auto; height: 350px; padding:10px; border:1px solid #eee;">
                <ul id="myTab" class="nav nav-tabs">
                    <li class="active" style="width:50%;">
                        <a href="#email" data-toggle="tab">修改邮箱</a>
                    </li>
                    <li style="width:50%;"><a href="#modify_password" data-toggle="tab">修改密码</a></li>
                </ul>
                <div id="myTabContent" class="tab-content" style="padding-top:10px;">
                    <div class="tab-pane fade in active" id="email">
                        <p>
                          <div class="form-group">
                            <label>输入邮箱：</label><input name="tbEmail" type="text" class="form-control" id="tbEmail">
                          </div>
                          <button type="submit" class="btn btn-success" id="modify_email">修改邮箱</button>
                        </p>
                      </div>
                    <div class="tab-pane fade" id="modify_password">
                        <p>
                            <div class="form-group has-feedback">
                                <label>原密码：</label><input type="password" name="password" class="form-control" placeholder="原密码">
                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <label>新密码</label><input type="password" name="confirmPassword" class="form-control" placeholder="新密码">
                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                            </div>
                            <button type="submit" class="btn btn-success" id="modify_psw">修改密码</button>
                        </p>
                    </div>
                </div>
             </div>
          </div>
      </div>
  </div>
    <script src="{{ CDN_PATH }}/jquery/jquery-2.1.4.min.js"></script>
    <script src="{{ CDN_PATH }}/bootstrap/js/bootstrap.min.js"></script>
    <script src="{{ CDN_PATH }}/nprogress/nprogress.js"></script>
    <script src="{{ CDN_PATH }}/route/mm.routes.js"></script>
    <script src="{{ ASSETS_PATH }}/lt.js"></script>
   
</body>
</html>