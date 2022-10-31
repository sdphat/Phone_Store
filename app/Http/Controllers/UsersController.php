<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Users;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function store(Request $request)
    {
        $f = $request->get("function");
        try {
            call_user_func_array([get_class($this), $f], [$request]);
        } catch (Exception $exception) {
            echo "Not found";
        }
    }

    public function getAll(Request $request)
    {
        echo json_encode(Users::all());
    }

    public function changeStatus(Request $request)
    {
        $key = $request->get('key');
        $status = $request->get('trangThai');
        echo json_encode(Users::where("MaND", $key)->update(["TrangThai" => $status]));
    }

    public function delete(Request $request)
    {
        $id = $request->get('mand');
        echo json_encode(Products::where('MaND', $id)->delete());
    }

    public function login(Request $request)
    {
        $token = $request->header("X-CSRF-TOKEN");
        $username = $request->get('username');
        $password = $request->get('password');
        $password = md5($password);
        $sql = "SELECT * FROM nguoidung WHERE TaiKhoan='$username' AND MatKhau='$password' AND MaQuyen=1 AND TrangThai=1";
        $result = DB::select($sql);
        if (count($result) == 1) {
            Users::where("MaND", $result[0]->MaND)->update(["api_token" => $token]);
            $result[0]->api_token = $token;
            echo json_encode($result[0]);
        } else echo json_encode(null);
    }

    public function adminLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
//            'password' => 'required|min:6|max:20|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/|confirmed',
        ]);
        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $messages) {
                foreach ($messages as $message) {
                    echo $message;
                    return;
                }
            }
        }
        $username = $request->get('username');
        $password = md5($request->get('password'));
        $token = $request->header("X-CSRF-TOKEN");
        $sql = "SELECT * FROM nguoidung WHERE TaiKhoan = '$username' AND MatKhau='$password' AND MaQuyen!='1' AND TrangThai=1";
        $result = DB::select($sql);
        if (count($result) == 1) {
            Users::where("MaND", $result[0]->MaND)->update(["api_token" => $token]);
            echo "yes";
        } else  echo "no";
    }

    public function register(Request $request)
    {
        $ho = $request->get('data_ho');
        $ten = $request->get('data_ten');
        $sdt = $request->get('data_sdt');
        $email = $request->get('data_email');
        $diachi = $request->get('data_diachi');
        $newUser = $request->get('data_newUser');
        $newPass = $request->get('data_newPass');
        $newPass = md5($newPass);
        $token = $request->header("X-CSRF-TOKEN");
        Users::create([
            "Ho" => $ho,
            "Ten" => $ten,
            "SDT" => $sdt,
            "Email" => $email,
            "DiaChi" => $diachi,
            "TaiKhoan" => $newUser,
            "MatKhau" => $newPass,
            "MaQuyen" => 1,
            "TrangThai" => 1,
            "api_token" => NUll,
        ]);
//        login
        $sql = "SELECT * FROM nguoidung WHERE TaiKhoan='$newUser' AND MatKhau='$newPass' AND MaQuyen=1 AND TrangThai=1";
        $result = DB::select($sql);
        if ($result != false) {
            $result[0]->api_token = $token;
            echo json_encode($result);
        } else {
            echo json_encode(null);
        }
    }

    public function loginUserInformation(Request $request)
    {
        $token = $request->header("X-CSRF-TOKEN");
        $user = Users::where("api_token", $token)->get();
        if (count($user) == 1) {
            echo json_encode($user[0]);
        } else {
            echo json_encode(null);
        }

    }

    public function logout(Request $request)
    {
        $token = $request->header("X-CSRF-TOKEN");
        Users::where("api_token", $token)->update(["api_token" => NULL]);
        echo "ok";
    }

    public function admin(): Factory|View|Application
    {
        $token = csrf_token();
        $user = Users::where("api_token", $token)->get();
        if (count($user) == 1) {
            return view("admin");
        } else {
            echo view("home");
        }
    }
}
