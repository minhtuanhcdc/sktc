<?php

namespace App\Exports;

use App\Models\Billservices;
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
use DB;

class GeneralReport implements FromCollection,WithMapping,WithHeadings,WithStyles,WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $buoi,$startDate, $endDate,$id_service, $index=0;
    public function __construct($buoi,$startDate, $endDate,$id_service ){
      
        $this->buoi = $buoi;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->id_service = $id_service;
      
    }
    use Exportable;

    public function map($bills): array
    {
        $add=$bills->bills->custommer->address?$bills->bills->custommer->address.', ':'';
        $ward = $bills->bills->custommer->ward?$bills->bills->custommer->ward->name.', ':'';
        $district = $bills->bills->custommer->district?$bills->bills->custommer->district->name.', ':'';
        $province =  $bills->bills->custommer->province?$bills->bills->custommer->province->name:'';
        $address = $add.$ward.$district.$province;
        return [
            ++$this->index,
            $bills->bills->seri_bill,
            Date::stringToExcel($bills->bills->created_at),    
            $bills->catelogies->code,
            $bills->catelogies->name,
            $bills->catelogies->donvi_tinh,
            $bills->catelogies->don_gia,
            $bills->sl,
            $bills->catelogies->don_gia*$bills->sl,
            $bills->bills->pay_cash==1?'TM':'CK',
            $bills->bills->custommer->name,
            $address,
            $bills->bills->buoi=='am'?'Sáng':'Chiều',
            '2K23THA'.$bills->bills->seri_bill,
            $bills->bills->user?$bills->bills->user->name:''
           
        ];
    }
    public function headings():array{
        return[
            "#",
             "Số BL",
             "Ngày",
             "Mã hàng",
             "Vacxin - Vật tư",
             "ĐVT",
             "ĐG" ,
             "SL" ,
             "Thành tiền" , 
             "HTTP" , 
             "Khách hàng" , 
             "ĐC" , 
             "Buổi" , 
             "MaTraCuu" , 
             "Thu ngân" , 
        ];
    }
    public function collection()
    { 
       
      
        $buoi = $this->buoi;
        $startDate = $this->startDate;
        $endDate = $this->endDate;
        $id_service = $this->id_service;

        $currentDate = Carbon::now()->toDateString();
        $admin=Auth()->user();
        $adminId=$admin->id;
        if($admin->is_admin==1){ 
                 if($id_service){
                    if($startDate && $endDate){
                         if($buoi){
                             $bills = Billservices::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                             ->with([ 'catelogies','bills'])
                             ->whereHas('bills', function($qr) use($buoi){
                                     $qr->where('buoi',$buoi);
                             })
                             ->whereHas('catelogies', function($qr) use($id_service){
                                 $qr->where('id_service',$id_service);
                             })
                             ->orderBy('id_bill','desc')
                             ->get();
                         }
                         else{
                             $bills = Billservices::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                                                         ->with([ 'catelogies','bills'])
                                                         ->whereHas('catelogies', function($qr) use($id_service){
                                                                 $qr->where('id',$id_service);
                                                         })
                                                         ->orderBy('id_bill','desc')
                                                         ->get();
                         }   
                    }
                    else{
                         $bills = Billservices::where('id_service',$id_service)->whereDate('created_at',$currentDate)
                                             ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'))
                                             ->groupBy('id_service')
                                             ->groupBy('don_gia')
                                             ->with('catelogies')
                                             ->get();
                    }
                 }
                 else{
                     if($buoi){
                         if($startDate && $endDate){
                             $bills = Billservices::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=', $endDate)
                                     ->with([ 'catelogies','bills'])
                                     ->whereHas('bills', function($qr) use($buoi){
                                             $qr->where('buoi',$buoi);
                                     })
                                     ->orderBy('id_bill','desc')
                                     ->get();
                         }
                         else{
                             $bills = Billservices::whereDate('created_at', $currentDate)
                                                 ->with([ 'catelogies','bills'])
                                                 ->whereHas('bills', function($qr) use($buoi){
                                                         $qr->where('buoi',$buoi);
                                                 })
                                                 ->orderBy('id_bill','desc')
                                                 ->get();
                         }  
                     }
                     else{ 
                         if($startDate && $endDate){
                             $bills = Billservices::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=', $endDate)
                                                 ->with([ 'catelogies','bills'])
                                                 ->orderBy('id_bill','desc')
                                                 ->get();
                         }
                         else{
                             $bills = Billservices::whereDate('created_at', $currentDate)
                                                 ->with([ 'catelogies','bills'])
                                                
                                                 ->orderBy('id_bill','desc')
                                                 ->get();
                         }
                     }
                 }
         }
        if($admin->is_admin !=1 || $admin->is_admin ==null){ 
                 if($id_service){
                     if($startDate && $endDate){
                         if($buoi){
                             $bills = Billservices::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                                                 ->with([ 'catelogies','bills'])
                                                 ->whereHas('bills', function($qr) use($buoi){
                                                         $qr->where('buoi',$buoi);
                                                 })
                                                 ->whereHas('catelogies', function($qr) use($id_service){
                                                     $qr->where('id_service',$id_service);
                                                 })
                                                 ->whereHas('bills', function($qr) use($adminId){
                                                     $qr->where('user_created',$adminId);
                                                 })
                                                 ->orderBy('id_bill','desc')
                                                 ->get();
                         }
                         else{
                             $bills = Billservices::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                                                         ->with([ 'catelogies','bills'])
                                                         ->whereHas('catelogies', function($qr) use($id_service){
                                                                 $qr->where('id',$id_service);
                                                         })
                                                         ->whereHas('bills', function($qr) use($adminId){
                                                             $qr->where('user_created',$adminId);
                                                         })
                                                         ->orderBy('id_bill','desc')
                                                         ->get();
                         }     
                     }
                     else{
                         $bills = Billservices::whereDate('created_at',$currentDate)
                                                ->with([ 'catelogies','bills'])
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                    })
                                                ->whereHas('bills', function($qr) use($adminId){
                                                    $qr->where('user_created',$adminId);
                                                    })
                                                ->orderBy('id_bill','desc')
                                                ->get();
                     }
                 }
                 if(!$id_service){
                    if($buoi){
                        if($startDate && $endDate){
                            $bills = Billservices::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=', $endDate)
                                    ->with([ 'catelogies','bills'])
                                    ->whereHas('bills', function($qr) use($buoi){
                                            $qr->where('buoi',$buoi);
                                    })
                                    ->whereHas('bills', function($qr) use($adminId){
                                        $qr->where('user_created',$adminId);
                                
                                })
                                    ->orderBy('id_bill','desc')
                                    ->get();
                        }
                        else{
                            $bills = Billservices::whereDate('created_at', $currentDate)
                                                ->with([ 'catelogies','bills'])
                                                ->whereHas('bills', function($qr) use($buoi){
                                                        $qr->where('buoi',$buoi);
                                                })
                                                ->whereHas('bills', function($qr) use($adminId){
                                                    $qr->where('user_created',$adminId);
                                            })
                                                ->orderBy('id_bill','desc')
                                                ->get();
                        }
                    }
                    else{ 
                        if($startDate && $endDate){
                            $bills = Billservices::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=', $endDate)
                                    ->with([ 'catelogies','bills'])
                                    ->whereHas('bills', function($qr) use($adminId){
                                        $qr->where('user_created',$adminId);
                                
                                })
                                    ->orderBy('id_bill','desc')
                                    ->get();
                        }
                        else{
                            $bills = Billservices::whereDate('created_at', $currentDate)
                                                ->with([ 'catelogies','bills'])
                                                ->whereHas('bills', function($qr) use($adminId){
                                                    $qr->where('user_created',$adminId);
                                                    })
                                                ->orderBy('id_bill','desc')
                                                ->get();
                        }
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
