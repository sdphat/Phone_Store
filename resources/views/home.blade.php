<!doctype html>
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
    <div class="companyMenu group flexContain" style="background: #ffffff;border-radius: 4px"></div>

    <div class="timNangCao" style="max-width: 1200px;width: 100%;display: flex;flex-wrap:wrap;justify-content: center;background: #ffffff;">
        <div class="flexContain" style="width:100%;border: 2px solid lightgray;border-radius: 4px;background: var(--primary-color);padding: 0;">
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

<x-footer/>
</body>
</html>
