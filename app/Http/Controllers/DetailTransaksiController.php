<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DetailTransaksi;
use App\DetailSparepart;
use App\DetailService;
use App\Transaksi;
use PDF;

class DetailTransaksiController extends Controller
{
    public function myTransaksi($idTransaksi){
        $Transaksi = Transaksi::where('id',$idTransaksi)->with(['cs'])->first();
        
        $detailTransaksi = DetailTransaksi::where('id_transaksi',$Transaksi->id)->with(['vehicleCustomer','montir'])->get();
        $i = 0;
        $sparepart = [];
        $service = [];
        $sparepartController = new SparepartController();
        foreach($detailTransaksi as $dt){
            $ts = DetailSparepart::where('id_detailtransaksi',$dt->id)->with('sparepart')->get();
            foreach($ts as $t){
                $sparepart[$i]['data'] = $t;
                $sparepart[$i]['position'] = $sparepartController->getPosition($t->kode_sparepart,$Transaksi->branch_id);
                $i++;
            }
            $sv = DetailService::where('id_detailtransaksi',$dt->id)->with('service')->get(); 
            foreach($sv as $s){
                $service[]= $s;
            }
        }
        return response()->json(['Transaksi'=>$Transaksi,'detailTransaksi'=>$detailTransaksi,'sparepart'=>$sparepart,'service'=>$service]);
    }

    public function notaLunasExport(Request $request, $idTransaksi){
        $this->validateWith([
            'diskon' => 'required',
            'totalServices' => 'required',
            'totalSpareparts' => 'required',
            'payment' => 'required'
        ]);
        $t = Transaksi::where('id',$idTransaksi)->first();
        $t->diskon = $request->diskon;
        $t->totalServices = $request->totalServices;
        $t->totalSpareparts = $request->totalSpareparts;
        $t->payment = $request->payment;
        $t->totalCost = $request->totalServices + $request->totalSpareparts;
        $userController = new UserController();
        $t->cashier_id = $userController->getPeopleId();
        $t->status = 3;
        $t->save();
        $Transaksi= Transaksi::where('id',$idTransaksi)->with(['customer','cs','cashier'])->first();

        $detailTransaksi = DetailTransaksi::where('id_transaksi',$Transaksi->id)->with(['vehicleCustomer','montir'])->get();
        $i = 0;
        $sparepart = [];
        $service = [];
        $customerVehicle = [];
        $sparepartController = new SparepartController();
        foreach($detailTransaksi as $dt){
            $ts = DetailSparepart::where('id_detailtransaksi',$dt->id)->with('sparepart')->get();
            foreach($ts as $t){
                $sparepart[$i]['data'] = $t;
                $sparepart[$i]['position'] = $sparepartController->getPosition($t->kode_sparepart,$Transaksi->branch_id);
                $i++;
                $sparepartController = new SparepartController();
                $sparepartController->decreaseStock($t->kode_sparepart,$t->total,$Transaksi->branch_id);
            }
            $sv = DetailService::where('id_detailtransaksi',$dt->id)->with('service')->get(); 
            foreach($sv as $s){
                $service[]= $s;
            }

            $customerVehicle[] = $dt->vehicleCustomer()->first();
        }
        view()->share(['Transaksi'=>$Transaksi,'detailTransaksi'=>$detailTransaksi,'sparepart'=>$sparepart,'service'=>$service,'customerVehicle'=> $customerVehicle]);
        $pdf = PDF::loadView('exports.notalunas');
        $pdf->setPaper([0, 0, 550, 900])->save(public_path('/files/notalunas').'/'.$Transaksi->nomor_transaksi.'-'.$Transaksi->id.'.pdf');
        return $pdf->download($Transaksi->nomor_transaksi.'-'.$Transaksi->id.'.pdf');
    }

