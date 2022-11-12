<?php

namespace App\Http\Controllers;

use App\Models\BillDetails;
use App\Models\Bills;
use App\Models\Products;
use App\Models\Users;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillsController extends Controller
{
    public function store(Request $request)
    {
        $f = $request->get("function");
        try {
            call_user_func_array(["App\\Http\\Controllers\\BillsController", $f], [$request]);
        } catch (Exception) {
            echo "Not found";
        }
    }

    public function getAll(Request $request)
    {
        echo json_encode(Bills::all());
    }

    public function add(Request $request)
    {
        $data = $request->get("dulieu");
        Bills::create([
            "MaND" => $data["maNguoiDung"],
            "NgayLap" => $data["ngayLap"],
            "NguoiNhan" => $data["tenNguoiNhan"],
            "SDT" => $data["sdtNguoiNhan"],
            "DiaChi" => $data["diaChiNguoiNhan"],
            "PhuongThucTT" => $data["phuongThucTT"],
            "TongTien" => $data["tongTien"],
            "TrangThai" => 1
        ]);
        $newBill = DB::select("SELECT * FROM hoadon ORDER BY MaHD DESC LIMIT 0, 1")[0];
        foreach ($data["dssp"] as $sp) {
            $price = Products::where("MaSP", $sp["masp"])->get()[0]["DonGia"];
            BillDetails::create([
                "MaHD" => $newBill->MaHD,
                "MaSP" => $sp["masp"],
                "SoLuong" => $sp["soLuong"],
                "DonGia" => $price
            ]);
        }
        echo json_encode(true);
    }

    public function tableBills(Request $request)
    {
        $token = $request->header("X-CSRF-TOKEN");
        $user = Users::where("api_token", $token)->get();
        if (count($user) == 1) {
            $userId = $user[0]->MaND;
            $sql = "SELECT * FROM hoadon WHERE MaND=$userId";
            $bills = DB::select($sql);
            if (sizeof($bills) > 0) {
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

                foreach ($bills as $bill) {
                    echo '<tr>
						<td  style="text-align:center;vertical-align:middle;">' . $bill->MaHD . '</td>
						<td  style="text-align:center;vertical-align:middle;">' . $bill->MaND . '</td>
						<td  style="text-align:center;vertical-align:middle;">' . $bill->NgayLap . '</td>
						<td  style="text-align:center;vertical-align:middle;">' . $bill->NguoiNhan . '</td>
						<td  style="text-align:center;vertical-align:middle;">' . $bill->SDT . '</td>
						<td  style="text-align:center;vertical-align:middle;">' . $bill->DiaChi . '</td>
						<td  style="text-align:center;vertical-align:middle;">' . $bill->PhuongThucTT . '</td>
						<td  style="text-align:center;vertical-align:middle;">' . $bill->TongTien . '</td>
						<td  style="text-align:center;vertical-align:middle;">' . $bill->TrangThai . '</td>
						<td  style="text-align:center;vertical-align:middle;">
							<button data-toggle="modal" data-target="#exampleModal" onclick="xemChiTiet(\'' . $bill->MaHD . '\')">Xem</button>
						</td>
					</tr>';
                }
                echo '</table>';

            } else {
                echo '<h2 style="color:green; text-align:center;">
						Hiện chưa có đơn hàng nào,
						<a href="'.url("").'/home" style="color:blue">Mua ngay</a>
					</h2>';
            }
        }
    }

}
