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
    public function getAll(){
        $list = ProductTypes::all();
        die (json_encode($list));
    }
}
