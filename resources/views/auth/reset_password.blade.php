<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>重置密码</title>
    <link rel="stylesheet" href="{{ CDN_PATH }}/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ CDN_PATH }}/bootstrap-table/bootstrap-table.min.css">
    <link rel="stylesheet" href="{{ CDN_PATH }}/admin-lte/css/AdminLTE.min.css">
    <style type="text/css">
      #reset_psw{ margin: 0 auto; padding: 10px; width: 400px; height: 200px; border: 1px solid #eee; margin-top: 30px; }
    </style>

</head>
<body>

<form action="postResetPassword" method="post" id="reset_psw">
    <div class="form-group has-feedback">
        <label>密码：</label><input type="password" name="password" class="form-control" placeholder="密码">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
    </div>
    <div class="form-group has-feedback">
        <label>确认密码</label><input type="password" name="confirmPassword" class="form-control" placeholder="确认密码">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
    </div>
    <button type="submit" class="btn btn-success" id="submit">提交</button>
</form>
<script src="{{ CDN_PATH }}/jquery/jquery-2.1.4.min.js"></script>
<script>
    //get url中的字段值
    function getQueryString(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]); return null;
    }

    function csrf_init(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    }

    $(document).ready(function(){

        csrf_init();

       $('#submit').click(function(form){
           form.preventDefault();

           var password = $('input[name="password"]').val();
           //alert(password);
           var confirmPassword = $('input[name="confirmPassword"]').val();
           if(password == confirmPassword){
             if(password.length<6){
              alert('密码不能少于6位！')
             }else{
                $.post('./reset-password',{
                       password : password,
                       token : getQueryString('token'),
                       stuId : getQueryString('stuId')
                   },function(response){
                       if(!response.error){
                           //修改成功
                           alert('密码重置成功，请使用新密码登录');
                           window.location.href="./auth/login#/login";
                       } else {
                           //修改失败
                           alert(response.message);
                       }
                   }).fail(function(e){
                       //post失败
                       console.log(e);
                   });
             }
           }else{
            alert('两次输入密码不一致，请确认重置密码！')
           }          
       });
   });
</script>
</body>
</html>