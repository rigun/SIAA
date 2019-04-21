<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cabang;
class CabangController extends Controller
{
  
    public function index()
    {
        return Cabang::all();
    }
    public function employeeByBranch($id){
        return Cabang::where('id',$id)->with('employee')->first();
    }
    public function store(Request $request){
        $this->validateWith([
            'nama' => 'required',
        ]);
        $item = new Cabang();
        $item->nama = $request->nama;
        $item->save();
        return 'Sukses';
    }
    public function update(Request $request,$id){
        $this->validateWith([
            'nama' => 'required',
        ]);
        $item = Cabang::where('id',$id)->first();
        $item->nama = $request->nama;
        $item->save();    
        return 'Sukses';
    }
    public function show($id){
        return Cabang::where('id',$id)->first();
    }
    public function destroy($id){
        $item = Cabang::where('id',$id)->first();
        $item->delete();
        return 'Berhasil';
    }
}
