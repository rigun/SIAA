<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pemesanan;
use App\DetailPemesanan;
use App\CabangSparepart;
class PemesananController extends Controller
{
    public function showByCabang($cabangId)
    {
        return Pemesanan::where('id_cabang',$cabangId)->with(['supplier','detail','sales'])->get();
    }
    public function show($pemesananId)
    {
        $eventId = Pemesanan::where('id',$pemesananId)->first()->id_cabang;
        return Pemesanan::where('id',$pemesananId)->with(['supplier','detail'=> function ($query) use ($eventId) {
                $query->with(['data' => function ($query2) use ($eventId) {
                    $query2->where('id_cabang',$eventId);
                }]);
            },'sales'])->first();
    }

    public function konfirmasiData($pemesananId)
    {
        $o = Pemesanan::find($pemesananId);
        $o->status = 2;
        $o->save();
        $detail = $o->detail()->get();
        foreach($detail as $dt){
            $s = CabangSparepart::where([['id_cabang',$o->id_cabang],['kode_sparepart',$dt->kode_sparepart]])->first();
            $s->stok = $s->stok + $dt->totalDiterima;
            $s->save();
        }
        return response()->json($o);
    }
    public function destroy($supplierId,$cabangId){
        if($item = Pemesanan::where([['id_supplier',$supplierId],['id_cabang',$cabangId],['status',0]])->first()){
            if($dt = DetailPemesanan::where('id_pemesanan',$item->id)->get()){
                foreach($dt as $d){
                    $d->delete();
                }
            }
            $item->delete();
            return response()->json(['status'=>'1','msg'=>'Pemesanan berhasil dihapus','result' => $item]);
        }
        return response()->json(['status'=>'0','msg'=>'Pemesanan tidak ditemukan','result' => []]);
    }
}
