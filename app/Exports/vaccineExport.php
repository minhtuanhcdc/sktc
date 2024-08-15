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
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use \Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;
use App\Models\Billcustommers;

use App\Models\Post;
use Auth;
use DB;

class vaccineExport implements FromCollection,WithMapping,WithHeadings,WithStyles,WithColumnFormatting,WithEvents,WithColumnWidths
{
     /**
    * @return \Illuminate\Support\Collection
    */
    protected $buoi,$startDate, $endDate,$id_service,$rowsCount,$total,$text_total, $index=0;
    public function __construct($buoi,$startDate, $endDate,$id_service,$rowsCount,$total, $text_total ){
      
        $this->buoi = $buoi;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->id_service = $id_service;
        $this->rowsCount = $rowsCount;
        $this->total = $total;
        $this->text_total = $text_total;
      
    }
    use Exportable;

    public function map($bills): array
    {
      //dd($bills);
        $services ='';
        $sl ='';
        // foreach($bills->catelogies as $s){
        //     $services = $s;
        // }
        // foreach($bill->services as $s){
        //     $sl .= $s->sl.' ';
        // }
        return [
           
            [
            ++$this->index, 
            $bills->catelogies->medicine_name,
            $bills->catelogies->donvi_tinh,
            number_format($bills->don_gia),
            $bills->tongSL,
            number_format($bills->don_gia*$bills->tongSL),
            ]
        ];
    }
    public function headings():array{
        return[
            ['Trung Tâm Kiểm Soát Bệnh Tật Thành Phố'],
            ['Phòng Tài Chính - Kế Toán'],
            ['Cơ sở: 699 Trần Hưng Đạo'],
            
            ['TỔNG HỢP DOANH THU - VACCINE'],
            ['Ngày  Tháng   Năm  '],
            [
            "STT",
            "Tên Hàng",
             "ĐVT",
             "ĐG" ,
             "SL" ,
             "Thành tiền" , 
        ]
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
           // dd(123);
            if($buoi){
                if($id_service){
                    if($startDate && $endDate){
                        $bills = Billservices::where('id_service',$id_service)->whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                                                    ->with([ 'catelogies','bills'])
                                                    ->whereHas('bills', function($qr) use($buoi){
                                                        $qr->where('buoi',$buoi);
                                                })
                                                ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'))
                                                ->groupBy('id_service')
                                                ->groupBy('don_gia')
                                                ->with([ 'catelogies'])
                                                ->get();
                                    $this->rowsCount=$bills->count();
                                    $this->total = Billcustommers::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)->where('buoi',$buoi)->sum('total_pay');    
                    }
                    else{
                        $bills = Billservices::where('id_service',$id_service)
                                                ->whereDate('created_at',$currentDate)
                                                ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'), DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as formatdate"))
                                                ->groupBy('id_service')
                                                ->groupBy('don_gia')
                                                ->groupBy('formatdate')
                                                ->with('catelogies')
                                                ->get();
                                                $this->rowsCount=$bills->count();
                                                $this->total = Billcustommers::whereDate('created_at','>=', $currentDate)->where('buoi',$buoi)->sum('total_pay');    
                    }
                }
                if(!$id_service){
                    if($startDate && $endDate){
                      
                        $bills = Billservices::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                                                ->with([ 'catelogies','bills'])
                                                ->whereHas('bills', function($qr) use($buoi){
                                                    $qr->where('buoi',$buoi);
                                                })
                                                ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'),
                                                )
                                                ->orderBy('id_service','desc')
                                                ->groupBy('id_service')
                                                ->groupBy('don_gia')
                                                ->get();  
                                                $this->rowsCount=$bills->count();
                                                $this->total = Billcustommers::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)->where('buoi',$buoi)->sum('total_pay');              
                    }
                    else{
                        dd(1233); 
                        $bills = Billservices::whereDate('created_at', $currentDate)
                                                ->with([ 'catelogies','bills'])
                                                ->whereHas('bills', function($qr) use($buoi){
                                                        $qr->where('buoi',$buoi);
                                                })
                                                ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'),DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as formatdate"))
                                                ->orderBy('id_service','desc')
                                                ->groupBy('id_service')
                                                ->groupBy('don_gia')
                                                ->groupBy('formatdate')
                                                ->get();
                                                $this->rowsCount=$bills->count();
                                                $this->total = Billcustommers::whereDate('created_at',$currentDate)->where('buoi',$buoi)->sum('total_pay');
                    }
    
                }  
            }
            else{ 
                if($id_service){
                    if($startDate && $endDate){
                       
                        $bills = Billservices::where('id_service',$id_service)
                                                ->whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                                                ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'))
                                                ->groupBy('id_service')
                                                ->groupBy('don_gia')
                                                ->with([ 'catelogies'])
                                                ->get();
                        $this->rowsCount=$bills->count();
                        //$this->total =  Billcustommers::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)->sum('total_pay');
                    }
                    else{
                        $bills = Billservices::where('id_service',$id_service)
                                                ->whereDate('created_at',$currentDate)
                                                ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'))
                                                ->groupBy('id_service')
                                                ->groupBy('don_gia')
                                                ->groupBy('formatdate')
                                                ->with('catelogies')
                                                ->get();
                    }
                }
                if(!$id_service){
                    if($startDate && $endDate){
                        $bills = Billservices::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                                                ->with([ 'catelogies'])
                                                ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'),
                                               // DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as formatdate")
                                                )
                                                ->orderBy('id_service','desc')
                                                ->groupBy('id_service')
                                                ->groupBy('don_gia')
                                               // ->groupBy('formatdate')
                                                ->get(); 
                                                //dd($bills);  
                        $this->rowsCount=$bills->count();
                        $this->total =  Billcustommers::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)->sum('total_pay');
                        //dd($this->total);
                        // foreach($bills as $k=>$v){
                        //     (Int) $this->total += ($v->don_gia)*$v->tongSL; 
                        // }
                       // dd($this->total);
                                    
                    }
                    else{ 
                        $bills = Billservices::whereDate('created_at', $currentDate)
                                                ->with('catelogies')
                                                ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'),DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as formatdate"))
                                                ->orderBy('id_service','desc')
                                                ->groupBy('id_service')
                                                ->groupBy('don_gia')
                                                ->groupBy('formatdate')
                                                ->get();
                                                $this->rowsCount=$bills->count();
                                                $this->total = Billcustommers::whereDate('created_at',$currentDate)->sum('total_pay');
                                            // dd($this->total);
                    }

                }    
            }
        }
        if($admin->is_admin !=1 || $admin->is_admin == null){ 
           if($buoi){
            if($id_service){
                if($startDate && $endDate){
                    $bills = Billservices::where('id_service',$id_service)
                                            ->whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                                            ->with([ 'catelogies','bills'])
                                            ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'), )
                                            ->whereHas('bills', function($qr) use($adminId){
                                                    $qr->where('user_created',$adminId);
                                            })
                                            ->whereHas('bills', function($qr) use($buoi){
                                                    $qr->where('buoi',$buoi);
                                            })
                                            ->groupBy('don_gia')
                                            ->groupBy('id_service')
                                            ->get();
                }
                else{
                    $bills = Billservices::where('id_service',$id_service)
                                            ->whereDate('created_at',$currentDate)
                                            ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'), 
                                            //DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as formatdate")
                                            )
                                            ->whereHas('bills', function($qr) use($adminId){
                                                $qr->where('user_created',$adminId);
                                            })
                                            ->whereHas('bills', function($qr) use($buoi){
                                                $qr->where('buoi',$buoi);
                                        })
                                            ->groupBy('id_service')
                                            ->groupBy('don_gia')
                                            //->groupBy('formatdate')
                                            ->with(['catelogies','bills'])
                                            ->get();
                }
            }
            if(!$id_service){
                if($startDate & $endDate){
                    $bills = Billservices::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                                            ->with([ 'catelogies','bills'])
                                            ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'), 
                                            //DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as formatdate")
                                            )
                                            ->whereHas('bills', function($qr) use($adminId){
                                                $qr->where('user_created',$adminId);
                                            })
                                            ->whereHas('bills', function($qr) use($buoi){
                                                $qr->where('buoi',$buoi);
                                        })
                                            //->groupBy('id_bill')
                                            ->groupBy('don_gia')
                                            ->groupBy('id_service')
                                            //->groupBy('formatdate')
                                            ->get();
                    
                }
                else{  
                    $bills = Billservices::whereDate('created_at', $currentDate)
                                            ->with([ 'catelogies','bills'])
                                            ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'), 
                                           // DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as formatdate")
                                            )
                                            ->whereHas('bills', function($qr) use($adminId){
                                                    $qr->where('user_created',$adminId);
                                            })
                                            ->whereHas('bills', function($qr) use($buoi){
                                                $qr->where('buoi',$buoi);
                                        })
                                            ->groupBy('don_gia')
                                            ->groupBy('id_service')
                                            //->groupBy('formatdate')
                                            ->get();
                }

            } 
           }else{ 
                if($id_service){
                    if($startDate && $endDate){
                        $bills = Billservices::where('id_service',$id_service)->whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                                                ->with([ 'catelogies','bills'])
                                                ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'), DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as formatdate"))
                                                ->whereHas('bills', function($qr) use($adminId){
                                                        $qr->where('user_created',$adminId);
                                                })
                                                ->groupBy('don_gia')
                                                ->groupBy('id_service')
                                                ->groupBy('formatdate')
                                                ->get();
                    }
                    else{
                        $bills = Billservices::where('id_service',$id_service)
                                                ->whereDate('created_at',$currentDate)
                                                ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'), DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as formatdate"))
                                                ->whereHas('bills', function($qr) use($adminId){
                                                    $qr->where('user_created',$adminId);
                                                })
                                                ->groupBy('id_service')
                                                ->groupBy('don_gia')
                                                ->groupBy('formatdate')
                                                ->with(['catelogies','bills'])
                                                ->get();
                    }
                }
                if(!$id_service){
                    if($startDate & $endDate){
                        $bills = Billservices::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                                                ->with([ 'catelogies','bills'])
                                                ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'), DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as formatdate"))
                                                ->whereHas('bills', function($qr) use($adminId){
                                                    $qr->where('user_created',$adminId);
                                                })
                                                //->groupBy('id_bill')
                                                ->groupBy('don_gia')
                                                ->groupBy('id_service')
                                                ->groupBy('formatdate')
                                                ->get();
                        
                    }
                    else{  
                        $bills = Billservices::whereDate('created_at', $currentDate)
                                                ->with([ 'catelogies','bills'])
                                                ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'), 
                                                //DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as formatdate")
                                                )
                                                ->whereHas('bills', function($qr) use($adminId){
                                                        $qr->where('user_created',$adminId);
                                                })
                                                //->groupBy('id_bill')
                                                ->groupBy('don_gia')
                                                ->groupBy('id_service')
                                                ///->groupBy('formatdate')
                                                ->get();
                                                $this->rowsCount=$bills->count();
                                                $this->total =  Billcustommers::whereDate('created_at','>=', $currentDate)->where('user_created',$adminId)->sum('total_pay');
                    }

                } 
           }
           
        }
      
        return $bills;
    }
    public function columnFormats(): array
    {
        return [
            //'G' => NumberFormat::FORMAT_NUMBER,
           'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
           // 'O' => NumberFormat::FORMAT_DATE_DDMMYYYY

        ];
    }
    protected $bills = [
        //'item_id' => 'integer',
        //'H' => 'string',
     ];
     public function columnWidths(): array
     {
         return [
             'A' =>4,
             'B' => 35,            
             'C' => 10,            
             'D' => 10,            
             'E' => 8,            
             'F' => 20,            
                 
         ];
     }
     public function styles(Worksheet $sheet){
        $getRow = $this->rowsCount+7;
        $getRowChu = $this->rowsCount+8;
      
        $sheet->mergeCells('A4:F4');
        $sheet->mergeCells('A5:F5');
        $sheet->mergeCells('A'.$getRow.':'.'F'.$getRow);
        $sheet->mergeCells('A'.$getRowChu.':'.'F'.$getRowChu);
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
           $getRow =>[
            'font'=>['bold'=>true,'size'=>11],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true, 
            ],
        ],
        $getRowChu=>['font'=>['bold'=>true]],
        

        ];
    }
    public function registerEvents(): array
    {  
        return [
            AfterSheet::class => function(AfterSheet $event) { 
                $sign_row =  $this->rowsCount + 10;
                $event->sheet->getStyle('A6'.':'.'F'.$this->rowsCount+7)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '#000000'],
                            ],
                        ],

                    ]);
                    $event->sheet->getStyle($sign_row)->applyFromArray([
                        'font' => ['bold'=>true],

                    ]);


                    $total_row =  $this->rowsCount + 7;
                    $textTotal_row =  $this->rowsCount + 8;
                   

                    $event->sheet->setCellValue(sprintf('A%d', $total_row), 'TỔNG CỘNG: '.number_format($this->total));
                    $event->sheet->setCellValue(sprintf('A%d', $textTotal_row), 'Thành tiền bằng chữ: '.$this->convert_number_to_words($this->total).' đồng');

                    $event->sheet->setCellValue(sprintf('B%d', $sign_row), 'Người Lập Bảng');
                    $event->sheet->setCellValue(sprintf('C%d', $sign_row), 'Kế Toán Thu');
                    $event->sheet->setCellValue(sprintf('F%d', $sign_row), 'Tiêm Chủng');
                   
               }
        ];
    }
    public static function convert_number_to_words($value) {
       
        $number = str_replace('.','', $value);


        $hyphen      = ' ';
		$conjunction = ' ';
		$separator   = ' ';
		$negative    = 'âm ';
		$decimal     = ' phẩy ';
		$one		 = 'mốt';
		$ten         = 'lẻ';
        $dictionary  = array(
            0                   => 'không',
            1                   => 'một',
            2                   => 'hai',
            3                   => 'ba',
            4                   => 'bốn',
            5                   => 'năm',
            6                   => 'sáu',
            7                   => 'bảy',
            8                   => 'tám',
            10                  => 'mười',
            11                  => 'mười một',
            12                  => 'mười hai',
            13                  => 'mười ba',
            14                  => 'mười bốn',
            15                  => 'mười lăm',
            16                  => 'mười sáu',
            17                  => 'mười bảy',
            18                  => 'mười tám',
            19                  => 'mười chín',
            20                  => 'hai mươi',
            30                  => 'ba mươi',
            40                  => 'bốn mươi',
            50                  => 'năm mươi',
            60                  => 'sáu mươi',
            70                  => 'bảy mươi',
            80                  => 'tám mươi',
            90                  => 'chín mươi',
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
}
