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
    function index()
    {
        echo "Products";
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

    function getAll(Request $request)
    {
        $list = Products::all();
        for ($i = 0; $i < sizeof($list); $i++) {
            $list[$i]["KM"] = Promotions::whereIn("MaKM", [$list[$i]['MaKM']])->get();
            $list[$i]["LSP"] = ProductTypes::whereIn("MaLSP", [$list[$i]['MaLSP']])->get();
        }
        echo json_encode($list);
    }

    function getById(Request $request)
    {
        $id = $request->get("id");
        $sp = Products::find($id);
        $sp["KM"] = Promotions::whereIn("MaKM", [$sp['MaKM']])->get();
        $sp["LSP"] = ProductTypes::whereIn("MaLSP", [$sp['MaLSP']])->get();
        echo json_encode($sp);
    }

    function getByListId(Request $request)
    {
        $listID = $request->get("listID");
        $result = array();
        foreach ($listID as $id) {
            $sp = Products::find($id);
            $sp["KM"] = Promotions::whereIn("MaKM", [$sp['MaKM']])->get();
            $sp["LSP"] = ProductTypes::whereIn("MaLSP", [$sp['MaLSP']])->get();
            array_push($result, $sp);
        }
        echo json_encode($result);
    }

    function handleFilters(Request $request){
        $filters = $request->get('filters');
        $ori = "SELECT * FROM SanPham WHERE TrangThai=1 AND SoLuong>0 AND ";
        $sql = $ori;

        // $page = null;
        $tenThanhPhanCanSort = null;
        $typeSort = null;

        foreach ($filters as $filter) {
            $dauBang = explode("=", $filter);
            switch ($dauBang[0]) {
                case 'search':
                    $dauBang[1] = explode("+", $dauBang[1]);
                    $dauBang[1] = join(" ", $dauBang[1]);
                    $dauBang[1] = mysqli_escape_string(DB::getDefaultConnection(), $dauBang[1]);
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
                    $khuyenmai = (new DB_driver())->get_row("SELECT * FROM KhuyenMai WHERE LoaiKM='$loaikm'");
                    $khuyenmaiID = $khuyenmai["MaKM"];

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
            $list[$i]->KM= Promotions::whereIn("MaKM", [$list[$i]->MaKM])->get();
            $list[$i]->LSP = ProductTypes::whereIn("MaLSP", [$list[$i]->MaLSP])->get();
        }
        echo json_encode($list);
    }


    function addFromWeb1(Request $request)
    {
        $spBUS = new SanPhamBUS();
        $sp = $request->get('sanpham');
        $loaisanpham = (new DB_driver())->get_row("SELECT * FROM LoaiSanPham WHERE TenLSP='" . $sp["company"] . "'");

        $sanphamArr = array(
            'MaSP' => "",
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

        echo json_encode($spBUS->add_new($sanphamArr));
    }

    function getAllBanners(Request $request)
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

    function getSmallBanner(Request $request)
    {
        echo '["../assets/img/smallBanners/blackFriday.gif"]';
    }
}
