let TONGTIEN = 0;


window.onload = function () {

    document.getElementById("btnDangXuat").onclick = function () {
        checkDangXuat(() => {
            window.location.href = "admin";
        });
    };

    getCurrentUser((user) => {
        if (user !== null && user.MaQuyen !== 1) {
            addEventChangeTab();
            addThongKe();
            openTab('Home');
        } else {
            document.body.innerHTML = `<h1 style="color:red; with:100%; text-align:center; margin: 50px;"> Truy cập bị từ chối.. </h1>`;
        }
    }, () => {
        document.body.innerHTML = `<h1 style="color:red; with:100%; text-align:center; margin: 50px;"> Truy cập bị từ chối.. </h1>`;
    });
};

function refreshTableSanPham() {
    $.ajax({
        type: "POST",
        url: "api/products",
        dataType: "json",
        // timeout: 1500, // sau 1.5 giây mà không phản hồi thì dừng => hiện lỗi
        data: {
            function: "getall",
        },
        success: function (data) {
            list_products = data; // biến toàn cục lưu trữ mảng sản phẩm hiện có
            addTableProducts(data);
        },
        error: function (e) {
            Swal.fire({
                type: "error",
                title: "Lỗi lấy dữ liệu sản phẩm (admin.js > refreshTableSanPham)",
                html: e.responseText
            });
            console.log(e.responseText);
        }
    });
}

function addChart(id, chartOption) {
    let ctx = document.getElementById(id).getContext('2d');
    let chart = new Chart(ctx, chartOption);
}

