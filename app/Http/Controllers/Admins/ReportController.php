<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Catelory;
use App\Models\Custommer;
use App\Models\Billcustommers;
use App\Models\Billservices;
use App\Models\Infobase;
use App\Models\CosoModel;
use App\Models\Paraminput;
use Carbon\Carbon;
use Auth;
use DateTime;
use DB;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       //dd($request->all());
        $is_admin=false;
        $admin = Auth()->user();
        $quan= Auth()->user()->district?Auth()->user()->district:'';
        $phuong= Auth()->user()->ward?Auth()->user()->ward:'';
        $currentDate = Carbon::now()->toDateString();

        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $nam = $request->nam;
        $child_alive_I="";
        $child_alive_II="";
        $child_alive_III="";
        $child_alive_IV="";
       $boy_I='';
       $boy_II='';
       $boy_III='';
       $boy_IV='';

       $girl_I='';
       $girl_II='';
       $girl_III='';
       $girl_IV='';

       $child_25_60_I='';
       $child_0_24_I='';
       $child_tu_duoi_6_I='';

       $child_25_60_II='';
       $child_0_24_II='';
       $child_tu_duoi_6_II='';

       $child_25_60_III='';
       $child_0_24_III='';
       $child_tu_duoi_6_III='';

       $child_25_60_IV='';
       $child_0_24_IV='';
       $child_tu_duoi_6_IV='';

       $child_alive_I="";
       $child_alive_II="";
       $child_alive_III="";
       $child_alive_IV="";

       $canDo1Lan_25_60='';
       $canDo2Lan_25_60='';
       $canDo3Lan_25_60='';
      

       $tiLeCanDo1Lan_25_60='';
       $tiLeCanDo2Lan_25_60='';
       $tiLeCanDo3Lan_25_60='';
      
      
       
       $perPage = $request->perPage?$request->perPage:50;
       $termFill = $request->term;
       $thongke = $request->thongke;
       $childs='';
       $query='';
     
       $lengthForAge='';
       $weigthForAge='';

       $soSinhDuoi2500_I='';
       $soSinhDuoi2500_II='';
       $soSinhDuoi2500_III='';
       $soSinhDuoi2500_IV='';

       $tiLeDuoi2500_I='';
       $tiLeDuoi2500_II='';
       $tiLeDuoi2500_III='';
       $tiLeDuoi2500_IV='';
     
      if($thongke){
       
       $year = Carbon::now()->year;
      $getYear = $nam? $nam:$year ;
      $ngayBatDauCarbon = Carbon::parse($request->birthday);
      $soNgay = (int)($ngayBatDauCarbon->diffInDays(Carbon::now()))/30.4375;
      $month=(int)round($soNgay);
      $minMonths = 240;  // Số tháng tuổi bạn muốn so sánh
    $now = Carbon::now();  
     
            $child_alive_I = Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',1)->whereMonth('input_date','<=',3)
                        ->whereHas('childs', function ($qr) {
                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                ->havingRaw('months_lived <= ?', [12]);  // Lọc những đứa trẻ có tháng sống <=60
                        })->count();
            $child_alive_II = Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',4)->whereMonth('input_date','<=',6)
                        ->whereHas('childs', function ($qr) {
                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                ->havingRaw('months_lived <= ?', [12]);  // Lọc những đứa trẻ có tháng sống <=60
                        })->count();
            $child_alive_III = Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',7)->whereMonth('input_date','<=',9)
                        ->whereHas('childs', function ($qr) {
                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                ->havingRaw('months_lived <= ?', [12]);  // Lọc những đứa trẻ có tháng sống <=60
                        })->count();
            $child_alive_IV = Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',10)->whereMonth('input_date','<=',12)
                        ->whereHas('childs', function ($qr) {
                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                ->havingRaw('months_lived <= ?', [12]);  // Lọc những đứa trẻ có tháng sống <=60
                        })->count();

            $boy_I = Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',1)->whereMonth('input_date','<=',3)
                        ->whereHas('childs', function ($qr) {
                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                ->where('sex', 1)  // Giới tính nam (1 = male)
                                ->havingRaw('months_lived <= ?', [60]);  // Lọc những đứa trẻ có tháng sống <=60
                        })->count();
            $girl_I = Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',1)->whereMonth('input_date','<=',3)
                        ->whereHas('childs', function ($qr) {
                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                ->where('sex', 0)  // Giới tính nam (1 = male)
                                ->havingRaw('months_lived <= ?', [60]);  // Lọc những đứa trẻ có tháng sống <=60
                        })->count();
            $boy_II = Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',4)->whereMonth('input_date','<=',6)
                        ->whereHas('childs', function ($qr) {
                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                ->where('sex', 1)  // Giới tính nam (1 = male)
                                ->havingRaw('months_lived <= ?', [60]);  // Lọc những đứa trẻ có tháng sống <=60
                        })->count();
            $girl_II = Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',4)->whereMonth('input_date','<=',6)
                        ->whereHas('childs', function ($qr) {
                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                ->where('sex', 0)  // Giới tính nam (1 = male)
                                ->havingRaw('months_lived <= ?', [60]);  // Lọc những đứa trẻ có tháng sống <=60
                        })->count();

            $boy_III = Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',7)->whereMonth('input_date','<=',9)
                        ->whereHas('childs', function ($qr) {
                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                ->where('sex', 1)  // Giới tính nam (1 = male)
                                ->havingRaw('months_lived <= ?', [60]);  // Lọc những đứa trẻ có tháng sống <=60
                        })->count();
            $girl_III = Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',7)->whereMonth('input_date','<=',9)
                        ->whereHas('childs', function ($qr) {
                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                ->where('sex', 0)  // Giới tính nam (1 = male)
                                ->havingRaw('months_lived <= ?', [60]);  // Lọc những đứa trẻ có tháng sống <=60
                        })->count();

            $boy_IV = Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',10)->whereMonth('input_date','<=',12)
                        ->whereHas('childs', function ($qr) {
                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                ->where('sex', 1)  // Giới tính nam (1 = male)
                                ->havingRaw('months_lived <= ?', [60]);  // Lọc những đứa trẻ có tháng sống <=60
                        })->count();
            $girl_IV = Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',10)->whereMonth('input_date','<=',12)
                        ->whereHas('childs', function ($qr) {
                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                ->where('sex', 0)  // Giới tính nam (1 = male)
                                ->havingRaw('months_lived <= ?', [60]);  // Lọc những đứa trẻ có tháng sống <=60
                        })->count();

            
             $child_25_60_I =  Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',1)->whereMonth('input_date','<=',3)
                                            ->whereHas('childs', function ($qr) {
                                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                            ->havingRaw('months_lived BETWEEN ? AND ?', [25, 60]);
                                            })->count();
             $child_0_24_I =  Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',1)->whereMonth('input_date','<=',3)
                                            ->whereHas('childs', function ($qr) {
                                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                            ->havingRaw('months_lived BETWEEN ? AND ?', [0, 24]);
                                            })->count();
             $child_tu_duoi_6_I =  Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',1)->whereMonth('input_date','<=',3)
                                            ->whereHas('childs', function ($qr) {
                                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                            ->havingRaw('months_lived <= ?', [6]);
                                            })->count();

            $child_25_60_II =  Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',4)->whereMonth('input_date','<=',6)
                                            ->whereHas('childs', function ($qr) {
                                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                            ->havingRaw('months_lived BETWEEN ? AND ?', [25, 60]);
                                            })->count();
             $child_0_24_II =  Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',4)->whereMonth('input_date','<=',6)
                                            ->whereHas('childs', function ($qr) {
                                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                            ->havingRaw('months_lived BETWEEN ? AND ?', [0, 24]);
                                            })->count();
             $child_tu_duoi_6_II=  Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',4)->whereMonth('input_date','<=',6)
                                            ->whereHas('childs', function ($qr) {
                                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                            ->havingRaw('months_lived <= ?', [6]);
                                            })->count();

            $child_25_60_III =  Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',7)->whereMonth('input_date','<=',9)
                                            ->whereHas('childs', function ($qr) {
                                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                            ->havingRaw('months_lived BETWEEN ? AND ?', [25, 60]);
                                            })->count();
             $child_0_24_III =  Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',7)->whereMonth('input_date','<=',9)
                                            ->whereHas('childs', function ($qr) {
                                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                            ->havingRaw('months_lived BETWEEN ? AND ?', [0, 24]);
                                            })->count();
             $child_tu_duoi_6_III =  Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',7)->whereMonth('input_date','<=',9)
                                            ->whereHas('childs', function ($qr) {
                                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                            ->havingRaw('months_lived <= ?', [6]);
                                            })->count();

            $child_25_60_IV =  Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',10)->whereMonth('input_date','<=',12)
                                            ->whereHas('childs', function ($qr) {
                                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                            ->havingRaw('months_lived BETWEEN ? AND ?', [25, 60]);
                                            })->count();
             $child_0_24_IV =  Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',10)->whereMonth('input_date','<=',12)
                                            ->whereHas('childs', function ($qr) {
                                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                            ->havingRaw('months_lived BETWEEN ? AND ?', [0, 24]);
                                            })->count();
             $child_tu_duoi_6_IV =  Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',10)->whereMonth('input_date','<=',12)
                                            ->whereHas('childs', function ($qr) {
                                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                            ->havingRaw('months_lived <= ?', [6]);
                                            })->count();
 

            $canDo1Lan_25_60 = Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',10)->whereMonth('input_date','<=',12)
                                            ->whereHas('childs', function ($qr) {
                                                // Chọn thông tin từ bảng Infobase và tính số tháng sống
                                                $qr->select('id', 'birthday', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                                    ->havingRaw('months_lived BETWEEN ? AND ?', [25, 60]);
                                            })
                                            ->selectRaw('paraminputs.id_children') // Chỉ lấy id_children từ Paraminput
                                            ->groupBy('paraminputs.id_children')  // Nhóm theo id_children
                                            ->havingRaw('COUNT(*) = 1')  // Lọc nhóm có đúng 1 bản ghi
                                            ->count();
            
            
            $child_25_60 =  Paraminput::whereYear('input_date', $getYear)
                                            ->whereHas('childs', function ($qr) {
                                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                            ->havingRaw('months_lived BETWEEN ? AND ?', [25, 60]);
                                            })->count();
            if($child_25_60){
                $tiLeCanDo1Lan_25_60 = ($canDo1Lan_25_60/ $child_25_60)*100;

            }
            // $tiLeCanDo1Lan_25_60_I='';
            // $tiLeCanDo1Lan_25_60_II='';
            // $tiLeCanDo1Lan_25_60_III='';
            // $tiLeCanDo1Lan_25_60_IV='';

                                        
             $canDo2Lan_25_60_IV = Paraminput::whereYear('input_date', $getYear)->where('month','>=',25)->where('month','<=',60)
                                ->select('id_children', Paraminput::raw('count(*) as count'))->where('month','>=',25)->where('month','<=',60)
                                ->groupBy('id_children')
                                ->having('count', '=', 2) 
                                ->count();
             $canDo3Lan_25_60_I = Paraminput::whereYear('input_date', $getYear)->where('month','>=',0)->where('month','<=',24)->select('id_children', Paraminput::raw('count(*) as count'))
                        ->groupBy('id_children')
                        ->having('count', '=', 3) 
                        ->count();
                if($child_25_60_I){
                    $tileCanDoLan1_25_60=($canDo1Lan_25_60_I/$child_25_60_I)*100;

                }
                if($child_25_60_II){

                    $tileCanDoLan2_25_60=($canDo1Lan_25_60_III/$child_25_60_II)*100;
                }


             $soSinhDuoi2500_I =  Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',1)->whereMonth('input_date','<=',3)
                                                ->whereHas('childs', function ($qr) {
                                                $qr->where('weightbirth','<=',2.5)->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                                ->havingRaw('months_lived <= ?', [12]);
                                                })->count();
             $soSinhDuoi2500_II =  Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',4)->whereMonth('input_date','<=',6)
                                                ->whereHas('childs', function ($qr) {
                                                $qr->where('weightbirth','<=',2.5)->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                                ->havingRaw('months_lived <= ?', [12]);
                                                })->count();
             $soSinhDuoi2500_III =  Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',7)->whereMonth('input_date','<=',8)
                                                ->whereHas('childs', function ($qr) {
                                                $qr->where('weightbirth','<=',2.5)->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                                ->havingRaw('months_lived <= ?', [12]);
                                                })->count();
             $soSinhDuoi2500_IV =  Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',10)->whereMonth('input_date','<=',12)
                                                ->whereHas('childs', function ($qr) {
                                                $qr->where('weightbirth','<=',2.5)->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                                ->havingRaw('months_lived <= ?', [12]);
                                                })->count();
             if($child_alive_I){
                $tiLeDuoi2500_I = ($soSinhDuoi2500_I/$child_alive_I)*100;
             }
            
             $lengthForAge = Paraminput::whereYear('input_date',$getYear)
                 ->whereHas('childs', function($qr) use($year){
                $qr->where('sex',0);
             })->count();
           // dd($lengthForAge);
      }
      else{
        if($admin->is_admin!=1 || $admin->is_admin==null){
            dd(22);
            $is_admin=false;
            if (request('term')) {
                $query = Billcustommers::query();
                $query
                    ->where('seri_bill', 'like', '%' . request('term') . '%')
                    ->orwhereHas('custommer', function($qr) use($termFill){
                        $qr->where('name','like', '%' . $termFill.'%');
                    })->with('custommer')
                    ->where('id_province',$id_province)->where('pay_status',1);
            }
            //$posts=Post::where('province_code',$id_province)->select('id','name','code')->get();
            if($startDate && $endDate && !$id_service){
                    $bills=Billcustommers::where('user_created',$admin->id)
                                        ->with(['custommer','services','catelogies','posts','user'])
                                        ->whereDate('created_at','>=',$startDate)
                                        ->whereDate('created_at','<=',$endDate)
                                        ->paginate($perPage)->withQueryString();
                    $sum_price =$bills->sum('total_pay');

                    $sumTienMat = Billcustommers::where('user_created',$admin->id)
                                        ->with(['custommer','services','catelogies','posts'])
                                         ->whereDate('created_at','>=',$startDate)
                                        ->whereDate('created_at','<=',$endDate)
                                        ->where('pay_cash',1)
                                        ->sum('total_pay');                    
                    $sumChuyenKhoan = Billcustommers::where('user_created',$admin->id)
                                        ->with(['custommer','services','catelogies','posts'])
                                        ->whereDate('created_at','>=',$startDate)
                                        ->whereDate('created_at','<=',$endDate)
                                        ->where('pay_transfer',1)
                                        ->sum('total_pay');    
            }
            if($id_service && $startDate && $endDate){
                $bills=Billcustommers::where('user_created',$admin->id)->with(['custommer','services','catelogies','posts','user'])
                                        ->whereDate('created_at','>=',$startDate)
                                        ->whereDate('created_at','<=',$endDate)
                                        ->where('pay_status',1)
                                        ->whereHas('catelogies', function($qr) use($id_service){
                                            $qr->where('id_service',$id_service);
                                        })->paginate($perPage)->withQueryString();

            }
            if(!$id_service && !$startDate && !$endDate){
                //dd(123);
                    $bills=Billcustommers::where('user_created',$admin->id)
                                        ->with(['custommer','services','catelogies','posts','user'])
                                        ->whereDate('created_at',$currentDate)
                                        ->where('pay_status',1)
                                        ->paginate($perPage)->withQueryString();
                   
            }
            if($id_service && !$startDate && !$endDate){
                dd('Nhập khoản thời gian');
            } 
        }
        if($admin->is_admin==1){ 
           // dd($request->all());
            if($admin->is_admin==1){
                
                $is_admin=true;
                if($startDate && $endDate){
                    $query = Infobase::query();
                    $query
                        ->whereHas('paraminput', function($qr) use($startDate,$endDate){
                            $qr->whereDate('input_date','>=',$startDate)
                            ->whereDate('input_date','<=',$endDate);
                        });
                  }
                else{
                    $childs = Infobase::with(['paraminput','khamdinhkis','vitamins'])->paginate($perPage);
                }
            }
            else{
                dd(123);
               
            }   
        }
      }  
        $filters=[
            'perPage'=>$request->perPage,
            'startDate'=>$request->startDate,
            'endDate'=>$request->endDate,
            'thongke'=>$request->thongke,
            'danhsach'=>$request->danhsach,
            'nam'=>$request->nam,
        ];
        return Inertia::render('Report/Index',[
            //'childs'=>$childs,
            'childs'=>$query?fn() => $query->with(['paraminput','khamdinhkis','vitamins'])->orderBy('id','desc')->paginate($perPage)->withQueryString():$childs,
            'filters'=>$filters,
            'is_admin'=>$is_admin,
            'quan' => $quan,
            'phuong' => $phuong,
            'lengthForAge' => $lengthForAge,
            'boy_I' => $boy_I,
            'boy_II' => $boy_II,
            'boy_III' => $boy_III,
            'boy_IV' => $boy_IV,
            'girl_I' => $girl_I,
            'girl_II' => $girl_II,
            'girl_III' => $girl_III,
            'girl_IV' => $girl_IV,

            'child_25_60_I' => $child_25_60_I,
            'child_25_60_II' => $child_25_60_II,
            'child_25_60_III' => $child_25_60_III,
            'child_25_60_IV' => $child_25_60_IV,

            'child_0_24_I' => $child_0_24_I,
            'child_0_24_II' => $child_0_24_II,
            'child_0_24_III' => $child_0_24_III,
            'child_0_24_IV' => $child_0_24_IV,

            'child_tu_duoi_6_I' => $child_tu_duoi_6_I,
            'child_tu_duoi_6_II' => $child_tu_duoi_6_II,
            'child_tu_duoi_6_III' => $child_tu_duoi_6_III,
            'child_tu_duoi_6_IV' => $child_tu_duoi_6_IV,
           
           

            'canDo1Lan_25_60' => $canDo1Lan_25_60,
            'canDo2Lan_25_60' => $canDo2Lan_25_60,
            'canDo3Lan_25_60' => $canDo3Lan_25_60,
           
            'tiLeCanDo1Lan_25_60' => $tiLeCanDo1Lan_25_60,
            'tiLeCanDo2Lan_25_60' => $tiLeCanDo2Lan_25_60,
            'tiLeCanDo3Lan_25_60' => $tiLeCanDo3Lan_25_60,
            

            'soSinhDuoi2500_I' => $soSinhDuoi2500_I,
            'soSinhDuoi2500_II' => $soSinhDuoi2500_II,
            'soSinhDuoi2500_III' => $soSinhDuoi2500_III,
            'soSinhDuoi2500_IV' => $soSinhDuoi2500_IV,

            'child_alive_I' => $child_alive_I,
            'child_alive_II' => $child_alive_II,
            'child_alive_III' => $child_alive_III,
            'child_alive_IV' => $child_alive_IV,

            'tiLeDuoi2500_I' => $tiLeDuoi2500_I,
            'tiLeDuoi2500_II' => $tiLeDuoi2500_II,
            'tiLeDuoi2500_III' => $tiLeDuoi2500_III,
            'tiLeDuoi2500_IV' => $tiLeDuoi2500_IV,
           
            'can' => [
                'view' => Auth::user()->checkView(config('permission.access.view_report')),
                'create' => Auth::user()->checkCreate(config('permission.access.create_report')),
                'edit' => Auth::user()->checkEdit(config('permission.access.edit_report')),
               
            ],
           // 'condition_fill'=>$condition_fill
            
        ]);
    }
    public function indexVaccine(Request $request)
    {
     //dd($request->all());
        $currentDate = Carbon::now()->toDateString();
       $startDate = $request->startDate;
       $endDate = $request->endDate;
       $id_post = $request->id_coso;
       $buoi = $request->buoi;
    
       $id_service = $request->id_service;
       $perPage = $request->perPage?$request->perPage:50;
       $termFill = $request->term;
       $bills='';
       $query='';
       $sum_price="";
       $tong= '';
       $unpaid='';
       $text_price="";
       $total_pay='';
        $unconfimred='';
        $hcdcconfimred='';
        $posts="";
        $condition_fill=false;
       $isAdmin = Auth()->user();
      //dd($id_post_auth->is_admin);
       //$is_admin=false;
       //$admin = Auth()->user();
       $cosos= CosoModel::get(); 
       $buoi=$request->buoi;
       $qui=$request->qui;
   
        
         if($buoi){
            if($isAdmin->is_admin==1){ 
                if (request('term')) {
                    $query = Billcustommers::query();
                    $query
                        ->where('id', 'like', '%' . request('term') . '%')
                        ->orwhereHas('custommer', function($qr) use($termFill){
                            $qr->where('name','like', '%' . $termFill.'%');
                        })->with('custommer')
                        ->orwhereHas('posts', function($qr) use($termFill){
                            $qr->where('code','like', '%' . $termFill.'%');
                        })->with('post');
                }
                if($id_service){
                    if($startDate && $endDate){
                    
                        $bills = Billservices::where('id_service',$id_service)
                                            ->whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                                           
                                            ->whereHas('bills',function($qr) use($buoi){
                                                $qr->where('buoi',$buoi);})
                                            ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'))
                                            ->groupBy('id_service')
                                            ->groupBy('don_gia')
                                            ->with([ 'catelogies','bills'])
                                            ->paginate($perPage)->withQueryString();
                        $sum_price = Billcustommers::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                            ->where('buoi',$buoi) ->whereHas('catelogies', function($qr) use($id_service){
                                $qr->where('id_service',$id_service);})->sum('total_pay');
                    }
                    else{
                        $bills = Billservices::where('id_service',$id_service)
                                            ->whereDate('created_at',$currentDate)
                                            ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'),)
                                            ->groupBy('id_service')
                                            ->groupBy('don_gia')
                                            
                                            ->with('catelogies')
                                            ->paginate($perPage)->withQueryString();
                        $sum_price = Billcustommers::whereDate('created_at',$currentDate)
                                            ->where('buoi',$buoi)
                                            ->whereHas('catelogies', function($qr) use($id_service){
                                                $qr->where('id_service',$id_service);})
                                            ->sum('total_pay');

                    }
                }
                if(!$id_service){
                    if(($startDate !=null && $endDate!=null) && ($startDate == $endDate)){
                        $bills = Billservices::whereDate('created_at', $startDate)
                                            ->with([ 'catelogies'])
                                            ->whereHas('bills',function($qr) use($buoi){
                                                $qr->where('buoi',$buoi);
                                            })->with('bills')
                                            ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'))
                                            ->orderBy('id_service','desc')
                                            ->groupBy('id_service')
                                            ->groupBy('don_gia')
                                            ->paginate($perPage)->withQueryString();
                        $sum_price = Billcustommers::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                                                    ->where('buoi',$buoi)->sum('total_pay');
                    }
                    else{ 
                        
                        $bills = Billservices::whereDate('created_at', $currentDate)
                                            ->with([ 'catelogies','bills'])
                                            ->whereHas('bills',function($qr) use($buoi){
                                                $qr->where('buoi',$buoi);
                                            })->with('bills')
                                            ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'),)
                                           
                                            ->orderBy('id_service','desc')
                                            ->groupBy('id_service')
                                            ->groupBy('don_gia')
                                            ->paginate($perPage)->withQueryString();
                                            
                        $sum_price = Billcustommers::whereDate('created_at',$currentDate)->where('buoi',$buoi)->sum('total_pay');
                    }

                }    
            }
            if($isAdmin->is_admin !=1 || $isAdmin->is_admin == null){ 
                    if($id_service){
                        if($startDate && $endDate){
                            $bills = Billservices::where('id_service',$id_service)->whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                                                    ->with([ 'catelogies','bills'])
                                                    ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'), DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as formatdate"))
                                                    ->whereHas('bills', function($qr) use($isAdmin){
                                                            $qr->where('user_created',$isAdmin->id);
                                                    })
                                                    
                                                    ->groupBy('don_gia')
                                                    ->groupBy('id_service')
                                                    ->groupBy('formatdate')
                                                    ->paginate($perPage)->withQueryString();
                            $sum_price = Billcustommers::where('user_created',$isAdmin->id)->whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                                                    ->where('buoi',$buoi) ->whereHas('catelogies', function($qr) use($id_service){
                                                        $qr->where('id_service',$id_service);})->sum('total_pay');
                        }
                        else{
                            $bills = Billservices::where('id_service',$id_service)
                                                ->whereDate('created_at',$currentDate)
                                                ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'), DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as formatdate"))
                                                ->whereHas('bills', function($qr) use($isAdmin){
                                                    $qr->where('user_created',$isAdmin->id);
                                                })
                                                ->groupBy('id_service')
                                                ->groupBy('don_gia')
                                                ->groupBy('formatdate')
                                                ->with(['catelogies','bills'])
                                                ->paginate($perPage)->withQueryString();
                        $sum_price = Billcustommers::where('user_created',$isAdmin->id)->whereDate('created_at','>=', $currentDate)
                                                ->where('buoi',$buoi) ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);})->sum('total_pay');
                        }
                    }
                    else{
                        if($startDate & $endDate){
                            $bills = Billservices::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                                                    ->with([ 'catelogies','bills'])
                                                    ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'), 
                                                    )
                                                    ->whereHas('bills', function($qr) use($isAdmin){
                                                        $qr->where('user_created',$isAdmin->id);
                                                    })
                                                    ->groupBy('don_gia')
                                                    ->groupBy('id_service')
                                                    ->paginate($perPage)->withQueryString();
                            $sum_price = Billcustommers::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)->where('user_created',$isAdmin->id)->sum('total_pay');
                        }
                        else{ 
                            $bills = Billservices::whereDate('created_at', $currentDate)
                                                    ->with([ 'catelogies','bills'])
                                                    ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'), DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as formatdate"))
                                                    ->whereHas('bills', function($qr) use($isAdmin){
                                                            $qr->where('user_created',$isAdmin->id);
                                                    })
                                                    //->groupBy('id_bill')
                                                    ->groupBy('don_gia')
                                                    ->groupBy('id_service')
                                                    ->groupBy('formatdate')
                                                    ->paginate($perPage)->withQueryString();
                            //$sum_price = $bills->bills()->sum('total_pay');                   
                            $sum_price = Billcustommers::whereDate('created_at',$currentDate)->where('user_created',$isAdmin->id)->sum('total_pay');
                            
                        }
                    } 
            }
            if(!$isAdmin->is_admin==1 && !$isAdmin){
                    dd('Không có phép truy cập!');
            }   
         }
         else{
            if($isAdmin->is_admin==1){ 
                if (request('term')) {
                        $query = Billcustommers::query();
                        $query
                        ->where('id', 'like', '%' . request('term') . '%')
                        ->orwhereHas('custommer', function($qr) use($termFill){
                            $qr->where('name','like', '%' . $termFill.'%');
                        })->with('custommer')
                        ->orwhereHas('posts', function($qr) use($termFill){
                            $qr->where('code','like', '%' . $termFill.'%');
                        })->with('post');
                }
                if($id_service){
                    if($startDate && $endDate){
                        $bills = Billservices::where('id_service',$id_service)->whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                        ->with('bills')
                        ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'))
                        ->groupBy('id_service')
                        ->groupBy('don_gia')
                       
                        ->with([ 'catelogies','bills'])
                        ->paginate($perPage)->withQueryString();
                        $sum_price = Billcustommers::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)->sum('total_pay');
                    }
                    else{
                        $bills = Billservices::where('id_service',$id_service)
                                            ->whereDate('created_at',$currentDate)
                                            ->select('id_service','don_gia')
                                            ->groupBy('id_service')
                                            ->groupBy('don_gia')
                                            ->with('catelogies')
                                            ->paginate($perPage)->withQueryString();
                    }
                }
                if(!$id_service){
                    if($startDate && $endDate){
                        $bills = Billservices::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                                            ->with([ 'catelogies','bills'])
                                            ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'))
                                            ->orderBy('id_service','desc')
                                            ->groupBy('id_service')
                                            ->groupBy('don_gia')
                                            ->paginate($perPage)->withQueryString();
                                        
                        $sum_price = Billcustommers::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)->sum('total_pay');
                    }
                    else{ 
                       
                        $bills = Billservices::whereDate('created_at', $currentDate)
                                            ->with([ 'catelogies','bills'])
                                            ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'),
                                            )
                                            ->orderBy('id_service','desc')
                                            ->groupBy('id_service')
                                            ->groupBy('don_gia')
                                            ->paginate($perPage)->withQueryString();
                                            
                        $sum_price = Billcustommers::whereDate('created_at',$currentDate)->sum('total_pay');
                    }

                }    
            }
            if($isAdmin->is_admin !=1 || $isAdmin->is_admin == null){ 
                    if($id_service){
                        if($startDate && $endDate){
                            $bills = Billservices::where('id_service',$id_service)->whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                                                    ->with([ 'catelogies','bills'])
                                                    ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'), DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as formatdate"))
                                                    ->whereHas('bills', function($qr) use($id_post_auth){
                                                            $qr->where('user_created',$id_post_auth->id);
                                                    })
                                                    
                                                    ->groupBy('don_gia')
                                                    ->groupBy('id_service')
                                                    ->groupBy('formatdate')
                                                    ->paginate($perPage)->withQueryString();
                        }
                        else{
                            $bills = Billservices::where('id_service',$id_service)
                                                ->whereDate('created_at',$currentDate)
                                                ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'), DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as formatdate"))
                                                ->whereHas('bills', function($qr) use($id_post_auth){
                                                    $qr->where('user_created',$id_post_auth->id);
                                                })
                                                ->groupBy('id_service')
                                                ->groupBy('don_gia')
                                                ->groupBy('formatdate')
                                                ->with(['catelogies','bills'])
                                                ->paginate($perPage)->withQueryString();
                        }
                    }
                    else{
                        if($startDate & $endDate){
                            $bills = Billservices::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                                                    ->with([ 'catelogies','bills'])
                                                    ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'), DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as formatdate"))
                                                    ->whereHas('bills', function($qr) use($isAdmin){
                                                        $qr->where('user_created',$isAdmin->id);
                                                    })
                                                   
                                                    //->groupBy('id_bill')
                                                    ->groupBy('don_gia')
                                                    ->groupBy('id_service')
                                                    ->groupBy('formatdate')
                                                    ->paginate($perPage)->withQueryString();
                            $sum_price = Billcustommers::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)->where('buoi',$buoi)->where('user_created',$isAdmin->id)->sum('total_pay');
                        }
                        else{ 
                            $bills = Billservices::whereDate('created_at', $currentDate)
                                                    ->with([ 'catelogies','bills'])
                                                    ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'), DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as formatdate"))
                                                    ->whereHas('bills', function($qr) use($isAdmin){
                                                            $qr->where('user_created',$isAdmin->id);
                                                    })
                                                    //->groupBy('id_bill')
                                                    ->groupBy('don_gia')
                                                    ->groupBy('id_service')
                                                    ->groupBy('formatdate')
                                                    ->paginate($perPage)->withQueryString();
                            //$sum_price = $bills->bills()->sum('total_pay');
                                                
                            $sum_price = Billcustommers::whereDate('created_at',$currentDate)->where('user_created',$isAdmin->id)->sum('total_pay');
                            
                        }
                    } 
            }
            if(!$isAdmin->is_admin==1 && !$isAdmin){
                    dd('Không có phép truy cập!');
            }   
         }
      
    
       
        $filters=[
            'perPage'=>$request->perPage,
            'startDate'=>$request->startDate,
            'endDate'=>$request->endDate,
            'buoi'=>$request->buoi,
            'qui'=>$request->qui,
        ];
        return Inertia::render('Report/vaccineTiem',[
            'bills'=>$bills,
            'posts'=>$posts,
            'services'=>Catelory::select('id','medicine_name')->get(),
            'filters'=>$filters,
            'sum_price'=>$sum_price?$sum_price:'',
           
            'tong'=>$tong,
            'can' => [
                'view' => Auth::user()->checkView(config('permission.access.view_report')),
                'create' => Auth::user()->checkCreate(config('permission.access.create_report')),
                'edit' => Auth::user()->checkEdit(config('permission.access.edit_report')),
               
            ],
           // 'condition_fill'=>$condition_fill
            
        ]);
    }
    public function DoanhthuGiavon(Request $request){
      
        return Inertia::render('Report/ReportGiavon');
    }
   
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public static function convert_number_to_words($value) {

        $number = str_replace('.','', $value);
       // dd($test);
        // $hyphen      = ' ';//Gach noi
        // $conjunction = ' ';//lien ket
        // $separator   = ' ';//Phan cach
        // $negative    = 'negative ';
        // $decimal     = ' ngàn ';//point


        $hyphen      = ' ';
		$conjunction = ' ';
		$separator   = ' ';
		$negative    = 'âm ';
		$decimal     = ' phẩy ';
		$one		 = 'mốt';
		$ten         = 'lẻ';
        $dictionary  = array(
            0                   => 'Không',
            1                   => 'Một',
            2                   => 'Hai',
            3                   => 'Ba',
            4                   => 'Bốn',
            5                   => 'Năm',
            6                   => 'Sáu',
            7                   => 'Bảy',
            8                   => 'Tám',
            9                   => 'Chín',
            10                  => 'Mười',
            11                  => 'Mười một',
            12                  => 'Mười hai',
            13                  => 'Mười ba',
            14                  => 'Mười bốn',
            15                  => 'Mười lăm',
            16                  => 'Mười sáu',
            17                  => 'Mười bảy',
            18                  => 'Mười tám',
            19                  => 'Mười chín',
            20                  => 'Hai mươi',
            30                  => 'Ba mươi',
            40                  => 'Bốn mươi',
            50                  => 'Năm mươi',
            60                  => 'Sáu mươi',
            70                  => 'Bảy mươi',
            80                  => 'Tám mươi',
            90                  => 'Chín mươi',
            100                 => 'trăm',
            1000                => 'ngàn',
            1000000             => 'triệu',
            1000000000          => 'tỷ',
            1000000000000       => 'nghìn tỷ',
            1000000000000000    => 'ngàn triệu triệu',
            1000000000000000000 => 'tỷ tỷ'
        );
        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . Self::convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . Self::convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = Self::convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= Self::convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }
    public function confirmPay($id){
        Billcustommers::where('id',$id)->update(
            [
                'pay_unconfirm'=>1
            ]
            );
            return back()->withInput()->with('success','Xác nhận thành công successfully!');
    }
    public function tinhTongThang($ngay,$thang,$nam){
        $ngaySinh=DateTime::createFromFormat('Y-m-d',"$nam-$thang-$ngay");
        if(!$ngaySinh){
            return;
        }
        $ngayHienTai = new DateTime();
        $khoangThoiGian = $ngaySinh->diff($ngayHienTai);
        return ($khoangThoiGian->y*12 + $khoangThoiGian->m);
    }
}
