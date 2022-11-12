<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="{{asset("")}}assets/lib/Jquery/Jquery.min.js"></script>
    <title>Thông tin người dùng</title>
</head>
<body style="display: flex;justify-content: center;padding-top: 40px;">
<form style="max-width: 1200px;width: 100%;" onsubmit="return false;">
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="inputEmail4">Tên đăng nhập: {{$user->TaiKhoan}}</label><br>
            <label for="inputEmail4">Email: {{$user->Email}}</label>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="new">Mật khẩu mới</label>
            <input type="password" class="form-control" id="new" placeholder="Mật khẩu mới" value="">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="again">Nhập lần nữa</label>
            <input type="password" class="form-control" id="again" placeholder="Nhập lần nữa" value="">
        </div>
    </div>
    <button type="submit" class="btn btn-primary" onclick="confirm();">Xác nhận</button>
</form>
<script>
    function confirm() {
        let newPass = document.getElementById("new").value;
        let again = document.getElementById("again").value;
        $.ajax({
            url: "api/users",
            type: "Post",
            async: true,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                "function": "handleNewPassword",
                "new":newPass,
                "again":again,
                "token":"{{ $user->api_token }}"
            },
            success: function (data) {
                alert(data);
                if(data==="Đổi mật khẩu thành công"){
                    window.location="home";
                }
            },
            error: function () {
                console.log("Lỗi");
            }
        });
    }
</script>
</body>
</html>