function addThongKe() {
    let dataChart = {
        type: 'bar',
        data: {
            labels: ["Apple", "Samsung", "Xiaomi", "Vivo", "Oppo", "Mobiistar"],
            datasets: [{
                label: 'Số lượng bán ra',
                data: [12, 19, 10, 5, 20, 5],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            title: {
                fontColor: '#fff',
                fontSize: 25,
                display: true,
                text: 'Sản phẩm bán ra'
            }
        }
    };

    // Thêm thống kê
    let barChart = copyObject(dataChart);
    barChart.type = 'bar';
    addChart('myChart1', barChart);

    let doughnutChart = copyObject(dataChart);
    doughnutChart.type = 'doughnut';
    addChart('myChart2', doughnutChart);

    let pieChart = copyObject(dataChart);
    pieChart.type = 'pie';
    addChart('myChart3', pieChart);

    let lineChart = copyObject(dataChart);
    lineChart.type = 'line';
    addChart('myChart4', lineChart);
}

function ajaxLoaiSanPham() {
    $.ajax({
        type: "POST",
        url: "api/product_types",
        dataType: "json",
        // timeout: 1500, // sau 1.5 giây mà không phản hồi thì dừng => hiện lỗi
        data: {
            function: "getall"
        },
        success: function (data) {
            showLoaiSanPham(data);
        },
        error: function (e) {

        }
    });
}

function showLoaiSanPham(data) {
    let s = "";
    for (let i = 0; i < data.length; i++) {
        let p = data[i];
        s += `<option value="` + p.MaLSP + `">` + p.TenLSP + `</option>`;
    }
    document.getElementsByName("chonCompany")[0].innerHTML = s;
}

function ajaxKhuyenMai() {
    $.ajax({
        type: "POST",
        url: "api/promotions",
        dataType: "json",
        // timeout: 1500, // sau 1.5 giây mà không phản hồi thì dừng => hiện lỗi
        data: {
            function: "getall"
        },
        success: function (data) {
            showKhuyenMai(data);
            showGTKM(data);
        },
        error: function (e) {
        }
    });
}

function showKhuyenMai(data) {
    document.getElementsByName("chonKhuyenMai")[0].innerHTML = `
        <option selected="selected" value="` + data[0].MaKM + `">Không</option>
        <option value="` + data[1].MaKM + `">Trả góp</option>
        <option value="` + data[2].MaKM + `">Giảm giá</option>
        <option value="` + data[3].MaKM + `">Giá rẻ online</option>
        <option value="` + data[4].MaKM + `">Mởi ra mắt</option>`;

}

function showGTKM() {
    let giaTri = document.getElementsByName("chonKhuyenMai")[0].value;
    switch (giaTri) {
        // lấy tất cả khuyến mãi
        case '1':
            document.getElementById("giatrikm").value = 0;
            break;

        case '2':
            document.getElementById("giatrikm").value = 500000;
            break;

        case '3':
            document.getElementById("giatrikm").value = 650000;
            break;

        case '4':
            document.getElementById("giatrikm").value = 0;
            break;

        case '5':
            document.getElementById("giatrikm").value = 0;
            break;

        default:
            break;
    }
}

// ======================= Các Tab =========================
function addEventChangeTab() {
    let sidebar = document.getElementsByClassName('sidebar')[0];
    let list_a = sidebar.getElementsByTagName('a');
    for (let a of list_a) {
        if (!a.onclick) {
            a.addEventListener('click', function () {
                turnOff_Active();
                this.classList.add('active');
                let tab = this.childNodes[1].data.trim();
                openTab(tab);
            });
        }
    }
}

function turnOff_Active() {
    let sidebar = document.getElementsByClassName('sidebar')[0];
    let list_a = sidebar.getElementsByTagName('a');
    for (let a of list_a) {
        a.classList.remove('active');
    }
}

function openTab(nameTab) {
    // ẩn hết
    let main = document.getElementsByClassName('main')[0].children;
    for (let e of main) {
        e.style.display = 'none';
    }

    // mở tab
    switch (nameTab) {
        case 'Home':
            document.getElementsByClassName('home')[0].style.display = 'block';
            break;
        case 'Sản Phẩm':
            document.getElementsByClassName('sanpham')[0].style.display = 'block';
            break;
        case 'Đơn Hàng':
            document.getElementsByClassName('donhang')[0].style.display = 'block';
            break;
        case 'Khách Hàng':
            document.getElementsByClassName('khachhang')[0].style.display = 'block';
            break;
        case 'Thống Kê':
            document.getElementsByClassName('thongke')[0].style.display = 'block';
            break;
    }
}

// ========================== Sản Phẩm ========================
// Vẽ bảng danh sách sản phẩm
function addTableProducts(list_products) {
    let tc = document.getElementsByClassName('sanpham')[0].getElementsByClassName('table-content')[0];
    let s = `<table class="table-outline hideImg">`;

    for (let i = 0; i < list_products.length; i++) {
        let p = list_products[i];
        s += `<tr>
            <td style="width: 5%">` + (i + 1) + `</td>
            <td style="width: 10%">` + p.MaSP + `</td>
            <td style="width: 40%">
                <a title="Xem chi tiết" target="_blank" href="product_details?` + p.MaSP + `">` + p.TenSP + `</a>
                <img src="` + p.HinhAnh + `" alt=""/>
            </td>
            <td style="width: 15%">` + parseInt(p.DonGia).toLocaleString() + `</td>
            <td style="width: 10%">` + /*promoToStringValue(*/ (p.KM.TenKM) /*)*/ + `</td>
            <td style="width: 10%">` + (p.TrangThai === 1 ? "Hiện" : "Ẩn") + `</td>
            <td style="width: 10%">
                <div class="tooltip">
                    <i class="fa fa-wrench" onclick="addKhungSuaSanPham('` + p.MaSP + `')"></i>
                    <span class="tooltiptext">Sửa</span>
                </div>
                <div class="tooltip">
                    <i class="fa fa-trash" onclick="xoaSanPham('` + p.TrangThai + `', '` + p.MaSP + `', '` + p.TenSP + `')"></i>
                    <span class="tooltiptext">Xóa</span>
                </div>
            </td>
        </tr>`;
    }

    s += `</table>`;

    tc.innerHTML = s;
}

// Tìm kiếm
function timKiemSanPham(inp) {
    let kieuTim = document.getElementsByName('kieuTimSanPham')[0].value;
    let text = inp.value;

    // Lọc
    let vitriKieuTim = {
        'ma': 1,
        'ten': 2
    }; // mảng lưu vị trí cột

    let listTr_table = document.getElementsByClassName('sanpham')[0].getElementsByClassName('table-content')[0].getElementsByTagName('tr');
    for (let tr of listTr_table) {
        let td = tr.getElementsByTagName('td')[vitriKieuTim[kieuTim]].innerHTML.toLowerCase();

        if (td.indexOf(text.toLowerCase()) < 0) {
            tr.style.display = 'none';
        } else {
            tr.style.display = '';
        }
    }
}

// Thêm
function layThongTinSanPhamTuTable(id, inputFileId) {
    let khung = document.getElementById(id);
    let tr = khung.getElementsByTagName('tr');

    let masp = tr[1].getElementsByTagName('td')[1].getElementsByTagName('input')[0].value;
    let name = tr[2].getElementsByTagName('td')[1].getElementsByTagName('input')[0].value;
    let company = tr[3].getElementsByTagName('td')[1].getElementsByTagName('select')[0].value;
    let img = document.getElementById(inputFileId).value;
    let price = tr[5].getElementsByTagName('td')[1].getElementsByTagName('input')[0].value;
    let amount = tr[6].getElementsByTagName('td')[1].getElementsByTagName('input')[0].value;
    let star = tr[7].getElementsByTagName('td')[1].getElementsByTagName('input')[0].value;
    let rateCount = tr[8].getElementsByTagName('td')[1].getElementsByTagName('input')[0].value;
    let promoName = tr[9].getElementsByTagName('td')[1].getElementsByTagName('select')[0].value;
    let promoValue = tr[10].getElementsByTagName('td')[1].getElementsByTagName('input')[0].value;

    let screen = tr[12].getElementsByTagName('td')[1].getElementsByTagName('input')[0].value;
    let os = tr[13].getElementsByTagName('td')[1].getElementsByTagName('input')[0].value;
    let camara = tr[14].getElementsByTagName('td')[1].getElementsByTagName('input')[0].value;
    let camaraFront = tr[15].getElementsByTagName('td')[1].getElementsByTagName('input')[0].value;
    let cpu = tr[16].getElementsByTagName('td')[1].getElementsByTagName('input')[0].value;
    let ram = tr[17].getElementsByTagName('td')[1].getElementsByTagName('input')[0].value;
    let rom = tr[18].getElementsByTagName('td')[1].getElementsByTagName('input')[0].value;
    let microUSB = tr[19].getElementsByTagName('td')[1].getElementsByTagName('input')[0].value;
    let battery = tr[20].getElementsByTagName('td')[1].getElementsByTagName('input')[0].value;

    return {
        "name": name,
        "img": img,
        "price": price,
        "company": company,
        "amount": amount,
        "star": star,
        "rateCount": rateCount,
        "promo": {
            "name": promoName,
            "value": promoValue
        },
        "detail": {
            "screen": screen,
            "os": os,
            "camara": camara,
            "camaraFront": camaraFront,
            "cpu": cpu,
            "ram": ram,
            "rom": rom,
            "microUSB": microUSB,
            "battery": battery
        },
        "masp": masp,
        "TrangThai": 1
    };
}

function themSanPham() {
    let newSp = layThongTinSanPhamTuTable('khungThemSanPham', 'product-image-input');

    //kt tên sp
    let pattCheckTenSP = /([a-z A-Z0-9&():.'_-]{2,})$/;
    if (pattCheckTenSP.test(newSp.name) === false) {
        alert("Tên sản phẩm không hợp lệ");
        return false;
    }

    //kt hình
    /*let pattCheckHinh= /^([0-9]{1,})[.](png|jpeg|jpg)$/;
    if (pattCheckHinh.test(newSp.img) == false)
    {
        alert ("Ảnh không hợp lệ");
        return false;
    }*/

    //kt giá tiền
    let pattCheckGia = /^([0-9]){1,}(000)$/;
    if (pattCheckGia.test(newSp.price) === false) {
        alert("Đơn giá sản phẩm không hợp lệ");
        return false;
    }

    //kt số lượng
    let pattCheckSL = /[0-9]{1,}$/;
    if (pattCheckSL.test(newSp.amount) === false) {
        alert("Số lượng sản phẩm không hợp lệ");
        return false;
    }

    let formData = new FormData();

    formData.append('image', document.getElementById('product-image-input').files[0]);

    $.ajax({
        type: "POST",
        url: "api/upload-product-image",
        processData: false,
        contentType: false,
        data: formData,
        success: function (data, status, xhr) {
            if(data) {
                newSp.img = data;
            } else {
                newSp.img = newSp.HinhAnh;
            }
            $.ajax({
                type: "POST",
                url: "api/products",
                dataType: "json",
                // timeout: 1500, // sau 1.5 giây mà không phản hồi thì dừng => hiện lỗi
                data: {
                    function: "add",
                    dataAdd: newSp
                },
                success: function (data, status, xhr) {
                    Swal.fire({
                        type: 'success',
                        title: 'Thêm thành công'
                    });
                    resetForm();
                    document.getElementById('khungThemSanPham').style.transform = 'scale(0)';
                    refreshTableSanPham();
                },
                error: function (data, status, xhr) {
                    resetForm();
                    document.getElementById('khungThemSanPham').style.transform = 'scale(0)';
                    refreshTableSanPham();
                },
            });
        }
    });



    alert('Thêm sản phẩm "' + newSp.name + '" thành công.');
    refreshTableSanPham();

}
function resetForm() {
    let khung = document.getElementById('khungThemSanPham');
    let tr = khung.getElementsByTagName('tr');

    tr[2].getElementsByTagName('td')[1].getElementsByTagName('input')[0].value = "";
    tr[4].getElementsByTagName('td')[1].getElementsByTagName('img')[0].src = "";
    tr[5].getElementsByTagName('td')[1].getElementsByTagName('input')[0].value = "";
    tr[6].getElementsByTagName('td')[1].getElementsByTagName('input')[0].value = "0";

    tr[12].getElementsByTagName('td')[1].getElementsByTagName('input')[0].value = "";
    tr[13].getElementsByTagName('td')[1].getElementsByTagName('input')[0].value = "";
    tr[14].getElementsByTagName('td')[1].getElementsByTagName('input')[0].value = "";
    tr[15].getElementsByTagName('td')[1].getElementsByTagName('input')[0].value = "";
    tr[16].getElementsByTagName('td')[1].getElementsByTagName('input')[0].value = "";
    tr[17].getElementsByTagName('td')[1].getElementsByTagName('input')[0].value = "";
    tr[18].getElementsByTagName('td')[1].getElementsByTagName('input')[0].value = "";
    tr[19].getElementsByTagName('td')[1].getElementsByTagName('input')[0].value = "";
    tr[20].getElementsByTagName('td')[1].getElementsByTagName('input')[0].value = "";
}

function autoMaSanPham(company) {
    // hàm tự tạo mã cho sản phẩm mới
    let autoMaSP = list_products[list_products.length - 1].MaSP;
    document.getElementById('maspThem').value = parseInt(autoMaSP) + 1;
}


// Xóa
function xoaSanPham(trangthai, masp, tensp) {
    if (trangthai === 1) {
        // alert ("Sản phẩm còn đang bán");
        Swal.fire({
            type: 'warning',
            title: 'Bạn có muốn ẨN ' + tensp + ' không!',
            showCancelButton: true
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "api/products",
                    dataType: "json",
                    // timeout: 1500, // sau 1.5 giây mà không phản hồi thì dừng => hiện lỗi
                    data: {
                        function: "hide",
                        id: masp,
                        trangthai: 0
                    },
                    success: function (data, status, xhr) {
                        Swal.fire({
                            type: 'success',
                            title: 'Ẩn thành công'
                        });
                        refreshTableSanPham();
                    },
                    error: function (e) {
                        Swal.fire({
                            type: "error",
                            title: "Lỗi xóa",
                            html: e.responseText
                        });
                    }
                });
            }
        });
    }
    else {
        if (window.confirm('Bạn có chắc muốn xóa ' + tensp)) {
            // Xóa
            $.ajax({
                type: "POST",
                url: "api/products",
                dataType: "json",
                // timeout: 1500, // sau 1.5 giây mà không phản hồi thì dừng => hiện lỗi
                data: {
                    function: "delete",
                    maspdelete: masp
                },
                success: function (data, status, xhr) {
                    Swal.fire({
                        type: "success",
                        title: "Đã xóa thông tin sản phẩm"
                    });
                },
                error: function () {
                    Swal.fire({
                        type: "error",
                        title: "Không thể xóa"
                    });
                }
            });

            // Vẽ lại table
            refreshTableSanPham();
        }
    }
}

// Sửa
function suaSanPham(masp) {
    let newSp = layThongTinSanPhamTuTable('khungSuaSanPham', 'product-image-update-input');
    //kt tên sp
    let pattCheckTenSP = /([a-z A-Z0-9&():.'_-]{2,})$/;
    if (pattCheckTenSP.test(newSp.name) === false) {
        alert("Tên sản phẩm không hợp lệ");
        return false;
    }

    //kt hình
    /*let pattCheckHinh= /^([0-9]{1,})[.](png|jpeg|jpg)$/;
    if (pattCheckHinh.test(newSp.img) == false)
    {
        alert ("Ảnh không hợp lệ");
        return false;
    }*/

    //kt giá tiền
    let pattCheckGia = /^([0-9]){1,}(000)$/;
    if (pattCheckGia.test(newSp.price) === false) {
        alert("Đơn giá sản phẩm không hợp lệ");
        return false;
    }

    //kt số lượng
    let pattCheckSL = /[0-9]{1,}$/;
    if (pattCheckSL.test(newSp.amount) === false) {
        alert("Số lượng sản phẩm không hợp lệ");
        return false;
    }

    let formData = new FormData();

    formData.append('image', document.getElementById('product-image-update-input').files[0]);

    $.ajax({
        type: "POST",
        url: "api/upload-product-image",
        processData: false,
        contentType: false,
        data: formData,
        success: function (data, status, xhr) {
            console.log(data);
            if(data) {
                newSp.img = data;
            } else {
                newSp.img = list_products.find((product) => product.MaSP = masp).HinhAnh;
            }
            console.log('hinh anh', newSp);
            $.ajax({
                type: "POST",
                url: "api/products",
                dataType: "json",
                // timeout: 1500, // sau 1.5 giây mà không phản hồi thì dừng => hiện lỗi
                data: {
                    function: "update",
                    dataUpdate: newSp
                },
                success: function (data, status, xhr) {
                    Swal.fire({
                        type: 'success',
                        title: 'Sửa thành công'
                    });
                    resetForm();
                    document.getElementById('khungSuaSanPham').style.transform = 'scale(0)';
                    refreshTableSanPham();
                },
                error: function (data, status, xhr) {
                    resetForm();
                    document.getElementById('khungSuaSanPham').style.transform = 'scale(0)';
                    refreshTableSanPham();
                },
            });
        }
    });



    alert('Sửa sản phẩm "' + newSp.name + '" thành công.');
    refreshTableSanPham();
}

function addKhungSuaSanPham(masp) {
    let sp;
    for (let p of list_products) {
        if (p.MaSP == masp) {
            sp = p;
        }
    }

    console.log(sp);

    let s = `<span class="close" onclick="this.parentElement.style.transform = 'scale(0)';">&times;</span>
    <form method="post" action="" enctype="multipart/form-data" onsubmit="return false;">
        <table class="overlayTable table-outline table-content table-header">
            <tr>
                <th colspan="2">` + sp.TenSP + `</th>
            </tr>
            <tr>
                <td>Mã sản phẩm:</td>
                <td><input disabled="disabled" type="text" id="maspSua" name="maspSua" value="` + sp.MaSP + `"></td>
            </tr>
            <tr>
                <td>Tên sản phẩm:</td>
                <td><input type="text" value="` + sp.TenSP + `"></td>
            </tr>
            <tr>
                <td>Hãng:</td>
                <td>
                    <select name="chonCompany" onchange="autoMaSanPham(this.value)">`;

    let company = ["Apple", "Coolpad", "HTC", "Itel", "Mobell", "Vivo", "Oppo", "SamSung", "Phillips", "Nokia", "Motorola", "Motorola", "Xiaomi"];
    var i = 1;
    for (let c of company) {
        let masp = i++;
        if (sp.MaLSP == masp)
            s += (`<option value="` + sp.MaLSP + `" selected="selected">` + c + `</option>`);
        else s += (`<option value="` + masp + `">` + c + `</option>`);
    }
    s += `</select>
                </td>
            </tr>
            <?php
                            $tenfilemoi= "";
                                if (isset($_POST["submit"]))
                                {
                                    if (($_FILES["hinhanh"]["type"]=="image/jpeg") ||($_FILES["hinhanh"]["type"]=="image/png") || ($_FILES["hinhanh"]["type"]=="image/jpg") && ($_FILES["hinhanh"]["size"] < 50000) )
                                    {
                                        if ($_FILES["file"]["error"] > 0 || file_exists("img/products/" . basename($_FILES["hinhanh"]["name"])))
                                        {
                                            echo ("Error Code: " . $_FILES["file"]["error"] . "<br />Chỉnh sửa ảnh lại sau)");
                                        }
                                        else
                                        {
                                            /*$tmp = explode(".", $_FILES["hinhanh"]["name"]);
                                            $duoifile = end($tmp);
                                            $masp = $_POST['maspThem'];
                                            $tenfilemoi = $masp . "." . $duoifile;*/
                                            $file = $_FILES["hinhanh"]["name"];
                                            $tenfilemoi = "img/products/" .$_FILES["hinhanh"]["name"];
                                            move_uploaded_file( $_FILES["hinhanh"]["tmp_name"], $tenfilemoi);
                                        }
                                    }
                                }
                        // require_once ("php/uploadfile.php");
                        ?>
            <tr>
                            <td>Hình:</td>
                            <td>
                                <img class="hinhDaiDien" id="anhDaiDienSanPhamThem" src=${sp.HinhAnh}>
                                <input id="product-image-update-input" type="file" name="hinhanh" onchange="capNhatAnhSanPham(this.files, 'anhDaiDienSanPhamThem', '<?php echo $tenfilemoi; ?>')">
                                <input style="display: none;" type="text" id="hinhanh" value="">
                            </td>
                        </tr>
            <tr>
                <td>Giá tiền:</td>
                <td><input type="text" value="` + sp.DonGia + `"></td>
            </tr>
            <tr>
                <td>Số lượng:</td>
                <td><input type="text" value="` + sp.SoLuong + `"></td>
            </tr>
            <tr>
                <td>Số sao:</td>
                <td><input type="text" value="` + sp.SoSao + `"></td>
            </tr>
            <tr>
                <td>Đánh giá:</td>
                <td><input type="text" value="` + sp.SoDanhGia + `"></td>
            </tr>
            <tr>
                <td>Khuyến mãi:</td>
                <td>
                    <select name="chonKhuyenMai" onchange="showGTKM()">`;
    var i = 1;
    s += (`<option selected="selected" value="` + i++ + `">Không</option>`);
    s += (`<option value="` + i++ + `">Giảm giá</option>`);
    s += (`<option value="` + i++ + `">Giá rẻ online</option>`);
    s += (`<option value="` + i++ + `">Trả góp</option>`);
    s += (`<option value="` + i++ + `">Mới ra mắt</option>`);
    s += `</script>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Giá trị khuyến mãi:</td>
                <td><input id="giatrikm" type="text" value="0"></td>
            </tr>
            <tr>
                <th colspan="2">Thông số kĩ thuật</th>
            </tr>
            <tr>
                <td>Màn hình:</td>
                <td><input type="text" value="` + sp.ManHinh + `"></td>
            </tr>
            <tr>
                <td>Hệ điều hành:</td>
                <td><input type="text" value="` + sp.HDH + `"></td>
            </tr>
            <tr>
                <td>Camara sau:</td>
                <td><input type="text" value="` + sp.CamSau + `"></td>
            </tr>
            <tr>
                <td>Camara trước:</td>
                <td><input type="text" value="` + sp.CamTruoc + `"></td>
            </tr>
            <tr>
                <td>CPU:</td>
                <td><input type="text" value="` + sp.CPU + `"></td>
            </tr>
            <tr>
                <td>RAM:</td>
                <td><input type="text" value="` + sp.Ram + `"></td>
            </tr>
            <tr>
                <td>Bộ nhớ trong:</td>
                <td><input type="text" value="` + sp.Rom + `"></td>
            </tr>
            <tr>
                <td>Thẻ nhớ:</td>
                <td><input type="text" value="` + sp.SDCard + `"></td>
            </tr>
            <tr>
                <td>Dung lượng Pin:</td>
                <td><input type="text" value="` + sp.Pin + `"></td>
            </tr>
            <tr>
                <td colspan="2"  class="table-footer"> <button name="submit" onclick="suaSanPham(${sp.MaSP})">SỬA</button> </td>
            </tr>
        </table>`;

    let khung = document.getElementById('khungSuaSanPham');
    khung.innerHTML = s;
    khung.style.transform = 'scale(1)';
}

// Cập nhật ảnh sản phẩm
function capNhatAnhSanPham(files, id, anh) {
    let url = '';
    if (files.length) url = window.URL.createObjectURL(files[0]);

    document.getElementById(id).src = url;
    document.getElementById('hinhanh').value = anh;
}

// Sắp Xếp sản phẩm
function sortProductsTable(loai) {
    let list = document.getElementsByClassName('sanpham')[0].getElementsByClassName("table-content")[0];
    let tr = list.getElementsByTagName('tr');

    quickSort(tr, 0, tr.length - 1, loai, getValueOfTypeInTable_SanPham); // type cho phép lựa chọn sort theo mã hoặc tên hoặc giá ...
    decrease = !decrease;
}

// Lấy giá trị của loại(cột) dữ liệu nào đó trong bảng
function getValueOfTypeInTable_SanPham(tr, loai) {
    let td = tr.getElementsByTagName('td');
    switch (loai) {
        case 'stt':
            return Number(td[0].innerHTML);
        case 'masp':
            return Number(td[1].innerHTML);
        case 'ten':
            return td[2].innerHTML.toLowerCase();
        case 'gia':
            return stringToNum(td[3].innerHTML);
        case 'khuyenmai':
            return td[4].innerHTML.toLowerCase();
    }
    return false;
}

// ========================= Đơn Hàng ===========================
// Vẽ bảng

function refreshTableDonHang() {
    $.ajax({
        type: "POST",
        url: "api/bills",
        dataType: "json",
        // timeout: 1500, // sau 1.5 giây mà không phản hồi thì dừng => hiện lỗi
        data: {
            function: "getAll",
        },
        success: function (data, status, xhr) {
            addTableDonHang(data);
            products = data;
            console.log(data);
        },
        error: function (e) {
            Swal.fire({
                type: "error",
                title: "Lỗi lấy dữ liệu khách Hàng (admin.js > refreshTableKhachHang)",
                html: e.responseText
            });
        }
    });
}
function addTableDonHang(data) {
    let tc = document.getElementsByClassName('donhang')[0].getElementsByClassName('table-content')[0];
    let s = `<table class="table-outline hideImg">`;

    TONGTIEN = 0;
    for (let i = 0; i < data.length; i++) {
        let d = data[i];
        s += `<tr>
            <td style="width: 5%">` + (i + 1) + `</td>
            <td style="width: 13%">` + d.MaHD + `</td>
            <td style="width: 7%">` + d.MaND + `</td>
            <td style="width: 15%">` + parseInt(d.TongTien).toLocaleString() + `</td>
            <td style="width: 10%">` + d.NgayLap + `</td>
            <td style="width: 10%">` + d.TrangThai + `</td>
            <td style="width: 10%">
                <div class="tooltip">
                    <i class="fa fa-check" onclick="duyet('` + d.MaHD + `', true)"></i>
                    <span class="tooltiptext">Duyệt</span>
                </div>
                <div class="tooltip">
                    <i class="fa fa-remove" onclick="duyet('` + d.MaHD + `', false)"></i>
                    <span class="tooltiptext">Hủy</span>
                </div>

            </td>
        </tr>`;
        TONGTIEN += stringToNum(d.tongtien + "");
    }

    s += `</table>`;
    tc.innerHTML = s;
}

function getListDonHang() {
    let u = getListUser();
    let result = [];
    for (let i = 0; i < u.length; i++) {
        for (let j = 0; j < u[i].donhang.length; j++) {
            // Tổng tiền
            let tongtien = 0;
            for (let s of u[i].donhang[j].sp) {
                let timsp = timKiemTheoMa(list_products, s.ma);
                if (timsp.MaKM.name == 'giareonline') tongtien += stringToNum(timsp.MaKM.value);
                else tongtien += stringToNum(timsp.DonGia);
            }

            // Ngày giờ
            let x = new Date(u[i].donhang[j].ngaymua).toLocaleString();

            // Các sản phẩm
            let sps = '';
            for (let s of u[i].donhang[j].sp) {
                sps += `<p style="text-align: right">` + (timKiemTheoMa(list_products, s.ma).name + ' [' + s.soluong + ']') + `</p>`;
            }

            // Lưu vào result
            result.push({
                "ma": u[i].donhang[j].ngaymua.toString(),
                "khach": u[i].username,
                "sp": sps,
                "tongtien": numToString(tongtien),
                "ngaygio": x,
                "tinhTrang": u[i].donhang[j].tinhTrang
            });
        }
    }
    return result;
}

// Duyệt
function duyet(maDonHang, duyetDon) {
    let u = getListUser();
    for (let i = 0; i < u.length; i++) {
        for (let j = 0; j < u[i].donhang.length; j++) {
            if (u[i].donhang[j].ngaymua == maDonHang) {
                if (duyetDon) {
                    if (u[i].donhang[j].tinhTrang == 'Đang chờ xử lý') {
                        u[i].donhang[j].tinhTrang = 'Đã giao hàng';

                    } else if (u[i].donhang[j].tinhTrang == 'Đã hủy') {
                        alert('Không thể duyệt đơn đã hủy !');
                        return;
                    }
                } else {
                    if (u[i].donhang[j].tinhTrang == 'Đang chờ xử lý') {
                        if (window.confirm('Bạn có chắc muốn hủy đơn hàng này. Hành động này sẽ không thể khôi phục lại !'))
                            u[i].donhang[j].tinhTrang = 'Đã hủy';

                    } else if (u[i].donhang[j].tinhTrang == 'Đã giao hàng') {
                        alert('Không thể hủy đơn hàng đã giao !');
                        return;
                    }
                }
                break;
            }
        }
    }

    // lưu lại
    setListUser(u);

    // vẽ lại
    addTableDonHang();
}

function locDonHangTheoKhoangNgay() {
    let from = document.getElementById('fromDate').valueAsDate;
    let to = document.getElementById('toDate').valueAsDate;

    let listTr_table = document.getElementsByClassName('donhang')[0].getElementsByClassName('table-content')[0].getElementsByTagName('tr');
    for (let tr of listTr_table) {
        let td = tr.getElementsByTagName('td')[5].innerHTML;
        let d = new Date(td);

        if (d >= from && d <= to) {
            tr.style.display = '';
        } else {
            tr.style.display = 'none';
        }
    }
}

function timKiemDonHang(inp) {
    let kieuTim = document.getElementsByName('kieuTimDonHang')[0].value;
    let text = inp.value;

    // Lọc
    let vitriKieuTim = {
        'ma': 1,
        'khachhang': 2,
        'trangThai': 6
    };

    let listTr_table = document.getElementsByClassName('donhang')[0].getElementsByClassName('table-content')[0].getElementsByTagName('tr');
    for (let tr of listTr_table) {
        let td = tr.getElementsByTagName('td')[vitriKieuTim[kieuTim]].innerHTML.toLowerCase();

        if (td.indexOf(text.toLowerCase()) < 0) {
            tr.style.display = 'none';
        } else {
            tr.style.display = '';
        }
    }
}

// Sắp xếp
function sortDonHangTable(loai) {
    let list = document.getElementsByClassName('donhang')[0].getElementsByClassName("table-content")[0];
    let tr = list.getElementsByTagName('tr');

    quickSort(tr, 0, tr.length - 1, loai, getValueOfTypeInTable_DonHang);
    decrease = !decrease;
}

// Lấy giá trị của loại(cột) dữ liệu nào đó trong bảng
function getValueOfTypeInTable_DonHang(tr, loai) {
    let td = tr.getElementsByTagName('td');
    switch (loai) {
        case 'stt':
            return Number(td[0].innerHTML);
        case 'ma':
            return new Date(td[1].innerHTML); // chuyển về dạng ngày để so sánh ngày
        case 'khach':
            return td[2].innerHTML.toLowerCase(); // lấy tên khách
        case 'sanpham':
            return td[3].children.length; // lấy số lượng hàng trong đơn này, length ở đây là số lượng <p>
        case 'tongtien':
            return stringToNum(td[4].innerHTML); // trả về dạng giá tiền
        case 'ngaygio':
            return new Date(td[5].innerHTML); // chuyển về ngày
        case 'trangthai':
            return td[6].innerHTML.toLowerCase(); //
    }
    return false;
}

// ====================== Khách Hàng =============================
// Vẽ bảng
function refreshTableKhachHang() {
    $.ajax({
        type: "POST",
        url: "api/users",
        dataType: "json",
        // timeout: 1500, // sau 1.5 giây mà không phản hồi thì dừng => hiện lỗi
        data: {
            function: "getall",
        },
        success: function (data, status, xhr) {
            addTableKhachHang(data);
            //console.log(data);
        },
        error: function (e) {
            console.log("Lỗi xử lý dữ liệu");
        }
    });
}

function thayDoiTrangThaiND(inp, mand) {
    let trangthai = (inp.checked ? 1 : 0);
    $.ajax({
        type: "POST",
        url: "api/users",
        dataType: "json",
        // timeout: 1500, // sau 1.5 giây mà không phản hồi thì dừng => hiện lỗi
        data: {
            function: "changeStatus",
            id: mand,
            status: trangthai
        },
        success: function (data, status, xhr) {
            //list_products = data; // biến toàn cục lưu trữ mảng sản phẩm hiện có
            // refreshTableKhachHang();
            //console.log(data);
        },
        error: function (e) {
            // Swal.fire({
            //     type: "error",
            //     title: "Lỗi lấy dữ liệu khách Hàng (admin.js > refreshTableKhachHang)",
            //     html: e.responseText
            // });
            console.log(e.responseText);
        }
    });
}


function addTableKhachHang(data) {
    let tc = document.getElementsByClassName('khachhang')[0].getElementsByClassName('table-content')[0];
    let s = `<table class="table-outline hideImg">`;


    for (let i = 0; i < data.length; i++) {
        let u = data[i];
        s += `<tr>
            <td >` + (i + 1) + `</td>
            <td >` + u.Ho + ' ' + u.Ten + `</td>
            <td >` + u.Email + `</td>
            <td >` + u.TaiKhoan + `</td>
            <td >
                <div class="tooltip">
                    <label class="switch">
                        <input type="checkbox" `+ (u.TrangThai == 1 ? "checked" : "") + ` onclick="thayDoiTrangThaiND(this, '` + u.MaND + `')">
                        <span class="slider round"></span>
                    </label>
                    <span class="tooltiptext">` + (u.TrangThai ? 'Mở' : 'Khóa') + `</span>
                </div>
                <div class="tooltip">
                    <i class="fa fa-remove" onclick="xoaNguoiDung('` + u.MaND + `')"></i>
                    <span class="tooltiptext">Xóa</span>
                </div>
            </td>
        </tr>`;
    }

    s += `</table>`;
    tc.innerHTML = s;
}

// Tìm kiếm
function timKiemNguoiDung(inp) {
    let kieuTim = document.getElementsByName('kieuTimKhachHang')[0].value;
    let text = inp.value;

    // Lọc
    let vitriKieuTim = {
        'ten': 1,
        'email': 2,
        'taikhoan': 3
    };

    let listTr_table = document.getElementsByClassName('khachhang')[0].getElementsByClassName('table-content')[0].getElementsByTagName('tr');
    for (let tr of listTr_table) {
        let td = tr.getElementsByTagName('td')[vitriKieuTim[kieuTim]].innerHTML.toLowerCase();

        if (td.indexOf(text.toLowerCase()) < 0) {
            tr.style.display = 'none';
        } else {
            tr.style.display = '';
        }
    }
}


// vô hiệu hóa người dùng (tạm dừng, không cho đăng nhập vào)
function voHieuHoaNguoiDung(TrangThai) {
    if (TrangThai == 1) {

    }
    let span = inp.parentElement.nextElementSibling;
    span.innerHTML = (inp.checked ? 'Khóa' : 'Mở');
}

// Xóa người dùng
function xoaNguoiDung(mand) {
    Swal.fire({
        title: "Bạn có chắc muốn xóa?",
        type: "question",
        showCancelButton: true,
        cancelButtonText: "Hủy"
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "api/users",
                dataType: "json",
                // timeout: 1500, // sau 1.5 giây mà không phản hồi thì dừng => hiện lỗi
                data: {
                    function: "delete",
                    id: mand
                },
                success: function (data, status, xhr) {
                    refreshTableKhachHang();
                    //console.log(data);
                },
                error: function (e) {
                    Swal.fire({
                        type: "error",
                        title: "Không thể xóa",
                    });
                }
            });
        }
    });
}

