<?php

namespace App\Http\Controllers;

use App\Models\ProductTypes;
use Illuminate\Http\Request;

class ProductTypesController extends Controller
{
    function store(Request $request)
    {
        $f = $request->get("function");
        try {
            call_user_func_array(["App\\Http\\Controllers\\ProductTypesController", $f], [$request]);
        }catch (Exception $exception){
            echo "Not found";
        }
    }
    public function getAll(Request $request){
        $list = ProductTypes::all();
        echo json_encode($list);
    }
    public function add(Request $request){
        ProductTypes::create([
            'TenLSP' => 'Maven',
            "HinhAnh"=>"Maven.png",
            "Mota"=>"All products of Maven",
        ]);;
    }
}
