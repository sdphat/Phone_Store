<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bills extends Model
{
    use HasFactory;

    protected $table = "hoadon";
    protected $fillable = [
        "MaND",
        "NgayLap",
        "NguoiNhan",
        "SDT",
        "DiaChi",
        "PhuongThucTT",
        "TongTien",
        "TrangThai"
    ];
}
