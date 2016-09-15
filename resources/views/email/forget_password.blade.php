<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<body>
<h3> {{ $name }} 同学你好 :</h3>
<p>点击下面的链接重置你的密码</p><br>
<a href="{{ url('/reset-password?stuId='.$stuId.'&token='.$token) }}">点击重置密码</a><br><br>
<br>
</body>
</html>