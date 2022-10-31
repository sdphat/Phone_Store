<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Voteds;
use Illuminate\Http\Request;

class VotedController extends Controller
{
    function store(Request $request)
    {
        $f = $request->get("function");
        try {
            call_user_func_array(["App\\Http\\Controllers\\VotedController", $f], [$request]);
        } catch (Exception $exception) {
            echo "Not found";
        }
    }

    public function add(Request $request)
    {
        $productID = $request->get('masp');
        $userId = $request->get('mand');
        $sosao = $request->get('sosao');
        $binhluan = $request->get('binhluan');
        $thoigian = $request->get('thoigian');
        Voteds::create(array(
            "MASP" => $productID,
            "MaND" => $userId,
            "SoSao" => $sosao,
            "BinhLuan" => $binhluan,
            "NgayLap" => $thoigian
        ));
        echo json_encode(true);
    }

    public function get(Request $request)
    {
        $productID = $request->get('masp');
        $listVoted = Voteds::whereIn("MaSP",[$productID])->get();
        for ($i = 0; $i < count($listVoted); $i++) {
            $listVoted[$i]["ND"] = Users::find($listVoted[$i]['MaND']);
        }
        echo json_encode($listVoted);
    }
}
