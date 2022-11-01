<!doctype html>
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

    <!-- Slider -->
    <link rel="stylesheet" href="{{asset("")}}assets/lib/ion.rangeSlider-2.2.0/css/ion.rangeSlider.css">
    <link rel="stylesheet" href="{{asset("")}}assets/lib/ion.rangeSlider-2.2.0/css/ion.rangeSlider.skinHTML5.css">
    <script src="{{asset("")}}assets/lib/ion.rangeSlider-2.2.0/js/ion-rangeSlider/ion.rangeSlider.min.js"></script>

    <!-- tidio - live chat -->
    <!-- <script src="//code.tidio.co/bfiiplaaohclhqwes5xivoizqkq56guu.js"></script> -->

    <!-- our files -->
    <!-- css -->
    <link rel="stylesheet" href="{{asset("")}}assets/css/style.css">
    <link rel="stylesheet" href="{{asset("")}}assets/css/topnav.css">
    <link rel="stylesheet" href="{{asset("")}}assets/css/header.css">
    <link rel="stylesheet" href="{{asset("")}}assets/css/banner.css">
    <link rel="stylesheet" href="{{asset("")}}assets/css/taikhoan.css">
    <link rel="stylesheet" href="{{asset("")}}assets/css/trangchu.css">
    <link rel="stylesheet" href="{{asset("")}}assets/css/home_products.css">
    <link rel="stylesheet" href="{{asset("")}}assets/css/pagination_phantrang.css">
    <link rel="stylesheet" href="{{asset("")}}assets/css/footer.css">
    <!-- js -->
    <script src="{{asset("")}}assets/js/dungchung.js"></script>
    <script src="{{asset("")}}assets/js/trangchu.js"></script>
    <script src="{{asset("")}}assets/data/products.js"></script>
</head>
<body>
<x-header/>
<section>
    <div class="banner">
        <div class="owl-carousel owl-theme"></div>
    </div> <!-- End Banner -->
    <div class="smallbanner" style="width: 100%;"></div>
    <div class="companysFilter">
        <button class="companysButton" onclick="setCompanysMenu()">
            <p>Hãng</p>
            <div id="iconOpenMenu">▷</div>
            <div id="iconCloseMenu" style="display: none;">▽</div>
        </button>
    </div>
    <div class="companyMenu group flexContain"></div>

    <div class="timNangCao" style="max-width: 1200px;width: 100%;display: flex;flex-wrap:wrap;justify-content: center">
        <div class="flexContain" style="width:100%">
            <div class="pricesRangeFilter dropdown">
                <button class="dropbtn">Giá tiền</button>
                <div class="dropdown-content"></div>
            </div>

            <div class="promosFilter dropdown">
                <button class="dropbtn">Khuyến mãi</button>
                <div class="dropdown-content"></div>
            </div>

            <div class="starFilter dropdown">
                <button class="dropbtn">Số lượng sao</button>
                <div class="dropdown-content"></div>
            </div>

            <div class="sortFilter dropdown">
                <button class="dropbtn">Sắp xếp</button>
                <div class="dropdown-content"></div>
            </div>
        </div>
        <div style="width: 1000px;">
            <input type="text" class="js-range-slider" id="demoSlider">
        </div>

    </div> <!-- End khung chọn bộ lọc -->

    <div class="choosedFilter flexContain"></div> <!-- Những bộ lọc đã chọn -->
    <hr>

    <!-- Mặc định mới vào trang sẽ ẩn đi, nế có filter thì mới hiện lên -->
    <div class="contain-products" style="display:none">
        <div class="filterName">
            <div id="divSoLuongSanPham"></div>
            <input type="text" placeholder="Lọc trong trang theo tên..." onkeyup="filterProductsName(this)">
            <div class="loader" style="display: none"></div>
        </div> <!-- End FilterName -->

        <ul id="products" class="homeproduct group flexContain">
            <div id="khongCoSanPham">
                <i class="fa fa-times-circle"></i>
                Không có sản phẩm nào
            </div> <!-- End Khong co san pham -->
        </ul><!-- End products -->

        <div class="pagination"></div>
    </div>

    <!-- Div hiển thị khung sp hot, khuyến mãi, mới ra mắt ... -->
    <div class="contain-khungSanPham"></div>
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
                        <input name="username" type="text" id="username" required autocomplete="off"/>
                    </div> <!-- /user name -->
                    <div class="field-wrap">
                        <label>
                            Mật khẩu<span class="req">*</span>
                        </label>
                        <input name="pass" type="password" id="pass" required autocomplete="off"/>
                    </div>
                    <div class="field-wrap">
                        {!! NoCaptcha::display() !!}
                    </div>
                    {{--                    <p class="forgot"><a href="#">Quên mật khẩu?</a></p>--}}
                    <button type="submit" class="button button-block"/>
                    Tiếp tục</button>
                </form> <!-- /form -->
            </div> <!-- /log in -->
            <div id="signup">
                <h1>Đăng kí miễn phí</h1>
                <!-- <form onsubmit="return signUp(this);"> -->
                <form action="" method="post" name="formDangKy" onsubmit="return checkDangKy();" >
                    <div class="top-row">
                        <div class="field-wrap">
                            <label>
                                Họ<span class="req">*</span>
                            </label>
                            <input name="ho" type="text" id="ho" required autocomplete="off"/>
                        </div>
                        <div class="field-wrap">
                            <label>
                                Tên<span class="req">*</span>
                            </label>
                            <input name="ten" id="ten" type="text" required autocomplete="off"/>
                        </div>
                    </div> <!-- / ho ten -->
                    <div class="top-row">
                        <div class="field-wrap">
                            <label>
                                Điện thoại<span class="req">*</span>
                            </label>
                            <input name="sdt" id="sdt" type="text" pattern="\d*" minlength="10" maxlength="12" required
                                   autocomplete="off"/>
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
                    </div>
                    <div class="field-wrap">
                        <label>
                            Tên đăng nhập<span class="req">*</span>
                        </label>
                        <input name="newUser" id="newUser" type="text" required autocomplete="off"/>
                    </div> <!-- /user name -->
                    <div class="field-wrap">
                        <label>
                            Mật khẩu<span class="req">*</span>
                        </label>
                        <input name="newPass" id="newPass" type="password" required autocomplete="off"/>
                    </div>
                    <button type="submit" class="button button-block"/>
                    Tạo tài khoản</button>
                </form> <!-- /form -->
            </div> <!-- /sign up -->
        </div><!-- tab-content -->
    </div> <!-- /taikhoan -->
</div>
<script>
    checkTaiKhoan();
</script>
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
