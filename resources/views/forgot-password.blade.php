<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="{{asset("")}}assets/lib/Jquery/Jquery.min.js"></script>
    <title>Document</title>
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
