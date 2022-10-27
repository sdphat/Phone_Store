<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;

    protected $table = "nguoidung";

    function add_new($data)
    {
        // check
        // username trung, email trung

        // them
        parent::add_new($data);
    }

    public static function updateToken($maND,$token){
        Users::where("MaND",$maND)->update(["api_token" => $token]);
    }
}
