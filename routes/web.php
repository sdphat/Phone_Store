<?php

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
});
Route::get("/home",function (){
    return view("home");
});
Route::get("/product_details",function (){
    return view("product-details");
});
Route::get("/user",function (){
    return view("user");
});
Route::get("/cart",function (){
    return view("cart");
});
Route::get("/test",function (){
    return view("welcome");
});
Route::get('admin', [UsersController::class, 'admin'])->name('users.admin');
Route::get('admin-login', [UsersController::class, 'adminLoginPage'])->name('users.admin');

