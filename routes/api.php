<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MyTableController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::get('/mytable', [MyTableController::class, 'index']);

// Route::get('/mytable/limit', [MyTableController::class, 'indexlimit']);

// Route::get('/mytable/limitset/{id}', [MyTableController::class, 'indexlimitset']);

// Route::post('/mytable', [MyTableController::class, 'store']);

// Route::put('/put/mytable/{id}', [MyTableController::class, 'updatePut']);

// Route::patch('/patch/mytable/{id}', [MyTableController::class, 'updatePatch']);

// Route::delete('/delete/mytable/{id}',[MyTableController::class, 'delete']);

// Route::post('/login', [AuthController::class, 'login']);

// Đăng nhập để lấy token (Không cần token)
Route::post('/login', [AuthController::class, 'login']);

//get all
Route::get('/mytable', [MyTableController::class, 'index']);



// Các API yêu cầu token để truy cập
Route::middleware('auth:sanctum')->group(function () {

    //get limit
    Route::get('/mytable/limit', [MyTableController::class, 'indexlimit']);
    //get set limit
    Route::get('/mytable/limitset/{id}', [MyTableController::class, 'indexlimitset']);

    Route::post('/mytable', [MyTableController::class, 'store']);

    Route::put('/put/mytable/{id}', [MyTableController::class, 'updatePut']);

    Route::patch('/patch/mytable/{id}', [MyTableController::class, 'updatePatch']);

    Route::delete('/delete/mytable/{id}', [MyTableController::class, 'delete']);
});
