<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, array|string|null $token)
 * @method static create(array $array)
 */
class Users extends Model
{
    use HasFactory;

    protected $table = "nguoidung";
    protected $primaryKey="MaND";
    protected $fillable=["Ho", "Ten", "SDT", "Email","DiaChi", "TaiKhoan","MatKhau","MaQuyen","TrangThai", "api_token"];

}
