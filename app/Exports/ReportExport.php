<?php

namespace App\Exports;

use App\Models\Billcustommer;
use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FormArray;
use Maatwebsite\Excel\Concerns\FormView;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Events\AfterSheet;
use \Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;
use App\Models\Billcustommers;
use App\Models\Post;
use Auth;


class ReportExport implements FromCollection,WithMapping,WithHeadings,WithStyles,WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $buoi,$startDate, $endDate,$id_post,$id_service,$pay, $index=0;
    public function __construct($buoi,$startDate, $endDate,$id_post,$id_service,$pay ){
        $this->buoi = $buoi;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->id_post = $id_post;
        $this->id_service = $id_service;
        $this->pay = $pay;
    }
    use Exportable;

    public function map($bills): array
    {
     
        $services ='';
        $sl ='';
        foreach($bills->catelogies as $s){
            $services .= $s->name.'; ';
        }
        foreach($bills->services as $s){
            $sl .= $s->sl.' ';
        }
        $add=$bills->custommer->address?$bills->custommer->address.', ':'';
        $ward = $bills->custommer->ward?$bills->custommer->ward->name.', ':'';
        $district = $bills->custommer->district?$bills->custommer->district->name.', ':'';
        $province =  $bills->custommer->province?$bills->custommer->province->name:'';
        $address = $add.$ward.$district.$province;
        return [
            ++$this->index,
            $bills->seri_bill,
            Date::stringToExcel($bills->created_at),    
            $bills->custommer->name,
            $address,
            $services.'('.($sl).')',

            $bills->total_pay,  
            $bills->text_total_pay,
            $bills->pay_cash==1?'V':'',
            $bills->pay_transfer==1?'V':'',
            $bills->buoi=='am'?'Sáng':'Chiều',
            $bills->user?$bills->user->name:'',
            $bills->cosos?$bills->cosos->name:'',
        ];
    }
    public function headings():array{
        return[
            "#",
             "Số BN" ,
             "Ngày",
             "Tên Đơn vị (Khách hàng)",
             "Đia chỉ",
             "Tên hàng" ,
             "Tổng VNĐ" ,
             "Bằng chữ" ,
             "Tiền mặt" ,
             "Chuyển khoản" ,
             "Buổi" ,
             "Người thu" ,
             "Cơ sở " ,
             
        ];
    }
    public function collection()
    { 
        $buoi = $this->buoi;
        $startDate = $this->startDate;
        $endDate = $this->endDate;
        $id_post = $this->id_post;
        $id_service = $this->id_service;
        $pay = $this->pay;
        $currentDate=Carbon::now();
        $id_post_auth = Auth()->user()->id_post;

        $post=Post::where('id',$id_post_auth)->first();
        $getPost=$post?$post->code:'';
        $id_province= Auth()->user()->posts?Auth()->user()->posts->province_code:'';
        $admin=Auth()->user();
        if($buoi){
            if($admin->is_admin !=1 || $admin->is_admin ==null){
               if($id_service){
                    if($startDate && $endDate){
                        $bills=Billcustommers::where('user_created',$admin->id)->with(['custommer','services','catelogies','posts','user'])
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->where('buoi',$buoi)
                                                ->where('pay_status',1)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })->get();
    
                    }
                    else{
                        $bills=Billcustommers::where('user_created',$admin->id)->with(['custommer','services','catelogies','posts','user'])
                                                ->whereDate('created_at',$currentDate)
                                                ->where('buoi',$buoi)
                                                ->where('pay_status',1)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })->get();
    
                    }
               }
               else{
                    if($startDate && $endDate && !$id_service){
                        $bills=Billcustommers::where('user_created',$admin->id)->where('buoi',$buoi)
                                            ->with(['custommer','services','catelogies','posts','user'])
                                            ->whereDate('created_at','>=',$startDate)
                                            ->whereDate('created_at','<=',$endDate)
                                            ->get();
                    }
                    if(!$startDate && !$endDate && !$id_service){
                        $bills=Billcustommers::where('user_created',$admin->id)->where('buoi',$buoi)
                                            ->with(['custommer','services','catelogies','posts','user'])
                                            ->whereDate('created_at',$currentDate)
                                            ->get();
                    }
               }
               
            }
            if($admin->is_admin==1){ 
                    if($id_post && !$id_service){
                        if($startDate && $endDate){
                            $bills=Billcustommers::with(['custommer','services','catelogies','user'])  
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->where('buoi',$buoi)
                                                ->where('pay_status',1)
                                                ->get();
                        }
                        else{
                            dd('Chọn thêm khoản thời gian');
                        }
                    }
                    if(!$id_post && $id_service){
                        if($startDate && $endDate){
                            $bills=Billcustommers::with(['custommer','services','catelogies','user'])
                                ->where('pay_status',1)
                                ->whereDate('created_at','>=',$startDate)
                                ->whereDate('created_at','<=',$endDate)
                                ->where('buoi',$buoi)
                                ->whereHas('catelogies', function($qr) use($id_service){
                                    $qr->where('id_service',$id_service);
                                })->with('catelogies')
                                ->get();
                           
                        }
                        else{
                            $bills=Billcustommers::with(['custommer','services','catelogies','user'])
                                                ->whereDate('created_at',$currentDate)
                                                ->where('pay_status',1)
                                                ->where('buoi',$buoi)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })
                                                ->withSum('services','billservices.sl')
                                                ->get();
                      
                        }
                    }
                    if($id_post && $id_service){
                        if($startDate & $endDate){
                        
                                $bills=Billcustommers::with(['custommer','services','catelogies','user'])
                                                    ->where('id_province',$id_post)
                                                    ->whereDate('created_at','>=',$startDate)
                                                    ->whereDate('created_at','<=',$endDate)
                                                    ->where('buoi',$buoi)
                                                    ->where('pay_status',1)
                                                    ->whereHas('catelogies', function($qr) use($id_service){
                                                        $qr->where('id_service',$id_service);
                                                    })->get();       
                           
                        }
                        else{
                         dd('Nhập khoản thời gian');
                        }
                    }
                    if(!$id_post && !$id_service){
                        if($startDate & $endDate){
                            $bills=Billcustommers::with(['custommer','services','catelogies','user'])
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->where('buoi',$buoi)
                                                ->where('pay_status',1)
                                                ->get();
                        }
                        else{
                            $bills=Billcustommers::with(['custommer','services','catelogies','user'])
                                                ->whereDate('created_at',$currentDate)
                                                ->where('pay_status',1)
                                                ->where('buoi',$buoi)
                                                ->get();
                            
                        }
                    }
            }
          }
          else{
            if($admin->is_admin!=1 || $admin->is_admin==null){
             
                if($startDate && $endDate && !$id_service){
                        $bills=Billcustommers::where('user_created',$admin->id)
                                            ->with(['custommer','services','catelogies','user'])
                                            ->whereDate('created_at','>=',$startDate)
                                            ->whereDate('created_at','<=',$endDate)
                                            ->get();
                }
                if($id_service && $startDate && $endDate){
                    $bills=Billcustommers::where('user_created',$admin->id)->with(['custommer','services','catelogies','user'])
                                            ->whereDate('created_at','>=',$startDate)
                                            ->whereDate('created_at','<=',$endDate)
                                            ->where('pay_status',1)
                                            ->whereHas('catelogies', function($qr) use($id_service){
                                                $qr->where('id_service',$id_service);
                                            })->get();
                }
                if(!$id_service && !$startDate && !$endDate){
                        $bills=Billcustommers::where('user_created',$admin->id)
                                            ->with(['custommer','services','catelogies','user'])
                                            ->whereDate('created_at',$currentDate)
                                            ->where('pay_status',1)
                                            ->get();
                }
                if($id_service && !$startDate && !$endDate){
                    dd('Nhập khoản thời gian');
                } 
            }
            if($admin->is_admin==1){ 
                if($admin->is_admin==1){
                    if($id_post && !$id_service){
                        if($startDate && $endDate){
                            $bills=Billcustommers::with(['custommer','services','catelogies','user'])
                                                ->where('id_post',$id_post)
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->where('pay_status',1)
                                                ->get();
                        }
                        else{
                            dd('Chọn thêm khoản thời gian');
                        }
                    }
                    if(!$id_post && $id_service){
                        if($startDate && $endDate){
                            $bills=Billcustommers::with(['custommer','services','catelogies','user'])
                                ->where('pay_status',1)
                                ->whereDate('created_at','>=',$startDate)
                                ->whereDate('created_at','<=',$endDate)
                                ->whereHas('catelogies', function($qr) use($id_service){
                                    $qr->where('id_service',$id_service);
                                })->with('catelogies')
                                ->get();
    
                        }
                        else{
                            $bills=Billcustommers::with(['custommer','services','catelogies','user'])
                                                ->whereDate('created_at',$currentDate)
                                                ->where('pay_status',1)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })
                                                ->withSum('services','billservices.sl')
                                                ->get();
                        }
                    }
                    if($id_post && $id_service){
                        if($startDate & $endDate){
                                $bills=Billcustommers::with(['custommer','services','catelogies','user'])
                                                    ->where('id_province',$id_post)
                                                    ->whereDate('created_at','>=',$startDate)
                                                    ->whereDate('created_at','<=',$endDate)
                                                    ->where('pay_status',1)
                                                    ->whereHas('catelogies', function($qr) use($id_service){
                                                        $qr->where('id_service',$id_service);
                                                    })->get();
                        }
                        else{
                         dd('Nhập khoản thời gian');
                        }
                    }
                    if(!$id_post && !$id_service){
                        if($startDate & $endDate){
                            $bills=Billcustommers::with(['custommer','services','catelogies','user'])
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->where('pay_status',1)
                                                ->get();
                        }
                        else{
                            $bills=Billcustommers::with(['custommer','services','catelogies','user'])
                                                ->whereDate('created_at',$currentDate)
                                                ->where('pay_status',1)
                                                ->get();
                        }
                    }
                }
                else{
                    dd('Không có phép truy cập!');
                }   
            }
          }
        return $bills;
    }
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER,
            'C' => NumberFormat::FORMAT_DATE_DDMMYYYY,
           // 'O' => NumberFormat::FORMAT_DATE_DDMMYYYY

        ];
    }
    protected $bills = [
        //'item_id' => 'integer',
        //'H' => 'string',
     ];
     public function styles(Worksheet $sheet){
        return[
           1=>['font'=>['bold'=>true]],
            'C1'=>['font'=>['bold'=>true, 'italic'=>true],'color'=>'red'],

        ];
    }
}
