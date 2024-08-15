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


class BillCustommerExport implements FromCollection,WithMapping,WithHeadings,WithStyles,WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $startDate, $endDate,$id_post,$id_service, $index=0;
    public function __construct($startDate, $endDate,$id_post,$id_service ){
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->id_post = $id_post;
        $this->id_service = $id_service;
    }
    use Exportable;

    public function map($bill): array
    {
      
        $services ='';
        foreach($bill->catelogies as $s){
            $services .= $s->name.';';
        }
     
        return [
            ++$this->index,
            $bill->seri_bill,
            Date::stringToExcel($bill->created_at),    
            $bill->custommer->name,
            $bill->custommer->address,
         
            $services,
            $bill->usd_exchange,
            $bill->total_price,
            $bill->total_pay,  
            $bill->text_total_pay,
            $bill->posts?$bill->posts->code:' ',
        ];
    }
    public function headings():array{
        return[
            "#",
            "Số Biên lai" ,
            "Ngày",
             "Tên Đơn vị (Khách hàng)",
             "Đia chỉ",
             "Tên hàng" ,
             "Tỉ giá " ,
             "Tổng USD",
             "Tổng VNĐ" ,
             "Bằng chữ" ,
             "Bưu cục" ,
        ];
    }
    public function collection()
    { 
        $startDate = $this->startDate;
        $endDate = $this->endDate;
        $id_post = $this->id_post;
        $id_service = $this->id_service;
        $currentDate=Carbon::now();
        if($id_post && !$id_service){
            if($startDate && $endDate){
               // dd($startDate,$endDate);
                $bills=Billcustommers::with(['custommer','services','catelogies'])
                ->where('post_code',$id_post)
                ->whereDate('created_at','>=',$startDate)
                ->whereDate('created_at','<=',$endDate)
                ->get();            }
            else{
                $bills=Billcustommers::with(['custommer','services','catelogies'])
                                    ->where('post_code',$id_post)
                                    ->get();            }
        }
        if(!$id_post && $id_service){
            if($startDate && $endDate){
                $bills=Billcustommers::with(['custommer','services','catelogies'])
                    ->whereDate('created_at','>=',$startDate)
                    ->whereDate('created_at','<=',$endDate)
                    ->whereHas('catelogies', function($qr) use($id_service){
                        $qr->where('id_service',$id_service);
                    })
                ->get();            }
            else{
                $bills=Billcustommers::with(['custommer','services','catelogies'])
                ->whereHas('catelogies', function($qr) use($id_service){
                    $qr->where('id_service',$id_service);
                })->get();            }
        }
        if($id_post && $id_service){
            if($startDate & $endDate){ 
                $bills=Billcustommers::with(['custommer','services','catelogies'])
                ->where('post_code',$id_post)
                ->whereDate('created_at','>=',$startDate)
                ->whereDate('created_at','<=',$endDate)
                ->whereHas('catelogies', function($qr) use($id_service){
                    $qr->where('id_service',$id_service);
                })->get();            }
            else{
                $bills=Billcustommers::with(['custommer','services','catelogies'])
                ->where('post_code',$id_post)
                ->whereHas('catelogies', function($qr) use($id_service){
                    $qr->where('id_service',$id_service);
                })->get();            }
          
        }
        if(!$id_post && !$id_service){
            if($startDate & $endDate){
                $bills=Billcustommers::with(['custommer','services','catelogies'])
                ->whereDate('created_at','>=',$startDate)
                ->whereDate('created_at','<=',$endDate)
                ->get();            }
            else{
                $bills=Billcustommers::with(['custommer','services','catelogies'])
                ->whereDate('created_at',$currentDate)
                ->where('pay_status',1)
                ->get();            }
          
        }
        return $bills;
    }
    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_NUMBER,
           'K' => NumberFormat::FORMAT_DATE_DDMMYYYY,
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
