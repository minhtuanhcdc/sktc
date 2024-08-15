<?php

namespace App\Exports;

use App\Models\Billservices;
use Maatwebsite\Excel\Concerns\FromCollection;


use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FormArray;
use Maatwebsite\Excel\Concerns\FormView;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Sheet;


use \Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;
use App\Models\Billcustommers;
use App\Models\Post;
use Auth;
use DB;

class BaocaoThuExport implements FromCollection,WithMapping,WithHeadings,WithStyles,WithColumnFormatting,WithColumnWidths,WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    
    protected  $buoi,$startDate, $endDate,$id_service,$rowsCount,$index=0 ;
    public function __construct($buoi,$startDate, $endDate,$id_service, $rowsCount){
      
        $this->buoi = $buoi;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->id_service = $id_service;
        $this->rowsCount = $rowsCount;
      
        
       
      
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
            $bills->bills->seri_bill,
            Date::stringToExcel($bills->bills->created_at),    
            $bills->bills->custommer->name,
            $address,
            ++$this->index,
            $bills->catelogies->medicine_name,
            $bills->catelogies->donvi_tinh,
            $bills->catelogies->don_gia,
            $bills->sl,
            $bills->catelogies->don_gia*$bills->sl,
            $bills->bills->buoi=='am'?'Sáng':'Chiều',
            $bills->bills->pay_cash==1?'TM':'CK',
            '2K23THA'.$bills->bills->seri_bill,
            //$bills->bills->user?$bills->bills->user->name:''
           
        ];
      
    }
    public function headings():array{
        return[ 
        ['Trung Tâm Kiểm Soát Bệnh Tật Thành Phố'],
        ['Phòng Tài Chính - Kế Toán'],
        ['Cơ sở: 699 Trần Hưng Đạo'],
        
        ['BÁO CÁO THU '],
        ['Ngày  Tháng   Năm  '],
        [
             "Số BL",
             "Ngày",
             "Khách Hàng" , 
             "Địa Chỉ" , 
             "STT" , 
             "Tên Hàng",
             "ĐVT",
             "ĐG" ,
             "SL" ,
             "Thành tiền" , 
             "Buổi" , 
             "HTTT" , 
             "MaTraCuu" , 
        ],
       
        
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
                            $this->rowsCount = $bills->count();
                                                 
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
            'A' => NumberFormat::FORMAT_NUMBER,
           'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
           // 'O' => NumberFormat::FORMAT_DATE_DDMMYYYY

        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' =>14,
            'B' => 11,            
            'C' => 16,            
            'D' => 18,            
            'E' => 4,            
            'F' => 12,            
            'G' => 5,            
            'H' => 7,            
            'I' => 3,            
            'J' => 8,            
            'K' => 6,            
            'L' => 5,            
            'M' => 18,            
        ];
    }
    
    protected $bills = [
        //'item_id' => 'integer',
        //'H' => 'string',
     ];
     
     public function styles(Worksheet $sheet){
        // $sheet->setPageMargin(array(
        //     0.25, 0.30, 0.25, 0.30
        // ));
     
        $sheet->mergeCells('A4:M4');
        $sheet->mergeCells('A5:M5');

        //dd($this->rowsCount+10);
        
        return[
            2=>['font'=>['bold'=>true]],
            4=> [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'font'=>['bold'=>true,'size'=>14]
                ],
            5=> [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],  
                ],
            6=>[
            'font'=>['bold'=>true,'size'=>11],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true, 
            ],
            ],
            $this->rowsCount + 8=>['font'=>['bold'=>true]],
            'C1'=>['font'=>['bold'=>true, 'italic'=>true],'color'=>'red'],
            'D' => ['alignment' => ['wrapText' => true]],
            'M' => ['alignment' => ['wrapText' => true]],
            'J' => ['alignment' => ['wrapText' => true]],
           
            
        ];
    }
    public function registerEvents(): array
    {
       
        return [
        
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet
                ->getPageSetup()
                ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                //$event->sheet->getDelegate()->getParent()->getDefaultStyle()->getFont()->setName('Times New Roman');
                $event->sheet->getStyle('A6'.':'.'M'.$this->rowsCount+6)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '#000000'],
                            ],
                        ],

                    ]);

                    $last_row =  $this->rowsCount + 8;
                    
                    $event->sheet->setCellValue(sprintf('B%d', $last_row), 'Người Lập Bảng');
                    $event->sheet->setCellValue(sprintf('E%d', $last_row), 'Kế Toán Thu');
                    $event->sheet->setCellValue(sprintf('K%d', $last_row), 'Tiêm Chủng');
                   
               }
        ];
    }
   
}
