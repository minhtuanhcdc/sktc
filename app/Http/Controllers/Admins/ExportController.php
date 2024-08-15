<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\BillCustommerExport;
use App\Exports\ReportExport;
use App\Exports\vaccineExport;
use App\Exports\GeneralReport;
use App\Exports\BaocaoThuExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Auth;

class ExportController extends Controller
{
    public function exportBills(Request $request){
        //dd($request->all());
         $dateNow=Carbon::now()->format('d-m-Y');
         return Excel::download(new BillCustommerExport($request->startDate, $request->endDate,$request->id_post,$request->id_service), 'DS_KH'.$dateNow.'.xlsx');
        }
    public function exportReport(Request $request){
      //dd($request->all());
         $dateNow=Carbon::now()->format('d-m-Y');
         return Excel::download(new ReportExport($request->buoi,$request->startDate, $request->endDate,$request->id_post,$request->id_service,$request->pay), 'DS_Report'.$dateNow.'.xlsx');
        }
    public function vaccineReport(Request $request){
      //dd($request->all());
      $rowsCount=0;
      $total=0;
      $text_total='';
         $dateNow=Carbon::now()->format('d-m-Y');
         return Excel::download(new vaccineExport($request->buoi,$request->startDate, $request->endDate,$request->id_service, $rowsCount,$total,$text_total), 'DS_Vacxin_VT'.$dateNow.'.xlsx');
        }
    public function generalExport(Request $request){
         $dateNow=Carbon::now()->format('d-m-Y');
         return Excel::download(new GeneralReport($request->buoi,$request->startDate, $request->endDate,$request->id_service), 'DS_Tonghop'.$dateNow.'.xlsx');
        }
    public function BaoCaoThuExport(Request $request){
        //dd($request->all());
        $rowsCount=0;
         $dateNow=Carbon::now()->format('d-m-Y');
         return Excel::download(new BaocaoThuExport($request->buoi,$request->startDate, $request->endDate,$request->id_service,$rowsCount), 'BC_Thu'.$dateNow.'.xlsx');
        }
        
}
