<head>
    <link rel="stylesheet" href="{{asset("")}}assets/css/login.css">
    <!-- <link rel="stylesheet" href="css/bootstrap.min.css"> -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Jquery -->
    <script src="{{asset("")}}assets/lib/Jquery/Jquery.min.js"></script>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>

        function kiemTraDangNhap() {
            a = document.getElementById("username").value;
            b = document.getElementById("password").value;
            $.ajax({
                url: "api/users",
                type: "post",
                data: {
                    function: "adminLogin",
                    username: a,
                    password: b,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                //async:true,
                success: function (kq) {
                    console.log(kq);
                    if (kq.indexOf("yes") != -1) {
                        alert("Đăng nhập thành công");
                        window.location = "admin";
                    } else {
                        alert(kq);
                        form.username.focus();
                    }
                }

            });
        }
    </script>

</head>
<body class="main-bg" id="hienthiadmin">
<div class="login-container text-c animated flipInX">
    <div>
        <h1 class="logo-badge text-whitesmoke"><span class="fa fa-user-circle"></span></h1>
    </div>
    <h3 class="text-whitesmoke">Đăng nhập Admin</h3>
    <p class="text-whitesmoke"></p>
    <div class="container-content">
        <form action="" method="post" name="form" class="margin-t">
            <div class="form-group">
                <input name="username" id="username" type="text" class="form-control" placeholder="Tên đăng nhập">
            </div>
            <div class="form-group">
                <input name="password" id="password" type="password" class="form-control" placeholder="*****">
            </div>
            <button type="button" class="form-button button-l margin-b" onclick="kiemTraDangNhap()">Sign In</button>
            <div id="hienthiketqua"></div>
            <!-- Xử lí đăng nhập với thông tin tài khoản trên database -->

            <!-- <a class="text-darkyellow" href="#"><small>Forgot your password?</small></a> -->
            <!-- <p class="text-whitesmoke text-center"><small>Do not have an account?</small></p> -->
        </form>
        <!-- <p class="margin-t text-whitesmoke"><small> Your Name &copy; 2019</small> </p> -->
    </div>
</div>
</body>

