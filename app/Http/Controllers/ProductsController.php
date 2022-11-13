<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\ProductTypes;
use App\Models\Promotions;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    public function store(Request $request)
    {
        $f = $request->get("function");
        try {
            call_user_func_array([get_class($this), $f], [$request]);
        } catch (Exception) {
            echo "Not found";
        }
    }

    public function update(Request $request) {
        try {
            $data = $request->get('dataUpdate');
            Products::query()->where('MaSP', $data['masp'])->update([
                'MaLSP' => $data['company'],
                'TenSP' => $data['name'],
                'DonGia' => $data['price'],
                'SoLuong' => $data['amount'],
                'HinhAnh' => $data['img'],
                'MaKM' => $data['promo']['name'],
                'ManHinh' => $data['detail']['screen'],
                'HDH' => $data['detail']['os'],
                'CamSau' => $data['detail']['camara'],
                'CamTruoc' => $data['detail']['camaraFront'],
                'CPU' => $data['detail']['cpu'],
                'Ram' => $data['detail']['ram'],
                'Rom' => $data['detail']['rom'],
                'SDCard' => $data['detail']['microUSB'],
                'Pin' => $data['detail']['battery'],
                'SoSao' => $data['star'],
                'SoDanhGia' => $data['rateCount'],
                'TrangThai' => $data['TrangThai']
            ]);
            echo json_encode(Products::query()->find($data->masp));
        } catch(Exception $ex) {
            dd($ex);
        }
    }

    public function getAll(Request $request)
    {
        $list = Products::all();
        for ($i = 0; $i < sizeof($list); $i++) {
            $list[$i]["KM"] = Promotions::whereIn("MaKM", [$list[$i]['MaKM']])->get()[0];
            $list[$i]["LSP"] = ProductTypes::whereIn("MaLSP", [$list[$i]['MaLSP']])->get()[0];
        }
        echo json_encode($list);
    }

    public function getById(Request $request)
    {
        $id = $request->get("id");
        $sp = Products::find($id);
        $sp["KM"] = Promotions::whereIn("MaKM", [$sp['MaKM']])->get();
        $sp["LSP"] = ProductTypes::whereIn("MaLSP", [$sp['MaLSP']])->get();
        echo json_encode($sp);
    }


    public function getByListId(Request $request)
    {
        $listID = $request->get("listID");
        $result = array();
        foreach ($listID as $id) {
            $sp = Products::find($id);
            $sp["KM"] = Promotions::where("MaKM", $sp['MaKM'])->get()[0];
            $sp["LSP"] = ProductTypes::where("MaLSP", $sp['MaLSP'])->get()[0];
            array_push($result, $sp);
        }
        echo json_encode($result);
    }

    public function handleFilters(Request $request)
    {
        $filters = $request->get('filters');
        $ori = "SELECT * FROM SanPham WHERE TrangThai=1 AND SoLuong>0 AND ";
        $sql = $ori;
        $tenThanhPhanCanSort = null;
        $typeSort = null;
        foreach ($filters as $filter) {
            $dauBang = explode("=", $filter);
            switch ($dauBang[0]) {
                case 'search':
                    $dauBang[1] = explode("+", $dauBang[1]);
                    $dauBang[1] = join(" ", $dauBang[1]);
                    $sql .= ($sql == $ori ? "" : " AND ") . " TenSP LIKE '%$dauBang[1]%' ";
                    break;
                case 'price':
                    $prices = explode("-", $dauBang[1]);
                    $giaTu = (int)$prices[0];
                    $giaDen = (int)$prices[1];
                    // nếu giá đến = 0 thì cho giá đến = 100 triệu
                    if ($giaDen == 0) $giaDen = 1000000000;
                    $sql .= ($sql == $ori ? "" : " AND ") . " DonGia >= $giaTu AND DonGia <= $giaDen";
                    break;
                case 'company':
                    $companyID = $dauBang[1];
                    $sql .= ($sql == $ori ? "" : " AND ") . " MaLSP='$companyID'";
                    break;
                case 'star':
                    $soSao = (int)$dauBang[1];
                    $sql .= ($sql == $ori ? "" : " AND ") . " SoSao >= $soSao";
                    break;
                case 'promo':
                    // lấy id khuyến mãi
                    $loaikm = $dauBang[1];
                    $khuyenmai = DB::selectOne("SELECT * FROM KhuyenMai WHERE LoaiKM='$loaikm'");
                    $khuyenmaiID = $khuyenmai->MaKM;
                    $sql .= ($sql == $ori ? "" : " AND ") . " MaKM='$khuyenmaiID'";
                    break;
                case 'sort':
                    $s = explode("-", $dauBang[1]);
                    $tenThanhPhanCanSort = $s[0];
                    $typeSort = ($s[1] == "asc" ? "ASC" : "DESC");
                    break;
                    // case 'page':
                    //     $page = $dauBang[1];
                    //     break;
                default:
                    # code...
                    break;
            }
        }

        // sort phải để cuối
        if ($tenThanhPhanCanSort != null && $typeSort != null) {
            $sql .= ($sql == $ori ? " 1=1 " : ""); // fix lỗi dư chữ AND
            $sql .= " ORDER BY $tenThanhPhanCanSort $typeSort";
        }

        // Phân trang
        // if($page != 0 || $page == null) { // nếu == 0 thì trả về hết
        //     if($page == null) $page = 1; // mặc định là trang 1 (nếu không ghi gì hết)
        //     $productsPerPage = 10; // số lượng sản phẩm trong 1 trang
        //     $startIndex = ($page-1)*$productsPerPage;
        //     $sql .= ($sql==$ori?" 1=1 ":""); // fix lỗi dư chữ where
        //     $sql .= " LIMIT $startIndex,$productsPerPage";
        // }

        // chạy sql
        $list = DB::select($sql);
        for ($i = 0; $i < sizeof($list); $i++) {
            $list[$i]->KM = Promotions::whereIn("MaKM", [$list[$i]->MaKM])->get()[0];
            $list[$i]->LSP = ProductTypes::whereIn("MaLSP", [$list[$i]->MaLSP])->get()[0];
        }
        echo json_encode($list);
    }


    public function addFromWeb1(Request $request)
    {
        $sp = $request->get('sanpham');
        $loaisanpham = DB::select("SELECT * FROM LoaiSanPham WHERE TenLSP='" . $sp["company"] . "'");
        $sanphamArr = array(
            'MaLSP' => $loaisanpham['MaLSP'],
            'TenSP' => $sp['name'],
            'DonGia' => $sp['price'],
            'SoLuong' => 10,
            'HinhAnh' => $sp['img'],
            'MaKM' => $sp['MaKM'],
            'ManHinh' => $sp['detail']['screen'],
            'HDH' => $sp['detail']['os'],
            'CamSau' => $sp['detail']['camara'],
            'CamTruoc' => $sp['detail']['camaraFront'],
            'CPU' => $sp['detail']['cpu'],
            'Ram' => $sp['detail']['ram'],
            'Rom' => $sp['detail']['rom'],
            'SDCard' => $sp['detail']['microUSB'],
            'Pin' => $sp['detail']['battery'],
            'SoSao' => 0,
            'SoDanhGia' => 0,
            'TrangThai' => 1
        );
        echo json_encode(Products::insert($sanphamArr));
    }

    public function add(Request $request)
    {
        try {
            $data = $request->get('dataAdd');
    
            Products::insert([
                'MaLSP' => $data['company'],
                'TenSP' => $data['name'],
                'DonGia' => $data['price'],
                'SoLuong' => $data['amount'],
                'HinhAnh' => $data['img'],
                'MaKM' => $data['promo']['name'],
                'ManHinh' => $data['detail']['screen'],
                'HDH' => $data['detail']['os'],
                'CamSau' => $data['detail']['camara'],
                'CamTruoc' => $data['detail']['camaraFront'],
                'CPU' => $data['detail']['cpu'],
                'Ram' => $data['detail']['ram'],
                'Rom' => $data['detail']['rom'],
                'SDCard' => $data['detail']['microUSB'],
                'Pin' => $data['detail']['battery'],
                'SoSao' => $data['star'],
                'SoDanhGia' => $data['rateCount'],
                'TrangThai' => $data['TrangThai']
            ]);
        } catch(Exception $ex) {
            dd($ex);
        }
    }

    public function delete(Request $request)
    {
        $id = $request->get('maspdelete');
        echo json_encode(Products::where('MaSP', $id)->delete());
    }

    public function hide(Request $request)
    {
        $id = $request->get("id");
        $status = $request->get("trangthai");
        echo json_encode(Products::where("MaSP", $id)->update(["TrangThai" => $status]));
    }

    public function getAllBanners(Request $request)
    {
        $directory = asset("assets") . "/img/banners";
        $images = glob($directory . "/*.{png,gif}", GLOB_BRACE);
        echo '[
            "../assets/img/banners/banner1.png",
            "../assets/img/banners/banner2.png",
            "../assets/img/banners/banner3.png",
            "../assets/img/banners/banner4.png",
            "../assets/img/banners/banner5.png",
            "../assets/img/banners/banner6.png",
            "../assets/img/banners/banner7.png",
            "../assets/img/banners/banner8.png",
            "../assets/img/banners/banner9.png",
            "../assets/img/banners/banner0.gif"
        ]';
    }

    public function getSmallBanner(Request $request)
    {
        echo '["../assets/img/smallBanners/blackFriday.gif"]';
    }

    public function uploadImage(Request $request)
    {
        $target_dir = "img/products/";
        $target_file = $target_dir . basename($_FILES["hinhanh"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        if ($request->get("submit")) {
            $check = getimagesize($_FILES["hinhanh"]["tmp_name"]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["hinhanh"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["hinhanh"]["tmp_name"], $target_file)) {
                echo "The file " . basename($_FILES["hinhanh"]["name"]) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }
}
