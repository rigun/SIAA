<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pegawai;
use App\User;
use App\Role;
use App\Cabang;

class PegawaiController extends Controller
{
    public $id;

    public function getId(){
        return $this->id;
    }
    public function index()
    {
        return Pegawai::with(['user','cabang'])->orderBy('created_at','desc')->get();
    }
    public function showByBranch($branch_id)
    {
        return Pegawai::where('id_cabang',$branch_id)->with(['user'])->orderBy('created_at','desc')->get();
    }
    public function show($id)
    {
        return Pegawai::where('id',$id)->with(['detail','branch'])->first();
    }
    public function store(Request $request){
        $this->validateWith([
            'nama' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required',
            'gaji' => 'required',
            'branch' => 'required',
            'role' => 'required'
            ]);
            $id= null;
        if($b = Cabang::where('id',$request->branch)->first()){
            if($request->role != 'montir'){
                $r = Role::where('name',$request->role)->first();
                $user = new User();
                $user->username = $request->username;
                $user->password = bcrypt($request->password);
                $user->role_id = $r->id;
                $user->save();
                $id = $user->id;
            }
            $pegawai = new Pegawai();
            $pegawai->nama = $request->nama;
            $pegawai->alamat = $request->alamat;
            $pegawai->no_telp = $request->no_telp;
            $pegawai->gaji = $request->gaji;
            $pegawai->user_id = $id;
            $pegawai->id_cabang = $request->branch;
            $pegawai->save();    
            return 'Sukses';
        };
        return response()->json(['status'=>'0','msg'=>'Cabang tidak ditemukan','result' => []]);

    }
    public function update(Request $request,$id){
        $this->validateWith([
            'nama' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required',
            'gaji' => 'required',
            'role' => 'required',
            'user_id' => 'required',
        ]);
            $id= null;
            $tempId= null;
        if($b = Cabang::where('id',$request->branch)->first()){
            if($request->role != 'montir'){
                $r = Role::where('name',$request->role)->first();
                if(!$user = User::find($request->user_id)){
                    $user = new User();
                    $user->username = $request->username;
                }
                if($request->changePassword || $request->password != null){
                    $user->password = bcrypt($request->password);
                }
                $user->role_id = $r->id;
                $user->save();
                $id = $user->id;
            }
            $pegawai = Pegawai::find($request->id);
            $pegawai->nama = $request->nama;
            $pegawai->alamat = $request->alamat;
            $pegawai->no_telp = $request->no_telp;
            $pegawai->gaji = $request->gaji;
            if($id == null && $pegawai->user_id != null){
                $tempId = $pegawai->user_id;
            }else{
                $pegawai->user_id = $id;
            }
            $pegawai->save();    
            if($tempId != null){
                $deleteUser = User::find($tempId);
                $deleteUser->delete();
            }
            return 'Sukses';
        };
        return response()->json(['status'=>'0','msg'=>'Cabang tidak ditemukan','result' => []]);

    }
    public function destroy($id)
    {
        if($employee = Pegawai::where('id',$id)->first()){
            if($employee->user_id != null){
                $deleteUser = User::find($employee->user_id);
                $deleteUser->delete();
            }
            $employee->delete();
            return response()->json(['status'=>'1','msg'=>'Data berhasil dihapus']);                
        }else{
            return response()->json(['status'=>'0','msg'=>'Data berhasil dihapus']);                
        }
    }
}
