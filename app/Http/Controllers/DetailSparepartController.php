<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DetailSparepart;
use App\Transaksi;

class DetailSparepartController extends Controller
{
    public function showByTransaksiDetail($TransaksiDetailId,$branchId){
        $ts = DetailSparepart::where('id_detailtransaksi',$TransaksiDetailId)->with('sparepart')->get();
        $sparepartController = new SparepartController();
        $try = [];
        foreach($ts as $key => $t){
            $try[$key]['data'] = $t;
            $try[$key]['position'] = $sparepartController->getPosition($t->kode_sparepart,$branchId);
            $try[$key]['stok'] = $sparepartController->getStock($t->kode_sparepart,$branchId);
        }
        return $try;
    }
    public function store(Request $request){
        $this->validateWith([
            'id_detailtransaksi' => 'required',
            'kode_sparepart' => 'required',
            'jumlah' => 'required',
            'idCabang' => 'required'
        ]);
        $ts = new DetailSparepart();
        $ts->id_detailtransaksi = $request->id_detailtransaksi;
        $ts->kode_sparepart = $request->kode_sparepart;
        $sparepartController = new SparepartController();
        $ts->jumlah = $request->jumlah;
        $ts->harga =$sparepartController->getharga($request->kode_sparepart,$request->idCabang);
        $ts->save();
        return 'Berhasil';
    }
    public function update(Request $request, $tspId){
        $this->validateWith([
            'kode_sparepart' => 'required',
            'jumlah' => 'required'
        ]);
        $ts = DetailSparepart::find($tspId);
        $ts->kode_sparepart = $request->kode_sparepart;
        $ts->jumlah = $request->jumlah;
        $ts->save();
        return 'Berhasil';
    }
    public function destroy($tspId){
        $ts = DetailSparepart::find($tspId);
        $ts->delete();
        return 'Berhasil';
    }
}
