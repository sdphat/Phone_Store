<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Users;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillsDetailsController extends Controller
{
    public function store(Request $request)
    {
        $f = $request->get("function");
        try {
            call_user_func_array(["App\\Http\\Controllers\\BillsDetailsController", $f], [$request]);
        }catch (Exception $exception){
            echo "Not found";
        }
    }
    public function tableBillDetails(Request $request){
        $token = $request->header("X-CSRF-TOKEN");
        $user=Users::where("api_token", $token)->get();
        if (count($user)==1) {
            $billId = $request->get("mahd");
            $sql="SELECT * FROM chitiethoadon WHERE MaHD=$billId";
            $billDetails=DB::select($sql);
            for($i = 0; $i < sizeof($billDetails); $i++) {
                $billDetails[$i]->SP = Products::find( $billDetails[$i]->MaSP);
            }
            echo '<table class="table table-striped" >
		<tr style="text-align:center;vertical-align:middle;font-size:20px;background-color:coral;color:black!important">
			<th scope="col" style="font-weight:600">Sản phẩm</th>
			<th scope="col" style="font-weight:600">Số lượng</th>
			<th scope="col" style="font-weight:600">Đơn giá</th>
		</tr>';
            forEach($billDetails as $row) {
                echo '<tr>
					<td scope="col" style="text-align:center;vertical-align:middle;">
						<a href="product_details?'.$row->MaSP.'">
							<img style="width:100px;height:100px;" src="'.$row->SP->HinhAnh.'"><br>
							'.$row->SP->TenSP.'
						</a>
					</td>
					<td scope="col" style="text-align:center;vertical-align:middle;">'.$row->SoLuong.'</td>
					<td scope="col" style="text-align:center;vertical-align:middle;">'.$row->DonGia.'</td>
				</tr>'	;
            }
            echo   '</table>';
        }
    }
}
