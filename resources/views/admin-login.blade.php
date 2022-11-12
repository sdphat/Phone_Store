<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Phone Store | Admin login</title>
    <link rel="shortcut icon" href="{{asset("")}}assets/img/icon_phone_store.png"/>
    <!-- Jquery -->
    <script src="{{asset("")}}assets/lib/Jquery/Jquery.min.js"></script>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="{{asset("")}}assets/css/style.css">
    <link rel="stylesheet" href="{{asset("")}}assets/css/login.css">
</head>
<body>
<div class="margin-t" style="background: #f9f9f9;border-radius: 8px;overflow: hidden">
    <h1 class="text-whitesmoke" style="display: flex;justify-content: center">
        <img src="{{asset("assets/img/Logo_Phone_Store.png")}}" alt=""><br>
    </h1>
    <div class="container-content">
        <h3 class="title-form" style="text-align: center">Admin login</h3>
        <form onsubmit="return false;">
            <label for="username" class="form-group" style="width: 100%">
                <input type="text" class="form-control" placeholder="Tên đăng nhập" name="username" id="username">
            </label>
            <label for="password" class="form-group" style="width: 100%">
                <input name="password" id="password" type="password" class="form-control" placeholder="Mật khẩu">
            </label>
            <div class="form-group" style="width: 100%;">
                {!! NoCaptcha::renderJs("vi",false,'') !!}
                {!! NoCaptcha::display() !!}
                <a class="text-darkyellow" href="{{url("")}}/forgot-password"><small>Quên mật khẩu</small></a>
            </div>
            <div class="form-group" style="width: 100%;display: flex;justify-content: center">
                <button type="submit" onclick="login()" class="btn btn-info">Đăng nhập</button>
            </div>
        </form>
        <p class="margin-t" style="display: flex;justify-content: center"><small> Dat's Team &copy; {{date("Y")}}</small></p>
    </div>
</div>
<script>
    let a = document.getElementById('username');
    let b = document.getElementById('password');

    function login() {
        $.ajax({
            url: "api/users",
            type: "post",
            dataType: "json",
            timeout: 5000,
            data: {
                function: 'adminLogin',
                username: a.value,
                password: b.value,
                "g-recaptcha-response": grecaptcha.getResponse()
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                let title = "";
                for (let m of data.message) {
                    title += m + "\n";
                }
                alert(title);
                if (data.success) {
                    window.location = "admin";
                }
            },
            error: function (e) {
                console.log(e.responseText)
            }
        });
        grecaptcha.reset();
        return false;
    }
</script>
</body>
</html>
