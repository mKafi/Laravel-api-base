<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\KafiController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TraderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SalesController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ReceivableController;
use App\Http\Controllers\Api\MarchantController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductStockController;

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

Route::apiResource('posts', KafiController::class);

Route::apiResource('roles', RoleController::class)->middleware('auth:sanctum');
Route::post('/auth/login', [AuthController::class, 'apiLoginUser']);
Route::apiResource('traders', TraderController::class)->middleware('auth:sanctum');
Route::apiResource('products', ProductController::class)->middleware('auth:sanctum');
Route::apiResource('sales', SalesController::class)->middleware('auth:sanctum');
Route::apiResource('customer', CustomerController::class)->middleware('auth:sanctum');
Route::apiResource('receivable', ReceivableController::class)->middleware('auth:sanctum');
Route::apiResource('marchant', MarchantController::class)->middleware('auth:sanctum');
Route::apiResource('category', CategoryController::class)->middleware('auth:sanctum');
Route::apiResource('product-stock', ProductStockController::class)->middleware('auth:sanctum');

Route::get('/invoice/{invoiceId}', [SalesController::class,'getInvoice'])->middleware('auth:sanctum');
Route::get('product/tags', [ProductController::class,'getTags'])->middleware('auth:sanctum');
Route::post('produc/tags', [ProductController::class,'storeTags'])->middleware('auth:sanctum');

