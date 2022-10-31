<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillDetails extends Model
{
    use HasFactory;
    protected $table="chitiethoadon";
    protected $fillable=[
        "MaHD",
        "MaSP",
        "SoLuong",
        "DonGia"
    ];
}
