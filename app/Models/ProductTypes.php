<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static whereIn(string $string, array $array)
 * @method static create(string[] $array)
 */
class ProductTypes extends Model
{
    use HasFactory;
    protected $table="loaisanpham";
    protected $primaryKey="MaLSP";
    protected $fillable=["MaLSP", "TenLSP", "HinhAnh", "Mota", "create_at"];
}
