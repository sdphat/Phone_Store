<?php

namespace App\Http\Controllers;

use App\Mail\NewPasswordMail;
use App\Mail\OTPMail;
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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class UsersController extends Controller
{
    public function store(Request $request)
    {
        $f = $request->get("function");
        try {
            call_user_func_array([get_class($this), $f], [$request]);
        } catch (Exception $exception) {
            echo "Not found " . $exception;
        }
    }

    public function getAll(Request $request)
    {
        echo json_encode(Users::all());
    }

    public function changeStatus(Request $request)
    {
        try {
            $key = $request->get("id");
            $status = $request->get("status");
            Users::where("MaND", $key)->update(["TrangThai" => $status]);
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function delete(Request $request)
    {
        try {
            Products::where('MaND', $request->get("id"))->delete();
        } catch (Exception $e) {
            echo $e;
        }
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
        ]);
        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $messages) {
                foreach ($messages as $message) {
                    if ($message === "The g-recaptcha-response field is required.") $message = "Chưa xác thực captcha";
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
                Users::where("api_token", $token)->update(["api_token" => null]);
                Users::where("MaND", $list[0]->MaND)->update(["api_token" => $token]);
                $result["success"] = true;
                $result["user"] = $list[0];
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
            "email" => "required|email",
            "username" => "required",
            "password" => "required|min:6",
//            'password' => 'required|min:6|max:20|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/|confirmed',
        ]);
        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $messages) {
                foreach ($messages as $message) {
                    array_push($result["message"], $message);
                }
            }
        } else {
            $email = $request->get("email");
            $newUser = $request->get('username');
            $newPass = $request->get('password');
            $newPass = md5($newPass);
            $token = Str::random(60);
            $users = Users::where("Email", $email)->get();
            if (count($users) === 1) {
                array_push($result["message"], "Email đã được đăng ký bạn có thể nhấp vào quên mật khẩu để lấy lại tài khoản");
            } else {
                try {

                    Users::create([
                        "Ho" => "",
                        "Ten" => "",
                        "SDT" => "",
                        "Email" => $email,
                        "DiaChi" => "",
                        "TaiKhoan" => $newUser,
                        "MatKhau" => $newPass,
                        "MaQuyen" => 1,
                        "TrangThai" => 0,
                        "api_token" => $token,
                    ]);
                    $mailData = [
                        'title' => 'Xác nhận đăng ký',
                        "otp" => $token
                    ];
                    $result["success"] = true;
                    Mail::to('nguyentandat16052000@gmail.com')->send(new OTPMail($mailData));
                    return;
                } catch (Exception $exception) {
                    array_push($result["message"], "Tên tài khoản đã được sử dụng");
                }
            }
        }
        echo json_encode($result);
    }

    public function confirmEmail(Request $request): Factory|View|Application
    {
        $otp = $request->get("otp");
        $users = Users::where("api_token", $otp)->get();
        if (count($users) === 1) {
            $user = $users[0];
            return view("user-info", compact("user"));
        }
        return view("errors.404");
    }

    public function changePassword(Request $request): Factory|View|Application
    {
        $otp = $request->get("token");
        $users = Users::where("api_token", $otp)->get();
        if (count($users) === 1) {
            $user = $users[0];
            return view("user-change-password", compact("user"));
        }
        return view("errors.404");
    }

    public function handleChangePass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old' => 'required',
            'new' => 'required',
