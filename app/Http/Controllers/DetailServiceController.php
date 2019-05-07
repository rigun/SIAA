<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DetailService;

class DetailServiceController extends Controller
{
    public function showByTransaksiDetail($idTransaksi){
        return DetailService::where('id_detailtransaksi',$idTransaksi)->with('layanan')->get();
    }
    public function store(Request $request){
        $this->validateWith([
            'id_detailtransaksi' => 'required',
            'id_service' => 'required',
            'jumlah' => 'required'
        ]);
        $ts = new DetailService();
        $ts->id_detailtransaksi = $request->id_detailtransaksi;
        $ts->id_service = $request->id_service;
        $serviceController = new JasaServiceController();
        $ts->jumlah = $request->jumlah;
        $ts->harga = $serviceController->getPrice($request->id_service);
        $ts->save();
        return 'Berhasil';
    }
    public function update(Request $request, $tspId){
        $this->validateWith([
            'id_service' => 'required',
            'jumlah' => 'required'
        ]);
        $ts = DetailService::find($tspId);
        $ts->id_service = $request->id_service;
        $ts->jumlah = $request->jumlah;
        $ts->save();
        return 'Berhasil';
    }
    public function destroy($tspId){
        $ts = DetailService::find($tspId);
        $ts->delete();
        return 'Berhasil';
    }
}
