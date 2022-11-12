<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta http-equiv="X-UA-Compatible" content="ie=edge"> -->
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Phone Store | Quên mật khẩu</title>
    <script src="{{asset("")}}assets/lib/Jquery/Jquery.min.js"></script>
</head>
<body style="display: flex;justify-content: center;align-items: center;">
<form onsubmit="return false;" style=" background:whitesmoke;border-radius:4px;padding:10px;display: flex;justify-content: center;flex-wrap: wrap;max-width: 350px;width: 100%;">
    <h3>Yêu cầu tạo mật khẩu mới</h3>
    <label for="email" style="padding-bottom: 10px;"><input style="width: 300px;height: 30px;border-radius: 2px;border: silver solid 1px;box-shadow: silver;outline:none" type="email" placeholder="Nhập email" id="email"></label>
    {!! NoCaptcha::renderJs("vi",false,'') !!}
    {!! NoCaptcha::display() !!}
    <button onclick="send();" style="margin:10px;background: blueviolet;color: white;border-radius: 2px; border: none;width: 100px;height: 34px">Gửi</button>
</form>
<script>
    function send(){
        let email=document.getElementById("email").value;
        $.ajax({
            url: "api/users",
            type: "post",
            dataType:"json",
            timeout: 5000,
            data: {
                function: 'handleForgotPassword',
                "email":email,
                "g-recaptcha-response": grecaptcha.getResponse()
            },
            success: function (data) {
                let m=""
                for(let i of data.noty){
                    m+=i+"\n";
                }
                alert(m);
            },
            error: function () {
                alert("Đã gửi yêu cầu tạo mật khẩu mới. Vui lòng xác nhận email và tạo lại mật khẩu mới");
                window.location="home";
            }
        });
        grecaptcha.reset();
    }
</script>
</body>
</html>
