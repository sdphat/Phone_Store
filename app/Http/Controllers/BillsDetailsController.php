<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillsDetailsController extends Controller
{
    function store(Request $request)
    {
        $f = $request->get("function");
        try {
            call_user_func_array(["App\\Http\\Controllers\\BillsDetailsController", $f], [$request]);
        }catch (Exception $exception){
            echo "Not found";
        }
    }
    public function tableBillDetails(Request $request){
        if (isset($_SESSION['currentUser'])) {
            $mahd = $request->get("mahd");

            $sql="SELECT * FROM chitiethoadon WHERE MaHD=$mahd";
            $dscthd=DB::select($sql);

            for($i = 0; $i < sizeof($dscthd); $i++) {
                $dscthd[$i]["SP"] = Products::find( $dscthd[$i]['MaSP']);
            }

            echo '<table class="table table-striped" >
		<tr style="text-align:center;vertical-align:middle;font-size:20px;background-color:coral;color:black!important">
			<th scope="col" style="font-weight:600">Sản phẩm</th>
			<th scope="col" style="font-weight:600">Số lượng</th>
			<th scope="col" style="font-weight:600">Đơn giá</th>
		</tr>';

            forEach($dscthd as $row) {

                echo '<tr>
					<td scope="col" style="text-align:center;vertical-align:middle;">
						<a href="chitietsanpham.php?'.$row['MaSP'].'">
							<img style="width:100px;height:100px;" src="'.$row["SP"]["HinhAnh"].'"><br>
							'.$row["SP"]["TenSP"].'
						</a>
					</td>
					<td scope="col" style="text-align:center;vertical-align:middle;">'.$row["SoLuong"].'</td>
					<td scope="col" style="text-align:center;vertical-align:middle;">'.$row["DonGia"].'</td>
				</tr>'	;
            }
            echo   '</table>';
        }
    }
}
