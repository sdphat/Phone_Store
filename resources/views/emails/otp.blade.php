<!DOCTYPE html>
<html>
<head>
    <title>ItsolutionStuff.com</title>
</head>
<body>
<h1>{{ $mailData['title'] }}</h1>

<p>Bạn đã đăng ký tài khoản trên trang Phone Store nếu đúng vậy bạn vui lòng xác nhận tài khoảng, nếu không bạn có thể bỏ qua email này.</p>
<a href="{{url("confirm-email?otp=".$mailData["otp"])}}">Xác nhận đăng ký</a>
<p>Cảm ơn!</p>
</body>
</html>
