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
    Route::get('/branchEmployee/{id}','CabangController@employeeByCabang');
    Route::get('/cabang/{id}','CabangController@show');
    Route::post('/cabang','CabangController@store');
    Route::post('/cabang/{id}','CabangController@update');
    Route::delete('/cabang/{id}','CabangController@destroy');

    Route::get('/pegawai','PegawaiController@index');
    Route::get('/employeebyCabang/{branch_id}','PegawaiController@showByBranch');
    Route::get('/pegawai/{id}','PegawaiController@show');
    Route::post('/pegawai','PegawaiController@store');
    Route::get('/montir/{branch_id}','PegawaiController@showMontirByBranch');
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

    Route::get('/transaksi','TransaksiController@index');
    Route::get('/transaksiByBranch/{branch_id}','TransaksiController@showByBranch');
    Route::post('/transaksi','TransaksiController@store');
    Route::post('/updateTransaksi/{idTransaksi}','TransaksiController@update');
    Route::patch('/transaksi/status/{id}','TransaksiController@updateStatus');
    Route::delete('/transaksi/{id}','TransaksiController@destroy');

    
    Route::get('/customer/{TransaksiId}','DetailTransaksiController@getCustomer');
    Route::get('/detailTransaksi/{TransaksiId}','DetailTransaksiController@showByTransaksi');
    Route::post('/detailTransaksi','DetailTransaksiController@store');
    Route::post('/detailTransaksi/{vehiclecustomerid}','DetailTransaksiController@update');
    Route::delete('/detailTransaksi/{TransaksiDetailId}','DetailTransaksiController@destroy');

    Route::get('/myvehicle/{TransaksiDetailId}','DetailTransaksiController@myvehicle');

    Route::get('/detailTSp/{TransaksiDetailId}/{branchId}','DetailSparepartController@showByTransaksiDetail');
    Route::post('/detailTSp','DetailSparepartController@store');
    Route::post('/detailTSp/{tspId}','DetailSparepartController@update');
    Route::delete('/detailTSp/{tspId}','DetailSparepartController@destroy');

    Route::get('/detailTSv/{TransaksiDetailId}','DetailServiceController@showByTransaksiDetail');
    Route::post('/detailTSv','DetailServiceController@store');
    Route::post('/detailTSv/{tspId}','DetailServiceController@update');
    Route::delete('/detailTSv/{tspId}','DetailServiceController@destroy');
    Route::post('/spk/{idTransaksi}/{idDetaiTransaksi}','DetailTransaksiController@spkExport');
    Route::post('/notaLunas/{idTransaksi}','DetailTransaksiController@notaLunasExport');

    // pemesanan
    Route::get('/konfirmasiData/{pemesananId}','PemesananController@konfirmasiData');
    Route::get('/pemesanan/{id_cabang}','PemesananController@showByCabang');
    Route::get('/detailPemesanan/{pemesananId}','PemesananController@show');
    Route::delete('/sparepartBS/{supplierId}/{id_cabang}','PemesananController@destroy');
    Route::get('/supplierCabang/{id_cabang}','SparepartController@showSupplierOfCabang');
    Route::get('/sparepartBS/{supplierId}/{id_cabang}','SparepartController@showByCabangSupplier');
    Route::post('/pemesanan','DetailPemesananController@store');
    Route::post('/storetotalaccept','DetailPemesananController@storetotalaccept');
    Route::post('/cetakPemesanan','DetailPemesananController@cetakPemesanan');
});
Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', 'UserController@login');
    Route::post('/register', 'UserController@register');
});