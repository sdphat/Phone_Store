<div class="top-nav group">
    <section>
        <div class="social-top-nav">
            <a class="fa fa-facebook"></a>
            <a class="fa fa-twitter"></a>
            <a class="fa fa-google"></a>
            <a class="fa fa-youtube"></a>
        </div> <!-- End Social Topnav -->

        <ul class="top-nav-quicklink flexContain">
            <li><a href="{{url("")}}"><i class="fa fa-home"></i> Trang chủ</a></li>
            <li><a href=""><i class="fa fa-newspaper-o"></i> Tin tức</a></li>
            <li><a href=""><i class="fa fa-handshake-o"></i> Tuyển dụng</a></li>
            <li><a href=""><i class="fa fa-info-circle"></i> Giới thiệu</a></li>
            <li><a href=""><i class="fa fa-wrench"></i> Bảo hành</a></li>
            <li><a href=""><i class="fa fa-phone"></i> Liên hệ</a></li>
        </ul> <!-- End Quick link -->
    </section><!-- End Section -->
</div><!-- End Top Nav  -->
<section>
    <div class="header group">
        <div class="smallmenu" id="openmenu" onclick="smallmenu(1)">≡</div>
        <div style="display: none;" class="smallmenu" id="closemenu" onclick="smallmenu(0)">×</div>
        <div class="logo">
            <a href="index.php">
                <img src="{{asset("")}}assets/img/logo.jpg" alt="Trang chủ Smartphone Store" title="Trang chủ Smartphone Store">
            </a>
        </div> <!-- End Logo -->

        <div class="content">
            <div class="search-header">
                <form class="input-search" method="get" action="index.php">
                    <div class="autocomplete">
                        <input id="search-box" name="search" autocomplete="off" type="text" placeholder="Nhập từ khóa tìm kiếm...">
                        <button type="submit">
                            <i class="fa fa-search"></i>
                            Tìm kiếm
                        </button>
                    </div>
                </form> <!-- End Form search -->
                <div class="tags">
                    <strong>Từ khóa: </strong>
                </div>
            </div> <!-- End Search header -->

            <div class="tools-member">
                <div class="member">
                    <a onclick="checkTaiKhoan()" id="btnTaiKhoan">
                        <i class="fa fa-user"></i>
                        Tài khoản
                    </a>
                    <div class="menuMember hide">
                        <a href="nguoidung.php">Trang người dùng</a>
                        <a onclick="checkDangXuat();">Đăng xuất</a>
                    </div>
                </div> <!-- End Member -->

                <div class="cart">
                    <a href="giohang.php">
                        <i class="fa fa-shopping-cart"></i>
                        <span>Giỏ hàng</span>
                        <span class="cart-number"></span>
                    </a>
                </div> <!-- End Cart -->

                <!-- <div class="check-order">
                    <a>
                        <i class="fa fa-truck"></i>
                        <span>Đơn hàng</span>
                    </a>
                </div>  -->
            </div><!-- End Tools Member -->
        </div> <!-- End Content -->
    </div> <!-- End Header -->
</section>
