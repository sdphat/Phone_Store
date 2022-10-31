<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta http-equiv="X-UA-Compatible" content="ie=edge"> -->
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Phone Store</title>
    <link rel="shortcut icon" href="{{asset("")}}assets/img/icon_phone_store.png"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" crossorigin="anonymous">
    <script src="{{asset("")}}assets/lib/Jquery/Jquery.min.js"></script>
    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>

    <!-- our files -->
    <!-- css -->
    <link rel="stylesheet" href="{{asset("")}}assets/css/style.css">
    <link rel="stylesheet" href="{{asset("")}}assets/css/topnav.css">
    <link rel="stylesheet" href="{{asset("")}}assets/css/header.css">
    <link rel="stylesheet" href="{{asset("")}}assets/css/taikhoan.css">
    <link rel="stylesheet" href="{{asset("")}}assets/css/chitietsanpham.css">
    <link rel="stylesheet" href="{{asset("")}}assets/css/footer.css">
    <!-- js -->
    <script src="{{asset("")}}assets/js/dungchung.js"></script>
    <script src="{{asset("")}}assets/js/chitietsanpham.js"></script>
</head>

<body>
<x-header/>
<section>

    <div class="chitietSanpham" style="min-height: 85vh">
        <h1>Điện thoại </h1>
        <div class="rowdetail group">
            <div class="picture">
                <img src="">
            </div>
            <div class="price_sale">
                <div class="area_price"> </div>
                <div class="ship" style="display: none;">
                    <i class="fa fa-clock-o"></i>
                    <div>NHẬN HÀNG TRONG 1 GIỜ</div>
                </div>
                <div class="area_promo">
                    <strong>khuyến mãi</strong>
                    <div class="promo">
                        <i class="fa fa-check-circle"></i>
                        <div id="detailPromo"> </div>
                    </div>
                </div>
                <div class="policy">
                    <div>
                        <i class="fa fa-archive"></i>
                        <p>Trong hộp có: Sạc, Tai nghe, Sách hướng dẫn, Cây lấy sim, Ốp lưng </p>
                    </div>
                    <div>
                        <i class="fa fa-star"></i>
                        <p>Bảo hành chính hãng 12 tháng.</p>
                    </div>
                    <div class="last">
                        <i class="fa fa-retweet"></i>
                        <p>1 đổi 1 trong 1 tháng nếu lỗi, đổi sản phẩm tại nhà trong 1 ngày.</p>
                    </div>
                </div>
                <div class="area_order">
                    <!-- nameProduct là biến toàn cục được khởi tạo giá trị trong phanTich_URL_chiTietSanPham -->
                    <a class="buy_now" onclick="themVaoGioHang(maProduct, nameProduct);">
                        <h3><i class="fa fa-plus"></i> Thêm vào giỏ hàng</h3>
                    </a>
                </div>
            </div>
            <div class="info_product">
                <h2>Thông số kỹ thuật</h2>
                <ul class="info">

                </ul>
            </div>
        </div>
        <hr>
        <div class="comment-area">
            <div class="guiBinhLuan">
                <div class="stars">
                    <form action="">
                        <input class="star star-5" id="star-5" value="5" type="radio" name="star"/>
                        <label class="star star-5" for="star-5" title="Tuyệt vời"></label>

                        <input class="star star-4" id="star-4" value="4" type="radio" name="star"/>
                        <label class="star star-4" for="star-4" title="Tốt"></label>

                        <input class="star star-3" id="star-3" value="3" type="radio" name="star"/>
                        <label class="star star-3" for="star-3" title="Tạm"></label>

                        <input class="star star-2" id="star-2" value="2" type="radio" name="star"/>
                        <label class="star star-2" for="star-2" title="Khá"></label>

                        <input class="star star-1" id="star-1" value="1" type="radio" name="star"/>
                        <label class="star star-1" for="star-1" title="Tệ"></label>
                    </form>
                </div>
                <textarea maxlength="250" id="inpBinhLuan" placeholder="Viết suy nghĩ của bạn vào đây..."></textarea>
                <input id="btnBinhLuan" type="button" onclick="checkGuiBinhLuan()" value="GỬI BÌNH LUẬN">
            </div>
            <!-- <h2>Bình luận</h2> -->
            <div class="container-comment">
                <div class="rating"></div>
                <div class="comment-content">
                </div>
            </div>
        </div>
    </div>
</section>
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
