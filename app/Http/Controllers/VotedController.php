<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VotedController extends Controller
{
    function store(Request $request)
    {
        $f = $request->get("function");
        try {
            call_user_func_array(["App\\Http\\Controllers\\VotedController", $f], [$request]);
        }catch (Exception $exception){
            echo "Not found";
        }
    }
    public function add(){
        $masp = $_POST['masp'];
        $mand = $_POST['mand'];
        $sosao = $_POST['sosao'];
        $binhluan = $_POST['binhluan'];
        $thoigian = $_POST['thoigian'];

        $status = (new DB_driver())->insert("danhgia", array(
            "MASP" => $masp,
            "MaND" => $mand,
            "SoSao" => $sosao,
            "BinhLuan" => $binhluan,
            "NgayLap" => $thoigian
        ));

        $spBUS = new SanPhamBUS();

        echo json_encode($spBUS->themDanhGia($masp));
    }
    public function get(){
        $masp = $_POST['masp'];
        $dsbl = (new DB_driver())->get_list("SELECT * FROM danhgia WHERE MaSP=$masp");

        for($i = 0; $i < sizeof($dsbl); $i++) {
            $dsbl[$i]["ND"] = (new NguoiDungBUS())->select_by_id('*', $dsbl[$i]['MaND']);
        }

        echo json_encode($dsbl);
    }
}
