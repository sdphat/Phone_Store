<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Products extends Model
{
    use HasFactory;
    protected $table="sanpham";
    protected $primaryKey = "MaSP";
    function capNhapTrangThai($trangthai, $id) {
        Products::where("MaSP", $id)->update(["TrangThai" => $trangthai]);
    }

    function themDanhGia($id) {
        // cập nhật số lượt đánh giá
        $sanpham = Products::where('MaSP', $id)->get();
        $sanpham["SoDanhGia"] = $sanpham["SoDanhGia"] + 1;
        Products::where("SoDanhGia", $id)->update(["SoDanhGia" => $sanpham["SoDanhGia"]]);
        // cập nhật số sao trung bình
        $dsbl = DB::select("SELECT * FROM danhgia WHERE MaSP=$id");
        $tongSoSao = 0;
        for($i = 0; $i < sizeof($dsbl); $i++) {
            $tongSoSao += $dsbl[$i]["SoSao"];
        }
        Products::where("MaSP", $id)->update(["SoSao" =>$tongSoSao / sizeof($dsbl)]);
    }
}
