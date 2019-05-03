<?php

use Illuminate\Http\Request;

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
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, enctype');
header('Access-Control-Allow-Methods: GET, PATCH, POST, DELETE');

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('/user', 'UserController@getAuthenticatedUser');
    Route::get('/', 'UserController@index');
    Route::post('/changepassword/{id}', 'UserController@updatePassword');

    Route::get('/layanan','JasaServiceController@index');
    Route::get('/layanan/{id}','JasaServiceController@show');
    Route::post('/layanan','JasaServiceController@store');
    Route::post('/layanan/{id}','JasaServiceController@update');
    Route::delete('/layanan/{id}','JasaServiceController@destroy');

    Route::get('/kendaraan','MotorController@index');
    Route::get('/kendaraan/{id}','MotorController@show');
    Route::post('/kendaraan','MotorController@store');
    Route::post('/kendaraan/{id}','MotorController@update');
    Route::delete('/kendaraan/{id}','MotorController@destroy');

    Route::get('/cabang','CabangController@index');
    Route::get('/branchEmployee/{id}','CabangController@employeeByBranch');
    Route::get('/cabang/{id}','CabangController@show');
    Route::post('/cabang','CabangController@store');
    Route::post('/cabang/{id}','CabangController@update');
    Route::delete('/cabang/{id}','CabangController@destroy');

    Route::get('/pegawai','PegawaiController@index');
    Route::get('/employeebyBranch/{branch_id}','PegawaiController@showByBranch');
    Route::get('/pegawai/{id}','PegawaiController@show');
    Route::post('/pegawai','PegawaiController@store');
    Route::post('/pegawai/{id}','PegawaiController@update');
    Route::delete('/pegawai/{id}','PegawaiController@destroy');
    
    Route::get('/supplier', 'SupplierController@index');
    Route::post('/supplier', 'SupplierController@store');
    Route::post('/supplier/{id}', 'SupplierController@update');
    Route::delete('/supplier/{id}', 'SupplierController@destroy');

    Route::get('/sparepart','SparepartController@index');
    Route::get('/sparepart/{kode}','SparepartController@show');
    Route::get('/sparepartBySupplier/{idSupplier}','SparepartController@showBySupplier');
    Route::get('/sparepartCabang/{idCabang}','SparepartController@showByCabang');
    Route::post('/sparepartCabang','SparepartController@storeToCabang');
    Route::post('/sparepart','SparepartController@store');
    Route::post('/sparepart/{kode}','SparepartController@update');
    Route::post('/sparepartIsUnique','SparepartController@isUniqueCode');
    Route::patch('/sparepartCabang/{spBrID}','SparepartController@updateSpCabang');
    Route::delete('/sparepartCabang/{spBrID}','SparepartController@destroyByCabang');
    Route::delete('/sparepart/{kode}','SparepartController@destroy');

});
Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', 'UserController@login');
    Route::post('/register', 'UserController@register');
});