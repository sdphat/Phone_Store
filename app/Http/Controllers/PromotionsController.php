<?php

namespace App\Http\Controllers;

use App\Models\Promotions;
use Illuminate\Http\Request;

class PromotionsController extends Controller
{
    public function store(Request $request)
    {
        $f = $request->get("function");
        try {
            call_user_func_array(["App\\Http\\Controllers\\PromotionsController", $f], [$request]);
        }catch (Exception $exception){
            echo "Not found";
        }
    }
    public function getAll(Request $request){
        echo json_encode(Promotions::all());
    }
    public function getById(Request $request)
    {
        $id = $request->get("id");
        $sp = Promotions::find($id);
        echo json_encode($sp);
    }
}
