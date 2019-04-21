<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JasaService;

class JasaServiceController extends Controller
{
    public function index()
    {
        return JasaService::all();
    }
    public function getPrice($id){
        return JasaService::find($id)->hargaJasa;
    }
    public function store(Request $request){
        $this->validateWith([
            'jenis' => 'required',
            'hargaJasa' => 'required',
        ]);
        $item = new JasaService();
        $item->jenis = $request->jenis;
        $item->hargaJasa = $request->hargaJasa;
        $item->save();
        return 'Sukses';
    }
    public function update(Request $request,$id){
        $this->validateWith([
            'jenis' => 'required',
            'hargaJasa' => 'required',
        ]);
        $item = JasaService::where('id',$id)->first();
        $item->jenis = $request->jenis;
        $item->hargaJasa = $request->hargaJasa;
        $item->save();    
    }
    public function show($id){
        return JasaService::where('id',$id)->first();
    }
    public function destroy($id){
        $item = JasaService::where('id',$id)->first();
        $item->delete();
        return response()->json(['status'=>'1','msg'=>'JasaService berhasil dihapus','result' => $item]);
    }
}
