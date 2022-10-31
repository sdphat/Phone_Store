<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{asset("")}}assets/img/favicon.ico" />

    <title>Thế giới điện thoại</title>

    <!-- Load font awesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">

    <!-- Jquery -->
    <script src="{{asset("")}}assets/lib/Jquery/Jquery.min.js"></script>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>

    <!-- our files -->
    <!-- css -->
    <link rel="stylesheet" href="{{asset("")}}assets/css/style.css">
    <link rel="stylesheet" href="{{asset("")}}assets/css/topnav.css">
    <link rel="stylesheet" href="{{asset("")}}assets/css/header.css">
    <link rel="stylesheet" href="{{asset("")}}assets/css/taikhoan.css">
    <link rel="stylesheet" href="{{asset("")}}assets/css/gioHang.css">
    <link rel="stylesheet" href="{{asset("")}}assets/css/footer.css">
    <!-- js -->
    <script src="{{asset("")}}assets/js/dungchung.js"></script>
    <script src="{{asset("")}}assets/js/giohang.js"></script>


</head>

<body>
<x-header/>

<section style="min-height: 85vh">
    <table class="listSanPham"></table>
    <div class="modal fade" id="exampleModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" >Nhập thông tin thanh toán</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" onsubmit="return xacNhanThanhToan()">
                    <div class="modal-body" id="thongtinthanhtoan"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary" id="btnXacNhan">Xác nhận</button>
                    </div>
                </form>
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

<x-footer/>
</body>

</html>
