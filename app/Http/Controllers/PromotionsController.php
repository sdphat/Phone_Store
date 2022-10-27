<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PromotionsController extends Controller
{
    function store(Request $request)
    {
        $f = $request->get("function");
        try {
            call_user_func_array(["App\\Http\\Controllers\\PromotionsController", $f], [$request]);
        }catch (Exception $exception){
            echo "Not found";
        }
    }
    public function getAll(){
        $dskm = (new KhuyenMaiBUS())->select_all();
        die (json_encode($dskm));
    }
    public function getByID(){
        $km = (new KhuyenMaiBUS())->select_by_id('*', $_POST['id']);
        die (json_encode($km));
    }
}
