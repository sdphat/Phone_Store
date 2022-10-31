<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voteds extends Model
{
    use HasFactory;

    protected $table = "danhgia";
    protected $fillable = [
        "MASP",
        "MaND",
        "SoSao",
        "BinhLuan",
        "NgayLap"
    ];
}
