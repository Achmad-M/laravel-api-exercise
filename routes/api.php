<?php

use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\authController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('buku', [BukuController::class, 'index'])->middleware('auth:sanctum', 'ablity:book-list');
// Route::get('buku/{id}', [BukuController::class, 'show']);
Route::post('buku', [BukuController::class, 'store'])->middleware('auth:sanctum', 'ablity:book-store');
// Route::put('buku/{id}', [BukuController::class, 'update']);
// Route::delete('buku/{id}', [BukuController::class, 'destroy']);

// Route::apiResource('buku', BukuController::class)->middleware('auth:sanctum');

Route::get('/', function () {
    return response()->json([
        'status' => false,
        'message' => 'akses tidak diperbolehkan'
    ], 401);
})->name('login');

Route::post('registerUser', [authController::class, 'registerUser']);
Route::post('loginUser', [authController::class, 'loginUser']);
