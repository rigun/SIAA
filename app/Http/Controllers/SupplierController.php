<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Supplier;

class SupplierController extends Controller
{
    public function index()
    {
        return Supplier::orderBy('created_at','desc')->get();
    }
    public function store(Request $request){
        $this->validateWith([
            'nama' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required',
        ]);
        $pegawai = new Supplier();
        $pegawai->nama = $request->nama;
        $pegawai->alamat = $request->alamat;
        $pegawai->no_telp = $request->no_telp;
        $pegawai->save();    
        return 'Sukses';
    }
    public function update(Request $request,$id){
        $this->validateWith([
            'nama' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required',
        ]);
        $pegawai = Supplier::find($id);
        $pegawai->nama = $request->nama;
        $pegawai->alamat = $request->alamat;
        $pegawai->no_telp = $request->no_telp;
        $pegawai->save();    
        return 'Sukses';
    }
    public function destroy($id)
    {
        if($employee = Supplier::where('id',$id)->first()){
            $employee->delete();
            return response()->json(['status'=>'1','msg'=>'Data berhasil dihapus']);                
        }else{
            return response()->json(['status'=>'0','msg'=>'Data berhasil dihapus']);                
        }
    }
}
