<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Currency;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/sources-list', 'ApiController@getSourcesList')->name('getSourcesList');
Route::get('/currencies-list/{api}', 'ApiController@getCurrenciesList')->name('getCurrenciesList');

Route::get('/convert', function () {
    return response()->json(['message' => 'To few arguments'], 400);
});
Route::get('/convert/{api}', function () {
    return response()->json(['message' => 'To few arguments'], 400);
});
Route::get('/convert/{api}/{from}', function () {
    return response()->json(['message' => 'To few arguments'], 400);
});
Route::get('/convert/{api}/{from}/{to}', function () {
    return response()->json(['message' => 'To few arguments'], 400);
});
Route::get('/convert/{api}/{from}/{to}/{amount}', 'ApiController@convert')->name('convert');

