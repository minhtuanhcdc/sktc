<?php

namespace App\Imports;

use App\Models\LengthForAgeBoy;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Validator;

use Maatwebsite\Excel\Concerns\Importable;

use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithValidation;

class LengthForAgeBoyImport implements ToCollection, WithHeadingRow
{ 
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
   {
    /*
    `month`, `L`, `M`, `S`, `SD`, `neg3SD`, `neg2SD`, `neg1SD`, `median`, `mot_SD`, `hai_SD`, `ba_SD`
    WHO: Month	L	M	S	SD	SD3neg	SD2neg	SD1neg	SD0	SD1	SD2	SD3

    */
      $a=array(); 
      foreach ($rows as $row){    
         //dd($row);
            if($row){ 
               $getCurrent = LengthForAgeBoy::Where("month",$row['month'])->first();
               if ($getCurrent) {
                  $a[] = array('month' => $getCurrent->month);
            }
            else{
               LengthForAgeBoy::insert([
                  "month"=>$row['month'],
                  "L"=>$row['l'],
                  "M"=>$row['m'],
                  "S"=>$row['s'],
                  "SD"=>$row['sd'],
                  "neg3SD"=>$row['sd3neg'],
                  "neg2SD"=>$row['sd2neg'],
                  "neg1SD"=>$row['sd1neg'],
                  "median"=>$row['sd0'],
                  "mot_SD"=>$row['sd1'],
                  "hai_SD"=>$row['sd2'],
                  "ba_SD"=>$row['sd3'],
                  "status"=>1,
               ]);
            }
            }
      }
         //dd($a);
      
   
   }
}
