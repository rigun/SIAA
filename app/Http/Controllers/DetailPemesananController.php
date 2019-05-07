<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DetailPemesanan;
use App\Pemesanan;
use App\CabangSparepart;
use PDF;
use App\Supplier;
use App\Sales;

class DetailPemesananController extends Controller
{
    public function showDetailPemesanan($pemesananId){
        return DetailPemesanan::where('id_pemesanan',$pemesananId)->get();
    }
    public function cetakPemesanan(Request $request){
        $this->validateWith([
            'id_supplier' => 'required',
            'id_cabang' => 'required',
            'nama' => "required",
            'no_telp' => "required",
            'alamat' => "required"
        ]);
        if(!$sales = Sales::where('no_telp',$request->no_telp)->first()){
            $sales = new Sales();
            $sales->id_supplier = $request->id_supplier;
            $sales->nama = $request->nama;
            $sales->no_telp = $request->no_telp;
            $sales->alamat = $request->alamat;
            $sales->save();
        }
        if($pemesanan = Pemesanan::where([['id_supplier',$request->id_supplier],['id_cabang',$request->id_cabang],['status',0]])->with('supplier')->first()){
            $detail = DetailPemesanan::where('id_pemesanan', $pemesanan->id)->with('sparepart')->get();
            $supplier = Supplier::find($request->id_supplier);
            view()->share(['sales'=>$sales,'order'=>$pemesanan,'detail'=>$detail, 'supplier'=>$supplier]);
            $pdf = PDF::loadView('exports.pemesanan');
            $pdf->setPaper([0, 0, 550, 900])->save(public_path('/files/pemesanan').'/'.$pemesanan->id.'-'.$sales->no_telp.'.pdf');
            $pemesanan->status = 1;
            $pemesanan->id_sales = $sales->id;
            $pemesanan->save();
            return $pdf->download($pemesanan->id.'-'.$sales->no_telp.'.pdf');
        }
        return 0;
    }
    public function store(Request $request){
        $this->validateWith([
            'id_supplier' => 'required',
            'id_cabang' => 'required',
            'kode_sparepart' => 'required',
            'satuan' => 'required',
            'total' => 'required'
        ]);
        if(!$pemesanan = Pemesanan::where([['id_supplier',$request->id_supplier],['id_cabang',$request->id_cabang],['status',0]])->first()){
            $pemesanan = new Pemesanan();
            $pemesanan->id_supplier = $request->id_supplier;
            $pemesanan->status = 0;
            $pemesanan->id_cabang = $request->id_cabang;
            $pemesanan->save();
        }
        $sp = CabangSparepart::where([['id_cabang',$request->id_cabang],['kode_sparepart',$request->kode_sparepart]])->first();
        if(!$od = DetailPemesanan::where([['id_pemesanan',$pemesanan->id],['kode_sparepart',$request->kode_sparepart]])->first()){
            $od = new DetailPemesanan();
            $od->id_pemesanan = $pemesanan->id;
        }
        $od->kode_sparepart = $request->kode_sparepart;
        $od->satuan = $request->satuan;
        $od->total = $request->total;
        $od->totalDiterima = $request->total;
        $od->hargaBeli = $sp->hargaBeli;
        $od->save();

        return response()->json(['sparepart'=>$od]);
    }
    public function storetotalaccept(Request $request){
        $this->validateWith([
            'id' => 'required',
            'totalDiterima' => 'required',
            'hargaJual' => 'required',
            'hargaBeli' => 'required',
            'sparepart_cabang' => 'required'
        ]);

        $od = DetailPemesanan::find($request->id);
        $od->totalDiterima = $request->totalDiterima;
        $od->hargaBeli = $request->hargaBeli;
        $od->save();

        $sb = CabangSparepart::find($request->sparepart_cabang);
        $sb->hargaJual = $request->hargaJual;
        $sb->hargaBeli = $request->hargaBeli;
        $sb->save();
        return response()->json(['Berhasil']);
    }
}
