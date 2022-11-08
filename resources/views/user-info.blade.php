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
            <label for="inputEmail4">Họ</label>
            <input type="text" class="form-control" id="ho" placeholder="Họ" value="{{$user->Ho}}">
        </div>
        <div class="form-group col-md-6">
            <label for="inputPassword4">Tên</label>
            <input type="text" class="form-control" id="ten" placeholder="Tên" value="{{$user->Ten}}">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="inputEmail4">Số điện thoại</label>
            <input type="text" class="form-control" id="sdt" placeholder="Số điện thoại" value="{{$user->SDT}}">
        </div>
        <div class="form-group col-md-6">
            <label for="inputPassword4">Địa chỉ</label>
            <input type="text" class="form-control" id="address" placeholder="Địa chỉ" value="{{$user->DiaChi}}">
        </div>
    </div>
    <button type="submit" class="btn btn-primary" onclick="confirm();">Xác nhận</button>
</form>
<script>
    function confirm(){
        let ho=document.getElementById("ho").value;
        let ten=document.getElementById("ten").value;
        let sdt=document.getElementById("sdt").value;
        let address=document.getElementById("address").value;
        $.ajax({
            url: "api/users",
            type: "Post",
            async: true,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                "function":"confirmRegister",
                "otp":"{{$user->api_token}}",
                "id":"{{$user->MaND}}",
                "ho":ho,
                "ten":ten,
                "sdt":sdt,
                "address":address,
            },
            success: function (data) {
                if(data==="OK")window.location="{{url("")}}/home";
            },
            error: function (xhr, exception) {
                var msg = "";
                if (xhr.status === 0) {
                    msg = "Not connect.\n Verify Network." + xhr.responseText;
                } else if (xhr.status == 404) {
                    msg = "Requested page not found. [404]" + xhr.responseText;
                } else if (xhr.status == 500) {
                    msg = "Internal Server Error [500]." +  xhr.responseText;
                } else if (exception === "parsererror") {
                    msg = "Requested JSON parse failed.";
                } else if (exception === "timeout") {
                    msg = "Time out error." + xhr.responseText;
                } else if (exception === "abort") {
                    msg = "Ajax request aborted.";
                } else {
                    msg = "Error:" + xhr.status + " " + xhr.responseText;
                }
                console.log(msg);
            }
        });
    }
</script>
</body>
</html>
