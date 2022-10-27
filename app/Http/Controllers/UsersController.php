<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    function index()
    {
        echo "Users";
    }

    function store(Request $request)
    {
        $f = $request->get("function");
        try {
            call_user_func_array([get_class($this), $f], [$request]);
        } catch (Exception $exception) {
            echo "Not found";
        }
    }

    function login(Request $request)
    {
        $taikhoan = $request->header('username');
        $matkhau = $request->header('password');
        $token = Str::random(60);
        $matkhau = md5($matkhau);
        $sql = "SELECT * FROM nguoidung WHERE TaiKhoan='$taikhoan' AND MatKhau='$matkhau' AND MaQuyen=1 AND TrangThai=1";
        $result = DB::select($sql);
        if ($result != false) {
            Users::updateToken($result[0]->MaND, $token);
            $result[0]->api_token = $token;
            echo json_encode($result);
        } else echo json_encode(null);
    }

    public function getAll(){
        $khachang = (new NguoiDungBUS())->select_all();
        die (json_encode($khachang));
    }
    public function changeStatus(){
        $khachhangBUS = new NguoiDungBUS();
        $key = $_POST['key'];
        $trangthai = $_POST['trangThai'];
        die (json_encode($khachhangBUS->capNhapTrangThai($trangthai, $key)));
    }
    public function delete(){
        $khachhangBUS = new NguoiDungBUS();
        $mand = $_POST['mand'];

        die (json_encode($khachhangBUS->delete_by_id($mand	)));
    }

    public function adminLogin()
    {
        $taikhoan = $_POST['data_username'];
        $matkhau = md5($_POST['data_password']);

// Sau khi dang nhap
        require("../BackEnd/ConnectionDB/DB_driver.php");

        $db = new DB_driver();
        $db->connect();

        $taikhoan = mysqli_escape_string($db->__conn, $taikhoan);
        $matkhau = mysqli_escape_string($db->__conn, $matkhau);

// mysqli_set_charset($connSanPham,"utf8");
        $sql = "SELECT * FROM nguoidung WHERE TaiKhoan = '$taikhoan' AND MatKhau='$matkhau' AND MaQuyen!='1' AND TrangThai=1";

        $dsad = $db->get_list($sql);

        if (sizeof($dsad) > 0) {
            $_SESSION['currentUser'] = $dsad[0];
            // header('Location: http://localhost/myweb/themplate/admin.php');
            echo "yes";

        } else  echo "no";

        $db->dis_connect();
    }

    public function loginUserInformation(Request $request){
        $user=Users::where("api_token",$request->get("token"))->get();
        if($user!=false) {
            echo json_encode($user);
        }
        echo json_encode(null);
    }

    public function logout(Request $request)
    {
        $token = $request->get("token");
        Users::where("api_token", $token)->update(["api_token" => null]);
    }

    function register(Request $request)
    {
        $xuli_ho = $request->get('data_ho');
        $xuli_ten = $request->get('data_ten');
        $xuli_sdt = $request->get('data_sdt');
        $xuli_email = $request->get('data_email');
        $xuli_diachi = $request->get('data_diachi');
        $xuli_newUser = $request->get('data_newUser');
        $xuli_newPass = $request->get('data_newPass');
        $xuli_newPass = md5($xuli_newPass);
        $status = Users::create([
            "MaND" => "",
            "Ho" => $xuli_ho,
            "Ten" => $xuli_ten,
            "SDT" => $xuli_sdt,
            "Email" => $xuli_email,
            "DiaChi" => $xuli_diachi,
            "TaiKhoan" => $xuli_newUser,
            "MatKhau" => $xuli_newPass,
            "MaQuyen" => 1,
            "TrangThai" => 1,
            "api_token" => null,
        ]);

        // đăng nhập vào ngay
        $sql = "SELECT * FROM nguoidung WHERE TaiKhoan='$xuli_newUser' AND MatKhau='$xuli_newPass' AND MaQuyen=1 AND TrangThai=1";
        $result = (new DB_driver())->get_row($sql);

        if ($result != false) {
            $_SESSION['currentUser'] = $result;
            echo json_encode($result);
        }

        echo json_encode(null);
    }
}
