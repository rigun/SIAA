<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sparepart;
use App\CabangSparepart;

class SparepartController extends Controller
{
    private $photos_path;

    public function __construct()
    {
        $this->photos_path = public_path('/images/sparepart');

    }
    public function getPrice($kode,$id){
        return CabangSparepart::where([['kode_sparepart',$kode],['id_cabang',$id]])->first()->hargaJual;
    }
    public function getPosition($kode,$id){
        return CabangSparepart::where([['kode_sparepart',$kode],['id_cabang',$id]])->first()->tempat;
    }
    public function index()
    {
        return Sparepart::all();
    }
    public function isUniqueCode(Request $request){
        if(Sparepart::where('kode',$request->kode)->exists()){
            return 1;
        }else{
            return 0;
        }
    }
    public function store(Request $request){
        $this->validateWith([
            'kode' => 'required|max:16|unique:spareparts',
            'nama' => 'required',
            'merk' => 'required',
            'tipe' => 'required',
            'id_supplier' => 'required',
            'kendaraans' => 'required',
            'filename' => 'required'
        ]);
        $item = new Sparepart();
        $item->kode = $request->kode;
        $item->nama = $request->nama;
        $item->merk = $request->merk;
        $item->tipe = $request->tipe;
        $item->id_supplier = $request->id_supplier;
        // image
        $image = $request->filename;  // your base64 encoded
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $nama = sha1(date('YmdHis') . str_random(30));
        $save_name = $nama .'.'.'png';
        \File::put($this->photos_path.'/'. $save_name, base64_decode($image));
        $item->filename = $save_name;
        // image

        $item->save();
        $item->kendaraan()->sync($request->kendaraans);
        return response()->json(['status'=>'1','msg'=>'Sparepart '.$item->nama.' berhasil dibuat','result' => $item]);
    }
    public function update(Request $request,$kode){
        $this->validateWith([
            'nama' => 'required',
            'merk' => 'required',
            'tipe' => 'required',
            'id_supplier' => 'required',
            'kendaraans' => 'required'
        ]);
        if($item = Sparepart::where('kode',$kode)->first()){
            $item->nama = $request->nama;
            $item->merk = $request->merk;
            $item->tipe = $request->tipe;
            $item->id_supplier = $request->id_supplier;
            if($request->changeImg == 1){
                $image = $request->filename;  // your base64 encoded
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $nama = sha1(date('YmdHis') . str_random(30));
                $save_name = $nama .'.'.'png';
                \File::put($this->photos_path.'/'. $save_name, base64_decode($image));
                $item->filename = $save_name;
            }
            $item->save();    
            $item->kendaraan()->sync($request->kendaraans);
            return response()->json(['status'=>'1','msg'=>'Sparepart berhasil diubah menjadi '.$item->nama,'result' => $item]);
        }
        return response()->json(['status'=>'0','msg'=>'Sparepart tidak ditemukan','result' => []]);
    }
    public function show($kode){
        if($item = Sparepart::where('kode',$kode)->first()){
            return response()->json(['status'=>'1','msg'=>'Sparepart berhasil ditemukan','result' => $item]);
        }
        return response()->json(['status'=>'0','msg'=>'Sparepart tidak ditemukan','result' => []]);
    }
    public function showBySupplier($id_supplier){
        return Sparepart::where('id_supplier',$id_supplier)->with('kendaraan')->get();
    }
    public function destroy($kode){
        if($item = Sparepart::where('kode',$kode)->first()){
            $item->kendaraan()->detach();
            $item->delete();
            return response()->json(['status'=>'1','msg'=>'Sparepart berhasil dihapus','result' => $item]);
        }
        return response()->json(['status'=>'0','msg'=>'Sparepart tidak ditemukan','result' => []]);
    }
    public function showByCabang($idCabang){
        return CabangSparepart::where('id_cabang',$idCabang)->with('sparepart')->get();
    }
    public function storeToCabang(Request $request){
        $this->validateWith([
            'kode_sparepart' => 'required',
            'id_cabang' => 'required',
            'tempat' => 'required',
            'limitStok' => 'required',
            'hargaJual' => 'required',
            'hargaBeli' => 'required',
            'stok' => 'required'
        ]);
        $item = new CabangSparepart();
        $item->kode_sparepart = $request->kode_sparepart;
        $item->id_cabang = $request->id_cabang;
        $item->tempat = $request->tempat;
        $item->limitStok = $request->limitStok;
        $item->hargaJual = $request->hargaJual;
        $item->hargaBeli = $request->hargaBeli;
        $item->stok = $request->stok;
        $item->save();
            
        return response()->json(['status'=>'1','msg'=>'Sparepart '.$item->nama.' berhasil dibuat','result' => $item]);
    }
    public function updateSpCabang(Request $request, $id){
        $this->validateWith([
            'kode_sparepart' => 'required',
            'tempat' => 'required',
            'limitStok' => 'required',
            'hargaJual' => 'required',
            'hargaBeli' => 'required',
            'stok' => 'required'
        ]);
        $item = CabangSparepart::where('id', $id)->first();
        $item->kode_sparepart = $request->kode_sparepart;
        $item->tempat = $request->tempat;
        $item->limitStok = $request->limitStok;
        $item->hargaJual = $request->hargaJual;
        $item->hargaBeli = $request->hargaBeli;
        $item->stok = $request->stok;
        $item->save();
            
        return response()->json(['status'=>'1','msg'=>'Sparepart '.$item->nama.' berhasil dibuat','result' => $item]);
    }
    public function destroyByCabang($id){
        $item = CabangSparepart::where('id', $id)->first();
        $item->delete();
            
        return 'Data berhasil dihapus';
    }
}
