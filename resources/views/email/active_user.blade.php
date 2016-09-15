<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<body>
<h3>Dear {{ $username }} :</h3>
<p>Please click following link to active your account.</p><br>
<a href="{{ url('/register-active?token='.$token) }}">Click here to Active!</a><br><br>
</body>
</html>