// Sắp xếp
function sortKhachHangTable(loai) {
    let list = document.getElementsByClassName('khachhang')[0].getElementsByClassName("table-content")[0];
    let tr = list.getElementsByTagName('tr');

    quickSort(tr, 0, tr.length - 1, loai, getValueOfTypeInTable_KhachHang);
    decrease = !decrease;
}

function getValueOfTypeInTable_KhachHang(tr, loai) {
    let td = tr.getElementsByTagName('td');
    switch (loai) {
        case 'stt':
            return Number(td[0].innerHTML);
        case 'hoten':
            return td[1].innerHTML.toLowerCase();
        case 'email':
            return td[2].innerHTML.toLowerCase();
        case 'taikhoan':
            return td[3].innerHTML.toLowerCase();
        case 'matkhau':
            return td[4].innerHTML.toLowerCase();
    }
    return false;
}

// ================== Sort ====================
// https://github.com/HoangTran0410/First_html_css_js/blob/master/sketch.js
let decrease = true; // Sắp xếp giảm dần

// loại là tên cột, func là hàm giúp lấy giá trị từ cột loai
function quickSort(arr, left, right, loai, func) {
    let pivot,
        partitionIndex;

    if (left < right) {
        pivot = right;
        partitionIndex = partition(arr, pivot, left, right, loai, func);

        //sort left and right
        quickSort(arr, left, partitionIndex - 1, loai, func);
        quickSort(arr, partitionIndex + 1, right, loai, func);
    }
    return arr;
}

