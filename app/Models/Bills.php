<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bills extends Model
{
    use HasFactory;
    protected $table="hoadon";
    function getHoaDonCuaNguoiDung($mand) {
        $sql = "SELECT * FROM hoadon WHERE MaND=$mand";
        $dsdh = (new HoaDonBUS())->get_list($sql);
    }

}
