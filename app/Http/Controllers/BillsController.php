<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BillsController extends Controller
{
    function store(Request $request)
    {
        $f = $request->get("function");
        try {
            call_user_func_array(["App\\Http\\Controllers\\BillsController", $f], [$request]);
        }catch (Exception $exception){
            echo "Not found";
        }
    }
    public function tableBills(){
        if (isset($_SESSION['currentUser'])) {
            $manguoidung = $_SESSION['currentUser']['MaND'];

            $sql="SELECT * FROM hoadon WHERE MaND=$manguoidung";
            $dsdh=(new DB_driver())->get_list($sql);

            if(sizeof($dsdh) > 0) {
                echo '<table class="table table-striped" >
				<tr style="text-align:center;vertical-align:middle;font-size:20px;background-color:coral;color:black!important">
				<th  style="font-weight:600">Mã đơn hàng</th>
				<th  style="font-weight:600">Mã người dùng</th>
				<th  style="font-weight:600">Ngày lập</th>
				<th  style="font-weight:600">Người nhận</th>
				<th  style="font-weight:600">SDT</th>
				<th  style="font-weight:600">Địa chỉ</th>
				<th  style="font-weight:600">Phương thức TT</th>
				<th  style="font-weight:600">Tổng tiền</th>
				<th  style="font-weight:600">Trạng thái</th>
				<th  style="font-weight:600">Xem chi tiết</th>
			</tr>';

                forEach($dsdh as $row) {
                    echo '<tr>
						<td  style="text-align:center;vertical-align:middle;">'.$row["MaHD"].'</td>
						<td  style="text-align:center;vertical-align:middle;">'.$row["MaND"].'</td>
						<td  style="text-align:center;vertical-align:middle;">'.$row["NgayLap"].'</td>
						<td  style="text-align:center;vertical-align:middle;">'.$row["NguoiNhan"].'</td>
						<td  style="text-align:center;vertical-align:middle;">'.$row["SDT"].'</td>
						<td  style="text-align:center;vertical-align:middle;">'.$row["DiaChi"].'</td>
						<td  style="text-align:center;vertical-align:middle;">'.$row["PhuongThucTT"].'</td>
						<td  style="text-align:center;vertical-align:middle;">'.$row["TongTien"].'</td>
						<td  style="text-align:center;vertical-align:middle;">'.$row["TrangThai"].'</td>
						<td  style="text-align:center;vertical-align:middle;">
							<button data-toggle="modal" data-target="#exampleModal" onclick="xemChiTiet(\''.$row["MaHD"].'\')">Xem</button>
						</td>
					</tr>'	;
                }
                echo '</table>';

            } else {
                echo '<h2 style="color:green; text-align:center;">
						Hiện chưa có đơn hàng nào,
						<a href="index.php" style="color:blue">Mua ngay</a>
					</h2>';
            }
        }
    }
    public function getAll(){
        $donhang = (new HoaDonBUS())->select_all();
        $ctdonhang = (new ChiTietHoaDonBUS())->select_all();
        die (json_encode($donhang));
    }
    public function add(){
        $dulieu = $_POST["dulieu"];

        $hoadonBUS = new HoaDonBUS();
        $chitiethdBUS = new ChiTietHoaDonBUS();

        $hoadonBUS->add_new(array(
            "MaHD" => "",
            "MaND" => $dulieu["maNguoiDung"],
            "NgayLap" => $dulieu["ngayLap"],
            "NguoiNhan" => $dulieu["tenNguoiNhan"],
            "SDT" => $dulieu["sdtNguoiNhan"],
            "DiaChi" => $dulieu["diaChiNguoiNhan"],
            "PhuongThucTT" => $dulieu["phuongThucTT"],
            "TongTien" => $dulieu["tongTien"],
            "TrangThai" => 1
        ));

        $hoadonMaxID = $hoadonBUS->get_list("SELECT * FROM hoadon ORDER BY MaHD DESC LIMIT 0, 1");
        $mahd = $hoadonMaxID[0]["MaHD"];

        forEach($dulieu["dssp"] as $sp) {
            $dataSp = (new SanPhamBUS())->select_by_id("*", $sp["masp"]);
            $donGia = $dataSp["DonGia"];

            $chitiethdBUS->add_new(array($mahd, $sp["masp"], $sp["soLuong"], $donGia));
        }

        die (json_encode(true));

    }
}
