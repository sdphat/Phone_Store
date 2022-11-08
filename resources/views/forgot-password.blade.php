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
<body>
<form onsubmit="return false;">
    <input type="email" placeholder="Nhập email" id="email">
    {!! NoCaptcha::renderJs("vi",false,'') !!}
    {!! NoCaptcha::display() !!}
    <button type="submit"onclick="send();">Gửi</button>
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
            error: function (e) {
                alert("Đã gửi yêu cầu tạo mật khẩu mới. Vui lòng xác nhận email và tạo lại mật khẩu mới");
                window.location="home";
            }
        });
        grecaptcha.reset();
    }
</script>
</body>
</html>
