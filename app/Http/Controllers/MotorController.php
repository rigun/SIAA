<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Motor;
class MotorController extends Controller
{
    public function index()
    {
        return Motor::all();
    }
    public function store(Request $request){
        $this->validateWith([
            'merk' => 'required',
            'tipe' => 'required',
        ]);
        $item = new Motor();
        $item->merk = $request->merk;
        $item->tipe = $request->tipe;
        $item->save();
        return 'Sukses';
    }
    public function update(Request $request,$id){
        $this->validateWith([
            'merk' => 'required',
            'tipe' => 'required',
        ]);
        $item = Motor::where('id',$id)->first();
        $item->merk = $request->merk;
        $item->tipe = $request->tipe;
        $item->save();    
        return 'Sukses';
    }
    public function show($id){
        return Motor::where('id',$id)->first();
    }
    public function destroy($id){
        $item = Motor::where('id',$id)->first();
        $item->delete();
        return 'Sukses';
    }
}
