<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

</head>
<body>

<form action="postResetPassword" method="post">
    <div class="form-group has-feedback">
        <input type="password" name="password" class="form-control" placeholder="密码">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
    </div>
    <div class="form-group has-feedback">
        <input type="password" name="confirmPassword" class="form-control" placeholder="确认密码">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
    </div>
    <button type="submit" id="submit">提交</button>
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

           $.post('./reset-password',{
               password : password,
               token : getQueryString('token'),
               stuId : getQueryString('stuId')
           },function(response){
               if(!response.error){
                   //修改成功
               } else {
                   //修改失败
               }
           }).fail(function(e){
               //post失败
           });

       });
    });
</script>
</body>
</html>