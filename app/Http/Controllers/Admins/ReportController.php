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
use App\Models\District;
use App\Models\Ward;
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
        $query='';
        $childs='';
        $wards='';
        $is_admin=false;
        $admin = Auth()->user();
        $quan= Auth()->user()->district?Auth()->user()->district:'';
        $phuong= Auth()->user()->ward?Auth()->user()->ward:'';
        $currentDate = Carbon::now()->toDateString();
        if($request->id_district){
            //dd($request->termDistrict);
            $wards=Ward::where('id_district',$request->id_district)->get();
            //dd($wards);
        }

        $startMonth = $request->startMonth;
        $endMonth = $request->endMonth;
        $nam = $request->nam;
       $perPage = $request->perPage?$request->perPage:50;
       $termFill = $request->term;
       $thongke = $request->thongke;
       $danhsach = $request->danhsach;
       $month = $request->month;
      
       $filters=[
        'perPage'=>$request->perPage,
        'startMonth'=>$request->startMonth,
        'endMonth'=>$request->endMonth,
        'thongke'=>$request->thongke,
        'danhsach'=>$request->danhsach,
        'nam'=>$request->nam,
        'month'=>$request->month,
    ];
      
    
      if($thongke){
          $data = $this->getThongKe($nam);
         return Inertia::render('Report/Index',[
            'childs'=>$childs,
            'filters'=>$filters,
            'dataFills'=>$data
         ]);
        }
                                                                                                                     
      if($danhsach){
        if(!$nam && $request->month && !($startMonth && $endMonth)){
            $query = $this->getMonth($month,$perPage);
            dd($query);
        }
        else{
            //dd(123);
            $query = $this->getDate($nam,$month,$startMonth,$endMonth,$perPage);
            //dd($query);
        }
        $childs = Infobase::with(['paraminput','khamdinhkis','vitamins'])->paginate($perPage);
        //($query);  
        return Inertia::render('Report/Index',[
            //'childs'=>$query?fn() => $query->with(['paraminput','khamdinhkis','vitamins'])->orderBy('id','desc')->paginate($perPage)->withQueryString():$childs,
            'childs'=>$query?$query:$childs,
            'filters'=>$filters,
            'is_admin'=>$is_admin,
            'quan' => $quan,
            'phuong' => $phuong,
            'districts'=>District::Where('id_province',79)->get(),
            'wards'=>$wards?$wards:'',
            'can' => [
                'view' => Auth::user()->checkView(config('permission.access.view_report')),
                'create' => Auth::user()->checkCreate(config('permission.access.create_report')),
                'edit' => Auth::user()->checkEdit(config('permission.access.edit_report')),
                
            ],
        ]);
      }  
      return Inertia::render('Report/Index',[
        'childs'=>$childs,
        'filters'=>$filters,
        'is_admin'=>$is_admin,
        'quan' => $quan,
        'phuong' => $phuong,
        
        'can' => [
            'view' => Auth::user()->checkView(config('permission.access.view_report')),
            'create' => Auth::user()->checkCreate(config('permission.access.create_report')),
            'edit' => Auth::user()->checkEdit(config('permission.access.edit_report')),
            
        ],
        
        
    ]);
       
    
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
    public function getMonth($month,$perPage){
        $results = Infobase::whereHas('paraminput', function($qr) use ($month) {
            $qr->where('month', $month);
        })
        ->with(['paraminput', 'khamdinhkis', 'vitamins']) 
        ->orderBy('id', 'desc')  
        ->paginate($perPage)  
        ->withQueryString();  
        
        return $results;
    }
    public function getDate($nam,$month,$startMonth,$endMonth,$perPage){
        $query = Infobase::query();
        
        $currentYear=Carbon::now()->year;
       
        // Apply conditions based on the presence of the variables
        if ($nam) {
            $query->whereHas('paraminput', function ($qr) use ($nam, $startMonth, $endMonth) {
                $qr->whereYear('input_date', $nam);
                if ($startMonth && $endMonth) {
                    $qr->whereMonth('input_date', '>=', $startMonth)
                       ->whereMonth('input_date', '<=', $endMonth);
                }
            });
        } elseif ($startMonth && $endMonth && !$nam) {
            // If no $nam, but $startMonth and $endMonth are provided
            $query->whereHas('paraminput', function ($qr) use ($startMonth, $endMonth,$currentYear) {
                $qr->whereYear('input_date', $currentYear)->whereMonth('input_date', '>=', $startMonth)
                   ->whereMonth('input_date', '<=', $endMonth);
            });
        } elseif ($month) {
            // If $month is provided but no $nam or $startMonth/endMonth
            $query->whereHas('paraminput', function ($qr) use ($month) {
                $qr->where('month', $month);
            });
        }
    
        $query->with(['paraminput', 'khamdinhkis', 'vitamins'])
              ->orderBy('id', 'desc');
    
        // Apply pagination and include query string for filters
        return $query->paginate($perPage)->withQueryString();
    }
    public function getThongKe($nam){
        $childs='';
        $dataFill=[ 
            'quan'=> Auth()->user()->district?Auth()->user()->district:'',
            'phuong'=>Auth()->user()->ward?Auth()->user()->ward:'',
            'child_alive_year'=>"",
            'child_alive_I'=>"",
            'child_alive_II'=>"",
            'child_alive_III'=>"",
            'child_alive_IV'=>"",
            'boy_I'=>'',
            'boy_II'=>'',
            'boy_III'=>'',
            'boy_IV'=>'',

            'girl_I'=>'',
            'girl_II'=>'',
            'girl_III'=>'',
            'girl_IV'=>'',

            'child_25_60_I'=>'',
            'child_0_24_I'=>'',
            'child_tu_duoi_6_I'=>'',

            'child_25_60_II'=>'',
            'child_0_24_II'=>'',
            'child_tu_duoi_6_II'=>'',

            'child_25_60_III'=>'',
            'child_0_24_III'=>'',
            'child_tu_duoi_6_III'=>'',

            'child_25_60_IV'=>'',
            'child_0_24_IV'=>'',
            'child_tu_duoi_6_IV'=>'',

            'child_alive_I'=>"",
            'child_alive_II'=>"",
            'child_alive_III'=>"",
            'child_alive_IV'=>"",

            'canDo1Lan_25_60'=>'',
            'canDo2Lan_25_60'=>'',
            'canDo3Lan_25_60'=>'',
            

            'tiLeCanDo1Lan_25_60'=>'',
            'tiLeCanDo2Lan_25_60'=>'',
            'tiLeCanDo3Lan_25_60'=>'',
        
        
            'lengthForAge'=>'',
            'weigthForAge'=>'',
    
            'soSinhDuoi2500_I'=>'',
            'soSinhDuoi2500_II'=>'',
            'soSinhDuoi2500_III'=>'',
            'soSinhDuoi2500_IV'=>'',
    
            'tiLeDuoi2500_I'=>'',
            'tiLeDuoi2500_II'=>'',
            'tiLeDuoi2500_III'=>'',
            'tiLeDuoi2500_IV'=>'',
    
            'suyDDdoI_I'=>"",
            'suyDDdoI_II'=>"",
            'suyDDdoI_III'=>"",
            'suyDDdoI_IV'=>"",
    
            'suyDDdoII_I'=>"",
            'suyDDdoII_II'=>"",
            'suyDDdoII_III'=>"",
            'suyDDdoII_IV'=>"",
    
            'thaoCoidoI_I'=>"",
            'thaoCoidoI_II'=>"",
            'thaoCoidoI_III'=>"",
            'thaoCoidoI_IV'=>"",
    
            'thaoCoidoII_I'=>"",
            'thaoCoidoII_II'=>"",
            'thaoCoidoII_III'=>"",
            'thaoCoidoII_IV'=>"",
    
            'sddHangThang_CN_T'=>"",
            'sddHangThang_CN_T_I'=>"",
            'sddHangThang_CN_T_II'=>"",
            'sddHangThang_CN_T_III'=>"",
            'sddHangThang_CN_T_IV'=>"",
    
            'sddHangThang_CC_T'=>"",
            'sddHangThang_CC_T_I'=>"",
            'sddHangThang_CC_T_II'=>"",
            'sddHangThang_CC_T_III'=>"",
            'sddHangThang_CC_T_IV'=>"",
        ];
       $year = Carbon::now()->year;
        $getYear = $nam? $nam:$year ;
       // $ngayBatDauCarbon = Carbon::parse($request->birthday);
       // $soNgay = (int)($ngayBatDauCarbon->diffInDays(Carbon::now()))/30.4375;
        //$month=(int)round($soNgay);
        $minMonths = 240;  // Số tháng tuổi bạn muốn so sánh
        $now = Carbon::now();  
            //CHỈ SỐ CƠ BẢN
            $dataFile['child_alive_year'] = Paraminput::whereYear('input_date', $getYear)->where('month','<=',60)->count();
            $dataFile['child_alive_I'] = Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',1)->whereMonth('input_date','<=',3)
                                        ->where('month','<=',60)->count();
            $dataFile['child_alive_II'] = Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',4)->whereMonth('input_date','<=',6)
                                        ->where('month','<=',60)->count();
            $dataFile['child_alive_III'] = Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',7)->whereMonth('input_date','<=',9)
                                            ->where('month','<=',60)->count();
            $dataFile['child_alive_IV'] = Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',10)->whereMonth('input_date','<=',12)
                                        ->where('month','<=',60)->count();

            $dataFile['boy_I'] = Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',1)->whereMonth('input_date','<=',3)
                                ->where('month','<=',60)
                                ->whereHas('childs', function ($qr) {
                                    $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                        ->where('sex', 1);  // Giới tính nam (1 = male)
                                        // Lọc những đứa trẻ có tháng sống <=60
                                })->count();
            $dataFile['girl_I'] = Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',1)->whereMonth('input_date','<=',3)
                                ->where('month','<=',60)
                                ->whereHas('childs', function ($qr) {
                                    $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                        ->where('sex', 0);   
                                })->count();
            $dataFile['boy_II'] = Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',4)->whereMonth('input_date','<=',6)->where('month','<=',60)
                        ->whereHas('childs', function ($qr) {
                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                ->where('sex', 1);
                        })->count();
            $dataFile['girl_II'] = Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',4)->whereMonth('input_date','<=',6)->where('month','<=',60)
                        ->whereHas('childs', function ($qr) {
                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                ->where('sex', 0);
                        })->count();

            $dataFile['boy_III'] = Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',7)->whereMonth('input_date','<=',9)->where('month','<=',60)
                        ->whereHas('childs', function ($qr) {
                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                ->where('sex', 1);
                        })->count();
            $dataFile['girl_III'] = Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',7)->whereMonth('input_date','<=',9)->where('month','<=',60)
                        ->whereHas('childs', function ($qr) {
                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                ->where('sex', 0) ;
                        })->count();

            $dataFile['boy_IV'] = Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',10)->whereMonth('input_date','<=',12)->where('month','<=',60)
                        ->whereHas('childs', function ($qr) {
                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                ->where('sex', 1);
                        })->count();
            $dataFile['girl_IV'] = Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',10)->whereMonth('input_date','<=',12)->where('month','<=',60)
                        ->whereHas('childs', function ($qr) {
                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                ->where('sex', 0);
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

            $dataFile['child_25_60_II'] =  Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',4)->whereMonth('input_date','<=',6)
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

            $dataFile['child_25_60_III'] =  Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',7)->whereMonth('input_date','<=',9)
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

            $dataFile['child_25_60_IV'] =  Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',10)->whereMonth('input_date','<=',12)
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
 

            //QUẢN LÝ TRẺ
            $dataFile['canDo1Lan_25_60'] = Paraminput::whereYear('input_date', $getYear)
                                            ->whereHas('childs', function ($qr) {
                                                // Chọn thông tin từ bảng Infobase và tính số tháng sống
                                                $qr->select('id', 'birthday', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                                    ->havingRaw('months_lived BETWEEN ? AND ?', [25, 60]);
                                            })
                                            ->selectRaw('paraminputs.id_children') // Chỉ lấy id_children từ Paraminput
                                            ->groupBy('paraminputs.id_children')  // Nhóm theo id_children
                                            ->havingRaw('COUNT(*) = 1')  // Lọc nhóm có đúng 1 bản ghi
                                            ->count();
            
            
            $dataFile['child_25_60'] =  Paraminput::whereYear('input_date', $getYear)
                                            ->whereHas('childs', function ($qr) {
                                            $qr->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                            ->havingRaw('months_lived BETWEEN ? AND ?', [25, 60]);
                                            })->count();
            
           
            $dataFile['canDo2Lan_25_60'] = Paraminput::whereYear('input_date', $getYear)
                                ->whereHas('childs', function ($qr) {
                                    // Chọn thông tin từ bảng Infobase và tính số tháng sống
                                    $qr->select('id', 'birthday', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                        ->havingRaw('months_lived BETWEEN ? AND ?', [25, 60]);
                                })
                                ->selectRaw('paraminputs.id_children') // Chỉ lấy id_children từ Paraminput
                                ->groupBy('paraminputs.id_children')  // Nhóm theo id_children
                                ->havingRaw('COUNT(*) = 2')  // Lọc nhóm có đúng 1 bản ghi
                                ->count();
            $dataFile['canDo3Lan_25_60'] = Paraminput::whereYear('input_date', $getYear)
                                ->whereHas('childs', function ($qr) {
                                    // Chọn thông tin từ bảng Infobase và tính số tháng sống
                                    $qr->select('id', 'birthday', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                        ->havingRaw('months_lived BETWEEN ? AND ?', [0, 24]);
                                })
                                ->selectRaw('paraminputs.id_children') // Chỉ lấy id_children từ Paraminput
                                ->groupBy('paraminputs.id_children')  // Nhóm theo id_children
                                ->havingRaw('COUNT(*) = 3')  // Lọc nhóm có đúng 1 bản ghi
                                ->count();
            if($dataFile['child_25_60']){
                    $dataFile['tiLeCanDo1Lan_25_60']=round(($dataFile['canDo1Lan_25_60']/$dataFile['child_25_60'])*100,2);

            }
            if($dataFile['child_25_60']){
                    $dataFile['tiLeCanDo2Lan_25_60']=round(($dataFile['canDo2Lan_25_60']/$dataFile['child_25_60'])*100,2);
            }
            if($dataFile['child_25_60']){
                $dataFile['tiLeCanDo3Lan_25_60']=round(($dataFile['canDo3Lan_25_60']/$dataFile['child_25_60'])*100,2);
            }

            $dataFile['soSinhDuoi2500_I'] =  Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',1)->whereMonth('input_date','<=',3)
                                            ->where('weigth','<',2.5)
                                            ->count();
            $dataFile['soSinhDuoi2500_II'] =  Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',4)->whereMonth('input_date','<=',6)
                                                ->where('weigth','<',2.5)
                                                ->count();
            $dataFile['soSinhDuoi2500_III'] =  Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',7)->whereMonth('input_date','<=',8)
                                                ->where('weigth','<',2.5)
                                                ->count();
            $dataFile['soSinhDuoi2500_IV'] =  Paraminput::whereYear('input_date', $getYear)->whereMonth('input_date','>=',10)->whereMonth('input_date','<=',12)
                                                ->where('weigth','<',2.5)
                                                ->count();
                                                // ->whereHas('childs', function ($qr) {
                                                // $qr->where('weightbirth','<=',2.5)->select('childs.*', DB::raw('TIMESTAMPDIFF(MONTH, birthday, NOW()) AS months_lived'))
                                                // ->havingRaw('months_lived <= ?', [12]);
                                                // })->count();
             if($dataFile['child_alive_I']){
                $dataFile['tiLeDuoi2500_I'] = ($dataFile['soSinhDuoi2500_I']/$dataFile['child_alive_I'])*100;
             }
            //QUẢN LÝ TRẺ SUY DINH DƯỠNG BÉO PHÌ
          
            $dataFile['suyDDdoI_I'] = Paraminput::whereYear('input_date',$getYear)->where('weigthForAge',"Suy DD độ I")->whereMonth('input_date','>=',1)->whereMonth('input_date','<=',3)
                                                ->where('month','<=',60)->count();
            
            $dataFile['suyDDdoI_II'] = Paraminput::whereYear('input_date',$getYear)->where('weigthForAge',"Suy DD độ I")->whereMonth('input_date','>=',4)->whereMonth('input_date','<=',6)
                                                ->where('month','<=',60)->count();
            $dataFile['suyDDdoI_III'] = Paraminput::whereYear('input_date',$getYear)->where('weigthForAge',"Suy DD độ I")->whereMonth('input_date','>=',7)->whereMonth('input_date','<=',9)
                                                ->where('month','<=',60)->count();
            
            $dataFile['suyDDdoI_IV'] = Paraminput::whereYear('input_date',$getYear)->where('weigthForAge',"Suy DD độ I")->whereMonth('input_date','>=',10)->whereMonth('input_date','<=',12)
                                                ->where('month','<=',60)->count();
            
            $dataFile['suyDDdoII_I'] = Paraminput::whereYear('input_date',$getYear)->where('weigthForAge',"Suy DD độ II")->whereMonth('input_date','>=',1)->whereMonth('input_date','<=',3)
                                                ->where('month','<=',60)->count();
            
            $dataFile['suyDDdoII_II'] = Paraminput::whereYear('input_date',$getYear)->where('weigthForAge',"Suy DD độ II")->whereMonth('input_date','>=',4)->whereMonth('input_date','<=',6)
                                                ->where('month','<=',60)->count();
            $dataFile['suyDDdoII_III'] = Paraminput::whereYear('input_date',$getYear)->where('weigthForAge',"Suy DD độ II")->whereMonth('input_date','>=',7)->whereMonth('input_date','<=',9)
                                                ->where('month','<=',60)->count();
            $dataFile['suyDDdoII_IV'] = Paraminput::whereYear('input_date',$getYear)->where('weigthForAge',"Suy DD độ II")->whereMonth('input_date','>=',10)->whereMonth('input_date','<=',12)
                                                ->where('month','<=',60)->count();

            $dataFile['thaoCoidoI_I'] = Paraminput::whereYear('input_date',$getYear)->where('lengthForAge',"Thấp còi độ I")->whereMonth('input_date','>=',1)->whereMonth('input_date','<=',3)
                                                ->where('month','<=',60)->count();
            $dataFile['thaoCoidoI_II'] = Paraminput::whereYear('input_date',$getYear)->where('lengthForAge',"Thấp còi độ I")->whereMonth('input_date','>=',4)->whereMonth('input_date','<=',6)
                                                ->where('month','<=',60)->count();
            $dataFile['thaoCoidoI_III'] = Paraminput::whereYear('input_date',$getYear)->where('lengthForAge',"Thấp còi độ I")->whereMonth('input_date','>=',7)->whereMonth('input_date','<=',9)
                                                ->where('month','<=',60)->count();
            $dataFile['thaoCoidoI_IV'] = Paraminput::whereYear('input_date',$getYear)->where('lengthForAge',"Thấp còi độ I")->whereMonth('input_date','>=',10)->whereMonth('input_date','<=',12)
                                                ->where('month','<=',60)->count();

            $dataFile['thaoCoidoII_I'] = Paraminput::whereYear('input_date',$getYear)->where('lengthForAge',"Thấp còi độ II")->whereMonth('input_date','>=',1)->whereMonth('input_date','<=',3)
                                                ->where('month','<=',60)->count();
            $dataFile['thaoCoidoII_II'] = Paraminput::whereYear('input_date',$getYear)->where('lengthForAge',"Thấp còi độ II")->whereMonth('input_date','>=',4)->whereMonth('input_date','<=',6)
                                                ->where('month','<=',60)->count();
            $dataFile['thaoCoidoII_III'] = Paraminput::whereYear('input_date',$getYear)->where('lengthForAge',"Thấp còi độ II")->whereMonth('input_date','>=',7)->whereMonth('input_date','<=',9)
                                                ->where('month','<=',60)->count();
            $dataFile['thaoCoidoII_IV'] = Paraminput::whereYear('input_date',$getYear)->where('lengthForAge',"Thấp còi độ II")->whereMonth('input_date','>=',10)->whereMonth('input_date','<=',12)
                                                ->where('month','<=',60)->count();   

            $dataFile['sddHangThang_CN_T'] = DB::table('paraminputs')
                                                ->select('id_children') 
                                                ->whereYear('input_date', $getYear) 
                                                ->whereIn(DB::raw('MONTH(input_date)'), [1,2,3,4,5,6,7,8,9,10,11,12]) 
                                                ->groupBy('id_children')
                                                //->havingRaw('COUNT(DISTINCT MONTH(input_date)) = 12') 
                                                ->GET()->count();  
            $dataFile['sddHangThang_CN_T_I'] = DB::table('paraminputs')
                                                ->select('id_children') 
                                                ->whereYear('input_date', $getYear) 
                                                ->whereIn(DB::raw('MONTH(input_date)'), [1,2,3]) 
                                                ->groupBy('id_children')
                                                ->havingRaw('COUNT(DISTINCT MONTH(input_date)) = 3') 
                                                ->GET()->count();  
            $dataFile['sddHangThang_CN_T_II'] = DB::table('paraminputs')
                                                ->select('id_children') 
                                                ->whereYear('input_date', $getYear) 
                                                ->whereIn(DB::raw('MONTH(input_date)'), [4,5,6]) 
                                                ->groupBy('id_children')
                                                ->havingRaw('COUNT(DISTINCT MONTH(input_date)) = 3') 
                                                ->GET()->count();  
            $dataFile['sddHangThang_CN_T_III'] = DB::table('paraminputs')
                                                ->select('id_children') 
                                                ->whereYear('input_date', $getYear) 
                                                ->whereIn(DB::raw('MONTH(input_date)'), [7,8,9]) 
                                                ->groupBy('id_children')
                                                ->havingRaw('COUNT(DISTINCT MONTH(input_date)) = 3') 
                                                ->GET()->count();
            $dataFile['sddHangThang_CN_T_IV'] = DB::table('paraminputs')
                                                ->select('id_children') 
                                                ->whereYear('input_date', $getYear) 
                                                ->whereIn(DB::raw('MONTH(input_date)'), [10,11,12]) 
                                                ->groupBy('id_children')
                                                ->havingRaw('COUNT(DISTINCT MONTH(input_date)) = 3') 
                                                ->GET()->count();
            
        return $dataFile;
                                                // return Inertia::render('Report/Index',[
                
        //                 'childs'=>$childs,
        //                 'filters'=>$filters,
        //                 //'is_admin'=>$is_admin,
        //                 'quan' => $quan,
        //                 'phuong' => $phuong,
        //                 'lengthForAge' => $lengthForAge,
        //                 'boy_I' => $boy_I,
        //                 'boy_II' => $boy_II,
        //                 'boy_III' => $boy_III,
        //                 'boy_IV' => $boy_IV,
        //                 'girl_I' => $girl_I,
        //                 'girl_II' => $girl_II,
        //                 'girl_III' => $girl_III,
        //                 'girl_IV' => $girl_IV,
            
        //                 'child_25_60_I' => $child_25_60_I,
        //                 'child_25_60_II' => $child_25_60_II,
        //                 'child_25_60_III' => $child_25_60_III,
        //                 'child_25_60_IV' => $child_25_60_IV,
            
        //                 'child_0_24_I' => $child_0_24_I,
        //                 'child_0_24_II' => $child_0_24_II,
        //                 'child_0_24_III' => $child_0_24_III,
        //                 'child_0_24_IV' => $child_0_24_IV,
            
        //                 'child_tu_duoi_6_I' => $child_tu_duoi_6_I,
        //                 'child_tu_duoi_6_II' => $child_tu_duoi_6_II,
        //                 'child_tu_duoi_6_III' => $child_tu_duoi_6_III,
        //                 'child_tu_duoi_6_IV' => $child_tu_duoi_6_IV,
                        
                        
            
        //                 'canDo1Lan_25_60' => $canDo1Lan_25_60,
        //                 'canDo2Lan_25_60' => $canDo2Lan_25_60,
        //                 'canDo3Lan_25_60' => $canDo3Lan_25_60,
                        
        //                 'tiLeCanDo1Lan_25_60' => $tiLeCanDo1Lan_25_60,
        //                 'tiLeCanDo2Lan_25_60' => $tiLeCanDo2Lan_25_60,
        //                 'tiLeCanDo3Lan_25_60' => $tiLeCanDo3Lan_25_60,
                        
            
        //                 'soSinhDuoi2500_I' => $soSinhDuoi2500_I,
        //                 'soSinhDuoi2500_II' => $soSinhDuoi2500_II,
        //                 'soSinhDuoi2500_III' => $soSinhDuoi2500_III,
        //                 'soSinhDuoi2500_IV' => $soSinhDuoi2500_IV,
            
        //                 'child_alive_year'=>$child_alive_year,
            
        //                 'child_alive_I' => $child_alive_I,
        //                 'child_alive_II' => $child_alive_II,
        //                 'child_alive_III' => $child_alive_III,
        //                 'child_alive_IV' => $child_alive_IV,
            
        //                 'tiLeDuoi2500_I' => $tiLeDuoi2500_I,
        //                 'tiLeDuoi2500_II' => $tiLeDuoi2500_II,
        //                 'tiLeDuoi2500_III' => $tiLeDuoi2500_III,
        //                 'tiLeDuoi2500_IV' => $tiLeDuoi2500_IV,
            
        //                 'suyDDdoI_I'=>$suyDDdoI_I,
        //                 'suyDDdoI_II'=>$suyDDdoI_II,
        //                 'suyDDdoI_III'=>$suyDDdoI_III,
        //                 'suyDDdoI_IV'=>$suyDDdoI_IV,
            
        //                 'suyDDdoII_I'=>$suyDDdoII_I,
        //                 'suyDDdoII_II'=>$suyDDdoII_II,
        //                 'suyDDdoII_III'=>$suyDDdoII_III,
        //                 'suyDDdoII_IV'=>$suyDDdoII_IV,
            
        //                 'thaoCoidoI_I'=> $thaoCoidoI_I,
        //                 'thaoCoidoI_II'=> $thaoCoidoI_II,
        //                 'thaoCoidoI_III'=> $thaoCoidoI_III,
        //                 'thaoCoidoI_IV'=> $thaoCoidoI_IV,
            
        //                 'thaoCoidoII_I'=> $thaoCoidoII_I,
        //                 'thaoCoidoII_II'=> $thaoCoidoII_II,
        //                 'thaoCoidoII_III'=> $thaoCoidoII_III,
        //                 'thaoCoidoII_IV'=> $thaoCoidoII_IV,
            
        //                 'sddHangThang_CN_T'=>$sddHangThang_CN_T,
        //                 'sddHangThang_CN_T_I'=>$sddHangThang_CN_T_I,
        //                 'sddHangThang_CN_T_II'=>$sddHangThang_CN_T_II,
        //                 'sddHangThang_CN_T_III'=>$sddHangThang_CN_T_III,
        //                 'sddHangThang_CN_T_IV'=>$sddHangThang_CN_T_IV,
            
        //                 'sddHangThang_CC_T'=>$sddHangThang_CC_T,
                            
                        
        //                 // 'can' => [
        //                 //     'view' => Auth::user()->checkView(config('permission.access.view_report')),
        //                 //     'create' => Auth::user()->checkCreate(config('permission.access.create_report')),
        //                 //     'edit' => Auth::user()->checkEdit(config('permission.access.edit_report')),
                            
        //                 // ],
        //                 // 'condition_fill'=>$condition_fill
                        
        //             ]);
    }
}