    public function spkExport(Request $request, $idTransaksi, $idDetailTransaksi){
        $this->validateWith([
            'montir_id' => 'required',
        ]);
        $Transaksi = Transaksi::where('id',$idTransaksi)->with(['cs'])->first();
        $Transaksi->status = 1;
        $Transaksi->save();
        
        $dt = DetailTransaksi::where('id',$idDetailTransaksi)->first();
        $dt->id_montir = $request->montir_id;
        $dt->save();

        $detailTransaksi = DetailTransaksi::where('id',$idDetailTransaksi)->with(['montir','kendaraan'])->first();
        $ts = DetailSparepart::where('id_detailtransaksi',$idDetailTransaksi)->with('sparepart')->get();
        $sparepartController = new SparepartController();
        $sparepart = [];
        foreach($ts as $key => $t){
            $sparepart[$key]['data'] = $t;
            $sparepart[$key]['position'] = $sparepartController->getPosition($t->kode_sparepart,$Transaksi->id_cabang);
        }
        $service = DetailService::where('id_detailtransaksi',$idDetailTransaksi)->with('layanan')->get();
        
        view()->share(['Transaksi'=>$Transaksi,'detailTransaksi'=>$detailTransaksi,'sparepart'=>$sparepart,'service'=>$service]);
        $pdf = PDF::loadView('exports.spk');
        $pdf->setPaper([0, 0, 450, 735])->save(public_path('/files/spk').'/'.$Transaksi->nomor_transaksi.'-'.$Transaksi->id.'.pdf');
        return $pdf->download($Transaksi->nomor_transaksi.'-'.$Transaksi->id.'.pdf');
    }
    public function dataExport($idTransaksi,$idDetailTransaksi){

        $Transaksi = Transaksi::where('id',$idTransaksi)->with(['customer','cs'])->first();
        $detailTransaksi = DetailTransaksi::where('id',$idDetailTransaksi)->with(['vehicleCustomer','montir'])->first();
        $ts = DetailSparepart::where('id_detailtransaksi',$idDetailTransaksi)->with('sparepart')->get();
        $sparepartController = new SparepartController();
        $try=[];
        foreach($ts as $key => $t){
            $try[$key]['data'] = $t;
            $try[$key]['position'] = $sparepartController->getPosition($t->kode_sparepart,$Transaksi->branch_id);
        }
        $service = DetailService::where('id_detailtransaksi',$idDetailTransaksi)->with('service')->get();

        return response()->json(['Transaksi'=>$Transaksi,'detail'=>$detailTransaksi, 'sparepart'=>$try,'service'=>$service]);
    }
    public function showByTransaksi($TransaksiId){
        return DetailTransaksi::where('id_transaksi',$TransaksiId)->with('kendaraan')->get();
    }
    public function update(Request $request,$detailId){
        $this->validateWith([
            'plat_nomor' => 'required',
            'id_kendaraan' => 'required'
        ]);
        $detailTransaksi = DetailTransaksi::find($detailId);
        $detailTransaksi->id_kendaraan = $request->id_kendaraan;
        $detailTransaksi->plat_nomor = $request->plat_nomor;
        $detailTransaksi->save();

        return response()->json(['status'=>'1','msg'=>'Data berhasil dimasukkan','result' => $detailTransaksi]);                
    }
    public function destroy($DetailTransaksiId){
        $dt = DetailTransaksi::find($DetailTransaksiId);
        $dt->delete();
        return 'Berhasil';
    }
    public function myvehicle($DetailTransaksiId){
        $dt = DetailTransaksi::where('id',$DetailTransaksiId)->with('kendaraan')->first();
        return $dt;
    }
    public function store(Request $request){
        $this->validateWith([
            'plat_nomor' => 'required',
            'id_kendaraan' => 'required',
            'id_transaksi' => 'required'
        ]);
        $detailTransaksi = new DetailTransaksi();
        $detailTransaksi->id_transaksi = $request->id_transaksi;
        $detailTransaksi->id_kendaraan = $request->id_kendaraan;
        $detailTransaksi->plat_nomor = $request->plat_nomor;
        $detailTransaksi->save();

        return response()->json(['status'=>'1','msg'=>'Data berhasil dimasukkan','result' => $detailTransaksi->first()]);                
    }
}