//            'password' => 'required|min:6|max:20|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/|confirmed',
        ]);
        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $messages) {
                foreach ($messages as $message) {
                    echo($message);
                }
            }
            return;
        }
        $token = $request->header("X-CSRF-TOKEN");
        $users = Users::where("api_token", $token)->get();
        if (count($users) === 1) {
            $pass = $users[0]->MatKhau;
            $pass = $pass;
            if ($pass === md5($request->get("old"))) {
                $new = $request->get("new");
                $again = $request->get("again");
                if ($new === $again) {
                    $new = md5($new);
                    Users::where("api_token", $token)->Update([
                        "MatKhau" => $new
                    ]);
                    echo "Đổi mật khẩu thành công";
                } else {
                    echo "Mật khẩu nhập lại không khớp";
                }
            } else {
                echo "Mật khẩu cũ không đúng";
            }
        } else {
            echo "Lỗi xác thực";
        }
    }

    public function confirmRegister(Request $request)
    {
        $token = $request->header("X-CSRF-TOKEN");
        $id = $request->get("id");
        $otp = $request->get("otp");
        $ho = $request->get("ho");
        $ten = $request->get("ten");
        $sdt = $request->get("sdt");
        $address = $request->get("address");
        try {
            $loginID = "no";
            $logins = Users::where("api_token", $token)->get();
            if (count($logins) > 0) $loginID = $logins[0]->MaND;
            if ($loginID == $id) {
                Users::where("MaND", $id)->update([
                    "api_token" => $token,
                    "Ho" => $ho,
                    "Ten" => $ten,
                    "SDT" => $sdt,
                    "DiaChi" => $address,
                    "TrangThai" => 1
                ]);
            } else {
                Users::where("api_token", $token)->update(["api_token" => null]);
                Users::where("api_token", $otp)->update([
                    "api_token" => $token,
                    "Ho" => $ho,
                    "Ten" => $ten,
                    "SDT" => $sdt,
                    "DiaChi" => $address,
                    "TrangThai" => 1
                ]);
            }
        } catch (Exception $exception) {
            echo "No";
        }
        echo "OK";
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

    public function handleForgotPassword(Request $request)
    {
        $result = [];
        $result["true"] = false;
        $result["noty"] = [];
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'g-recaptcha-response' => 'required|captcha',
//            'password' => 'required|min:6|max:20|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/|confirmed',
        ]);
        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $messages) {
                foreach ($messages as $message) {
                    if ($message === "The g-recaptcha-response field is required.") $message = "Chưa xác thực captcha";
                    array_push($result["noty"], $message);
                }
            }
        } else {
            $users = Users::where("Email", $request->get("email"))->get();
            if (count($users) === 1) {
                $token = Str::random(60);
                Users::where("Email", $request->get("email"))->update(["api_token" => $token]);
                $mailData = [
                    'title' => 'Xác nhận đăng ký',
                    "token" => $token
                ];
                $result["success"] = true;
                array_push($result["noty"], "Vui lòng xác nhận email và tạo mật khẩu mới");
                echo json_encode($result);
                Mail::to('nguyentandat16052000@gmail.com')->send(new NewPasswordMail($mailData));
                return;
            } else {
                array_push($result["noty"], "Email chưa được đăng ký");
            }
        }
        echo json_encode($result);
    }

    public function newPassword(Request $request): Factory|View|Application
    {
        $otp = $request->get("token");
        $users = Users::where("api_token", $otp)->get();
        if (count($users) === 1) {
            $user = $users[0];
            return view("user-new-password", compact("user"));
        }
        return view("errors.404");
    }

    public function handleNewPassword(Request $request)
    {
        $token = $request->get("token");
        $users = Users::where("api_token", $token)->get();
        if (count($users) === 1) {
            $validator = Validator::make($request->all(), [
                'new' => 'required',
                'again' => 'required',
//            'password' => 'required|min:6|max:20|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/|confirmed',
            ]);
            if ($validator->fails()) {
                foreach ($validator->messages()->getMessages() as $messages) {
                    foreach ($messages as $message) {
                        echo($message);
                    }
                }
            } else {
                $new = $request->get("new");
                $again = $request->get("again");
                if ($new === $again) {
                    $new = md5($new);
                    Users::where("api_token", $token)->Update(["MatKhau" => $new]);
                    echo "Đổi mật khẩu thành công";
                } else {
                    echo "Mật khẩu nhập lại không khớp";
                }
            }
        } else {
            echo "Lỗi xác thực";
        }
    }

    public function loginWithGoogle(): Redirector|Application|RedirectResponse
    {
        $googleUser = Socialite::driver("google")->user();
        $id = $googleUser->getId();
        $name = $googleUser["name"];
        $user = Users::where("MatKhau", $id)->where("TaiKhoan", $name)->first();
        if ($user) {
            $token=csrf_token();
            Users::where("api_token", $token)->update(["api_token" => null]);
            Users::where("MatKhau", $id)->where("TaiKhoan", $name)->update(["api_token" => csrf_token()]);
            return redirect("home");
        } else {
            $email = $googleUser->getEmail();
            $ho = $googleUser->user["family_name"];
            $ten = $googleUser->user["given_name"];
            $token = Str::random(60);
            Users::create([
                "Ho" => $ho,
                "Ten" => $ten,
                "SDT" => "",
                "Email" => $email,
                "DiaChi" => "",
                "TaiKhoan" => $name,
                "MatKhau" => $id,
                "MaQuyen" => 1,
                "TrangThai" => 0,
                "api_token" => $token,
            ]);
            return redirect("confirm-email?otp=" . $token);
        }
    }

    public function loginWithFacebook(): Redirector|Application|RedirectResponse
    {
        $facebookUser = Socialite::driver("facebook")->user();
        $id = $facebookUser->getId();
        $name = $facebookUser->getName();
        $user = Users::where("MatKhau", $id)->where("TaiKhoan",$name)->first();
        if ($user) {
            $token=csrf_token();
            Users::where("api_token", $token)->update(["api_token" => null]);
            Users::where("MatKhau", $id)->update(["api_token" => $token]);
            return redirect("home");
        } else {
            $email = $facebookUser->getEmail();
            $token = Str::random(60);
            Users::create([
                "Ho" => "",
                "Ten" => "",
                "SDT" => "",
                "Email" => $email,
                "DiaChi" => "",
                "TaiKhoan" => $name,
                "MatKhau" => $id,
                "MaQuyen" => 1,
                "TrangThai" => 0,
                "api_token" => $token,
            ]);
            return redirect("confirm-email?otp=" . $token);
        }
    }
}
