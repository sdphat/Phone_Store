<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RolesController extends Controller
{
    function store(Request $request)
    {
        $f = $request->get("function");
        try {
            call_user_func_array(["App\\Http\\Controllers\\RolesController", $f], [$request]);
        }catch (Exception $exception){
            echo "Not found";
        }
    }
}
