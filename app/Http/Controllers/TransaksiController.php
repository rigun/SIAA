<?php

namespace App\Http\Controllers;

use App\Transaksi;
use App\Cabang;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function destroy($id){
        $Transaksi = Transaksi::find($id);
        $Transaksi->delete();
        return 'Berhasil';
    }
    public function index(){
        return Transaksi::where('status',3)->with(['customer','cashier','cs','branch','detail'])->orderBy('created_at','desc')->get();
    }
    public function showByBranch($id_cabang){
        return Transaksi::where('id_cabang',$id_cabang)->orderBy('created_at','desc')->get();
    }
    public function updateStatus(Request $request, $id){
        
        $this->validateWith([
            'status' => 'required'
        ]);
        $t = Transaksi::find($id);
        if($request->status == 1){
            $t->status = 2;
        }else{
            $t->status = 1;
        }
        $t->save();
        return 'Berhasil';
    }

    public function store(Request $request){
        $this->validateWith([
            'idCabang' => 'required',
            'jenisTransaksi' => 'required',
            'namaKonsumen' => 'required',
            'alamatKonsumen' => 'required',
            'nomorKonsumen' => 'required',
            ]);

        if($b = Cabang::where('id',$request->idCabang)->first()){
            $Transaksi = new Transaksi();
            $Transaksi->nomor_transaksi = $request->jenisTransaksi.'-'.date("dmy");
            $Transaksi->id_cabang = $request->idCabang;
            $userController = new UserController();
            $Transaksi->idCS = $userController->getPegawaiId();
            $Transaksi->namaKonsumen = $request->namaKonsumen;
            $Transaksi->alamatKonsumen = $request->alamatKonsumen;
            $Transaksi->nomorKonsumen = $request->nomorKonsumen;
            $Transaksi->save();
            return response()->json(['status'=>'1','msg'=>'Data berhasil dimasukkan','result' => $Transaksi->first()]);                
        };
        return response()->json(['status'=>'0','msg'=>'Cabang tidak ditemukan','result' => []]);
    }
    public function update(Request $request, $id){
        $this->validateWith([
            'jenisTransaksi' => 'required'
        ]);

        $Transaksi = Transaksi::find($id);
        $Transaksi->namaKonsumen = $request->namaKonsumen;
        $Transaksi->alamatKonsumen = $request->alamatKonsumen;
        $Transaksi->nomorKonsumen = $request->nomorKonsumen;
        $Transaksi->nomor_transaksi = $request->jenisTransaksi.'-'.date("dmy");
        $Transaksi->save();
        return response()->json(['status'=>'1','msg'=>'Data berhasil dimasukkan','result' => $Transaksi->first()]);                
    }
}