function partition(arr, pivot, left, right, loai, func) {
    let pivotValue = func(arr[pivot], loai),
        partitionIndex = left;

    for (let i = left; i < right; i++) {
        if (decrease && func(arr[i], loai) > pivotValue ||
            !decrease && func(arr[i], loai) < pivotValue) {
            swap(arr, i, partitionIndex);
            partitionIndex++;
        }
    }
    swap(arr, right, partitionIndex);
    return partitionIndex;
}

function swap(arr, i, j) {
    let tempi = arr[i].cloneNode(true);
    let tempj = arr[j].cloneNode(true);
    arr[i].parentNode.replaceChild(tempj, arr[i]);
    arr[j].parentNode.replaceChild(tempi, arr[j]);
}

// ================= các hàm thêm ====================
// Chuyển khuyến mãi vễ dạng chuỗi tiếng việt
function promoToStringValue(pr) {
    switch (pr.name) {
        case 'tragop':
            return 'Góp ' + pr.value + '%';
        case 'giamgia':
            return 'Giảm ' + pr.value;
        case 'giareonline':
            return 'Online (' + pr.value + ')';
        case 'moiramat':
            return 'Mới';
    }
    return '';
}

function progress(percent, bg, width, height) {

    return `<div class="progress" style="width: ` + width + `; height:` + height + `">
                <div class="progress-bar bg-info" style="width: ` + percent + `%; background-color:` + bg + `"></div>
            </div>`;
}

// for(let i = 0; i < list_products.length; i++) {
//     list_products[i].masp = list_products[i].company.substring(0, 3) + vitriCompany(list_products[i], i);
// }

// console.log(JSON.stringify(list_products));
