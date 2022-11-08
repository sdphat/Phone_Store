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
<div class="login-container">
    <h1 class="text-whitesmoke">
        <img src="{{asset("assets/img/Logo_Phone_Store.png")}}" alt=""><br>
    </h1>
    <div class="container-content">
        <h3 class="title-form">Admin login</h3>
        <form onsubmit="return false;" class="margin-t">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Tên đăng nhập" name="username" id="username">
            </div>
            <div class="form-group">
                <input name="password" id="password" type="password" class="form-control"
                       placeholder="*****">
            </div>
            <div class="form-group">
                {!! NoCaptcha::renderJs("vi",false,'') !!}
                {!! NoCaptcha::display() !!}
            </div>
            <button type="submit" onclick="login()" class="btn btn-info">Đăng nhập</button>
            {{--            <a class="text-darkyellow" href="#"><small>Quên mật khẩu?</small></a>--}}
            {{--            <p class="text-whitesmoke text-center"><small>Do not have an account?</small></p>--}}
        </form>
        <p class="margin-t"><small> Dat's Team &copy; {{date("Y")}}</small></p>
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
            success: function (data, status, xhr) {
                let title = "";
                for (let m of data.message) {
                    title += m + "\n";
                }
                alert(title);
                if (data.success) {
                    window.location="admin";
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
