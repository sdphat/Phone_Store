<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta http-equiv="X-UA-Compatible" content="ie=edge"> -->
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {!! NoCaptcha::renderJs("vi",false,'') !!}
    <title>Phone Store</title>
    <link rel="shortcut icon" href="{{asset("")}}assets/img/icon_phone_store.png"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" crossorigin="anonymous">

    <!-- Jquery -->
    <script src="{{asset("")}}assets/lib/Jquery/Jquery.min.js"></script>
    <!-- owl carousel libraries -->
    <link rel="stylesheet" href="{{asset("")}}assets/lib/owlcarousel/owl.carousel.min.css">
    <link rel="stylesheet" href="{{asset("")}}assets/lib/owlcarousel/owl.theme.default.min.css">
    <script src="{{asset("")}}assets/lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>

    <!-- our files -->
    <!-- css -->
    <link rel="stylesheet" href="{{asset("")}}assets/css/style.css">
    <link rel="stylesheet" href="{{asset("")}}assets/css/topnav.css">
    <link rel="stylesheet" href="{{asset("")}}assets/css/header.css">
    <link rel="stylesheet" href="{{asset("")}}assets/css/taikhoan.css">
    <link rel="stylesheet" href="{{asset("")}}assets/css/gioHang.css">
    <link rel="stylesheet" href="{{asset("")}}assets/css/nguoidung.css">
    <link rel="stylesheet" href="{{asset("")}}assets/css/footer.css">
    <!-- js -->
    <script src="{{asset("")}}assets/js/dungchung.js"></script>
    <script src="{{asset("")}}assets/js/nguoidung.js"></script>
</head>

<body>
<x-header/>
<section>
    <div class="infoUser"> </div>
    <div class="listDonHang"> </div>
    <div class="modal fade" id="exampleModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" >Chi tiết đơn hàng</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="chitietdonhang"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>

</section> <!-- End Section -->

<div class="containTaikhoan">
    <span class="close" onclick="showTaiKhoan(false);">&times;</span>
    <div class=" taikhoan">
        <ul class="tab-group">
            <li class="tab active"><a href="#login">Đăng nhập</a></li>
            <li class="tab"><a href="#signup">Đăng kí</a></li>
        </ul> <!-- /tab group -->
        <div class="tab-content">
            <div id="login">
                <h1>Chào mừng bạn trở lại!</h1>
                <!-- <form onsubmit="return logIn(this);"> -->
                <form action="" method="post" name="formDangNhap" onsubmit="return checkDangNhap();">
                    <div class="field-wrap">
                        <label>
                            Tên đăng nhập<span class="req">*</span>
                        </label>
                        <input name="username" type="text" id="username" required autocomplete="off" />
                    </div> <!-- /user name -->
                    <div class="field-wrap">
                        <label>
                            Mật khẩu<span class="req">*</span>
                        </label>
                        <input name="pass" type="password" id="pass" required autocomplete="off" />
                    </div> <!-- pass -->
                    <p class="forgot"><a href="#">Quên mật khẩu?</a></p>
                    <button type="submit" class="button button-block" />Tiếp tục</button>
                </form> <!-- /form -->
            </div> <!-- /log in -->
            <div id="signup">
                <h1>Đăng kí miễn phí</h1>
                <!-- <form onsubmit="return signUp(this);"> -->
                <form action="" method="post" name="formDangKy" onsubmit="return checkDangKy();">
                    <div class="top-row">
                        <div class="field-wrap">
                            <label>
                                Họ<span class="req">*</span>
                            </label>
                            <input name="ho" type="text" id="ho" required autocomplete="off" />
                        </div>
                        <div class="field-wrap">
                            <label>
                                Tên<span class="req">*</span>
                            </label>
                            <input name="ten" id="ten" type="text" required autocomplete="off" />
                        </div>
                    </div> <!-- / ho ten -->
                    <div class="top-row">
                        <div class="field-wrap">
                            <label>
                                Điện thoại<span class="req">*</span>
                            </label>
                            <input name="sdt" id="sdt" type="text" pattern="\d*" minlength="10" maxlength="12" required autocomplete="off" />
                        </div> <!-- /sdt -->
                        <div class="field-wrap">
                            <label>
                                Email<span class="req">*</span>
                            </label>
                            <input name="email" id="email" type="email" required autocomplete="off" />
                        </div> <!-- /email -->
                    </div>
                    <div class="field-wrap">
                        <label>
                            Địa chỉ<span class="req">*</span>
                        </label>
                        <input name="diachi" id="diachi" type="text" required autocomplete="off" />
                    </div> <!-- /user name -->
                    <div class="field-wrap">
                        <label>
                            Tên đăng nhập<span class="req">*</span>
                        </label>
                        <input name="newUser" id="newUser" type="text" required autocomplete="off" />
                    </div> <!-- /user name -->
                    <div class="field-wrap">
                        <label>
                            Mật khẩu<span class="req">*</span>
                        </label>
                        <input name="newPass" id="newPass" type="password" required autocomplete="off" />
                    </div> <!-- /pass -->
                    <button type="submit" class="button button-block" />Tạo tài khoản</button>
                </form> <!-- /form -->
            </div> <!-- /sign up -->
        </div><!-- tab-content -->
    </div> <!-- /taikhoan -->
</div>
<div class="plc">
    <section>
        <ul class="flexContain">
            <li>Giao hàng hỏa tốc trong 1 giờ</li>
            <li>Thanh toán linh hoạt: tiền mặt, visa / master, trả góp</li>
            <li>Trải nghiệm sản phẩm tại nhà</li>
            <li>Lỗi đổi tại nhà trong 1 ngày</li>
            <li>Hỗ trợ suốt thời gian sử dụng.
                <br>Hotline:
                <a href="tel:12345678" style="color: #288ad6;">1234.5678</a>
            </li>
        </ul>
    </section>
</div>

<x-footer/>
</body>

</html>
