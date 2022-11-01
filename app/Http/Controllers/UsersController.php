<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Users;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
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
            echo "Not found " . $f;
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
        $this->userLogin($request, 1);
    }

    public function userLogin(Request $request, $MaQuyen)
    {
        $result = [];
        $result["success"] = false;
        $result["message"] = [];
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
            'g-recaptcha-response' => 'required|captcha',
//            'password' => 'required|min:6|max:20|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/|confirmed',
        ]);
        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $messages) {
                foreach ($messages as $message) {
                    array_push($result["message"], $message);
                }
            }
        } else {
            $token = $request->header("X-CSRF-TOKEN");
            $username = $request->get('username');
            $password = $request->get('password');
            $password = md5($password);
            $sql = "SELECT * FROM nguoidung WHERE TaiKhoan='$username' AND MatKhau='$password' AND MaQuyen=$MaQuyen AND TrangThai=1";
            $list = DB::select($sql);
            if (count($list) == 1) {
                Users::where("MaND", $list[0]->MaND)->update(["api_token" => $token]);
                $result["success"] = true;
                $result["data"] = $list[0];
                array_push($result["message"], "Đăng nhập thành công");
            } else {
                array_push($result["message"], "Tên tài khoản hoặc mật khẩu không đúng");
            }
        }
        echo json_encode($result);
    }

    public function register(Request $request)
    {
        $result = [];
        $result["success"] = false;
        $result["message"] = [];
        $validator = Validator::make($request->all(), [
            "lastname"=>"required",
            "firstname"=>"required",
            "phone"=>"required",
            "email"=>"required|email",
            "address"=>"required",
            "username"=>"required",
            "password"=>"required",
//            'password' => 'required|min:6|max:20|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/|confirmed',
        ]);
        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $messages) {
                foreach ($messages as $message) {
                    array_push($result["message"], $message);
                }
            }
        } else {
            try {
                $ho = $request->get("lastname");
                $ten = $request->get("firstname");
                $sdt = $request->get("phone");
                $email = $request->get("email");
                $diachi = $request->get("address");
                $newUser = $request->get('username');
                $newPass = $request->get('password');
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
                    "api_token" => $token,
                ]);
                $result["success"] = true;
                array_push($result["message"], "Tài khoản đã được đăng ký thành công");
            }catch (Exception $exception){
                array_push($result["message"], "Tên tài khoản đã được sử dụng");
            }
        }
        echo json_encode($result);
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

    public function admin(): View|Factory|Redirector|RedirectResponse|Application
    {
        try {
            $token = csrf_token();
            $users = Users::where("api_token", $token)->get();
            if (count($users) == 1) {
                if ($users[0]->MaQuyen == 2) return view("admin");
            }
        } catch (Exception $exception) {
        }
        return redirect("home");
    }

    public function adminLoginPage(): View|Factory|Redirector|RedirectResponse|Application
    {
        try {
            $token = csrf_token();
            $users = Users::where("api_token", $token)->get();
            if (count($users) == 1) {
                if ($users[0]->MaQuyen == 2) return redirect("admin");
            }
        } catch (Exception $exception) {
        }
        return view("admin-login");
    }

    public function adminLogin(Request $request)
    {
        $this->userLogin($request, 2);
    }
}
