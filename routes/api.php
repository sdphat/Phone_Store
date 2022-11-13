<?php

use App\Http\Controllers\BillsController;
use App\Http\Controllers\BillsDetailsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProductTypesController;
use App\Http\Controllers\PromotionsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VotedController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Ramsey\Uuid\Rfc4122\UuidV4;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResource('products', ProductsController::class);
Route::apiResource('product_types', ProductTypesController::class);
Route::apiResource("promotions", PromotionsController::class);
Route::apiResource('bill-details', BillsDetailsController::class);
Route::apiResource('voted', VotedController::class);
Route::apiResource('bills', BillsController::class);
Route::apiResource('users', UsersController::class);

Route::post("upload-product-image", function (Request $request) {
    if ($request->hasFile("image")) {
        $request->file("image")->move('img/products', $request->file("image")->getClientOriginalName());
        return "img/products/".$request->file("image")->getClientOriginalName();
    }
    return "";
});
