<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TransferController;
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
Route::resource('/', HomeController::class);
Route::get('/search', [HomeController::class, 'search']);
Route::get('/transfer', [HomeController::class, 'transfer']);
Route::get('/transfer1', [HomeController::class, 'transfer1']);
Route::post('/list', [HomeController::class, 'list']);
Route::post('/store', [TransferController::class, 'store']);
Route::post('/store1', [TransferController::class, 'store1']);
Route::get('/error', [TransferController::class, 'error']);
Route::get('/cancel/{id}', [TransferController::class, 'cancel']);


Auth::routes();
Route::get('/logout','\App\Http\Controllers\Auth\LoginController@logout');


