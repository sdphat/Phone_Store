<?php

namespace App\Http\Controllers;

use App\Mail\NewPasswordMail;
use App\Mail\OTPMail;
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
            Users::where('MaND', $request->get("id"))->delete();
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
            $sql = "SELECT * FROM nguoidung WHERE TaiKhoan='$username' AND MatKhau='$password' AND MaQuyen=$MaQuyen";
            $list = DB::select($sql);
            if (count($list) == 1) {
                if ($list[0]->TrangThai === 1) {
                    Users::where("api_token", $token)->update(["api_token" => null]);
                    Users::where("MaND", $list[0]->MaND)->update(["api_token" => $token]);
                    $result["success"] = true;
                    $result["user"] = $list[0];
                    array_push($result["message"], "Đăng nhập thành công");
                } else {
                    array_push($result["message"], "Tài khoản đã đã bị khóa");
                }

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
        ]);
        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $messages) {
                foreach ($messages as $message) {
                    array_push($result["message"], $message);
                }
            }
            echo json_encode($result);
            return;
        }
        $email = $request->get("email");
        $token = Str::random(60);
        $user = Users::where("Email", $email)->first();
        if ($user) {
            array_push($result["message"], "Email đã được đăng ký bạn có thể nhấp vào quên mật khẩu để lấy lại tài khoản");
        } else {
            Users::create([
                "Email" => $email,
                "TaiKhoan" => $request->get('username'),
                "MatKhau" => md5($request->get('password')),
                "api_token" => $token,
            ]);
            $result["success"] = true;
            echo json_encode($result);
            Mail::to($email)->send(new OTPMail([
                'title' => 'Xác nhận đăng ký',
                "otp" => $token
            ]));
            return;
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
        $ho = $request->get("ho");
        $ten = $request->get("ten");
        $sdt = $request->get("sdt");
        $address = $request->get("address");
        try {
            $loginID = "no";
            $user = Users::where("api_token", $token)->first();
            if ($user) $loginID = $user->MaND;
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
                Users::where("api_token", $request->get("otp"))->update([
                    "api_token" => $token,
                    "Ho" => $ho,
                    "Ten" => $ten,
                    "SDT" => $sdt,
                    "DiaChi" => $address,
                    "TrangThai" => 1
                ]);
            }
        } catch (Exception $exception) {
            echo $exception;
            return;
        }
        echo "OK";
    }

    public function loginUserInformation(Request $request)
    {
        $token = $request->header("X-CSRF-TOKEN");
        $user = Users::where("api_token", $token)->get();
        if (count($user) == 1 && $user[0]->TrangThai == 1) {
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
        } catch (Exception) {
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
        } catch (Exception) {
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
                $result["success"] = true;
                array_push($result["noty"], "Vui lòng xác nhận email và tạo mật khẩu mới");
                echo json_encode($result);
                Mail::to($request->get("email"))->send(new NewPasswordMail([
                    'title' => 'Tạo mật khẩu mới',
                    "token" => $token
                ]));
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
                'again' => 'required'
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
        $user = Users::where("MatKhau", $id)->first();
        if ($user) {
            return $this->loginWithID($id);
        } else {
            $token = Str::random(60);
            Users::create([
                "Ho" => $googleUser->user["family_name"] ?? "",
                "Ten" => $googleUser->user["given_name"] ?? "",
                "TaiKhoan" => $googleUser["name"],
                "MatKhau" => $id,
                "api_token" => $token,
                "TrangThai"=>1
            ]);
            return redirect("confirm-email?otp=" . $token);
        }
    }

    public function loginWithID($id): Redirector|Application|RedirectResponse
    {
        $token = csrf_token();
        Users::where("api_token", $token)->update(["api_token" => null]);
        Users::where("MatKhau", $id)->update(["api_token" => $token]);
        return redirect("home");
    }

    public function loginWithFacebook(): Redirector|Application|RedirectResponse
    {
        $facebookUser = Socialite::driver("facebook")->user();
        $id = $facebookUser->getId();
        $user = Users::where("MatKhau", $id)->first();
        if ($user) {
            return $this->loginWithID($id);
        } else {
            $token = Str::random(60);
            Users::create([
                "TaiKhoan" => $facebookUser->getName(),
                "MatKhau" => $id,
                "api_token" => $token,
                "TrangThai"=>1,
            ]);
            return redirect("confirm-email?otp=" . $token);
        }
    }
}
