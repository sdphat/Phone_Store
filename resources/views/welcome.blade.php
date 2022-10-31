<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hello</title>
</head>
<body>
<script>
    let filters=["star=0","sort=SoDanhGia-desc","page=0"];
    filters=["price=2000000-2500000","sort=DonGia-asc","page=0"]
    $.ajax({
        url: "api/users",
        type: "post",
        timeout: 5000,
        data: {
            function: 'register',
            data_ho: "Nguyễn",
            data_ten: "Tấn Đạt",
            data_sdt: "0775820223",
            data_email: "nguyentandat16052000@gmail.com",
            data_diachi: "123 Cao Đạt, P.3, Q.5, TP.Hồ Chí Minh",
            data_newUser: "Nguyễn Tấn Đạt",
            data_newPass: "123456"
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(kq) {
            document.write(kq);
            // if(kq != null) {
            //     Swal.fire({
            //         type: 'success',
            //         title: 'Đăng kí thành công ' + kq.TaiKhoan,
            //         text: 'Bạn sẽ được đăng nhập tự động',
            //         confirmButtonText: 'Tuyệt'
            //     }).then((result) => {
            //         capNhatThongTinUser();
            //         showTaiKhoan(false);
            //     });
            // }
        },
        error: function(e) {
            Swal.fire({
                type: "error",
                title: "Lỗi",
                // html: e.responseText
            });
            console.log(e.responseText)
        }
    });
</script>
</body>
</html>
