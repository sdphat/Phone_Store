<!DOCTYPE html>
<html>
<head>
    <title>ItsolutionStuff.com</title>
</head>
<body>
<h1>{{ $data['title'] }}</h1>

<p>Bạn đã yêu cầu đổi tài khoản trên trang Phone Store nếu đúng vậy bạn vui lòng xác nhận và tạo mật khẩu mới, nếu không bạn có thể bỏ qua email này.</p>
<a href="{{url("new-password?token=".$data["token"])}}">Xác nhận đăng ký</a>
<p>Cảm ơn!</p>
</body>
</html>
