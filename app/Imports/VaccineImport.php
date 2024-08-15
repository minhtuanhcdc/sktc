<?php

namespace App\Imports;

use App\Models\Catelory;
use Maatwebsite\Excel\Concerns\ToModel;

use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Validator;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class VaccineImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
    
        foreach ($rows as $row)
        {
            if($row){ 
                $getVaccine = Catelory::where('code',$row['ma'])->first();
                if($getVaccine){
                    Catelory::where('code',$getVaccine->code)->update([
                        //`code`, `name`, `origin`, `medicine_name`, `id_group`, `donvi_tinh`, `origin_price`, `don_gia`,
                        'name'=>$row['loai_vaccine'],
                        'origin'=>$row['xuat_xu'],
                        'medicine_name'=>$row['ten_thuoc'],
                        'donvi_tinh'=>$row['donvi_tinh'],
                        'don_gia'=>$row['don_gia'],
                        'id_group'=>$row['nhom'],
                    ]);
                }
                else{
                    Catelory::Insert([
                        'code'=>$row['ma'],
                        'name'=>$row['loai_vaccine'],
                        'origin'=>$row['xuat_xu'],
                        'medicine_name'=>$row['ten_thuoc'],
                        'donvi_tinh'=>$row['donvi_tinh'],
                        'don_gia'=>$row['don_gia'],
                        'id_group'=>$row['nhom'],
                    ]);
                }   
            }
      }
   }
   public function rules(): array
   {
       return [
           //'name'=>'required',
          // 'hpv_code'=>'unique:results,hpv_code',
        //'element_id'=>'unique:results,element_id',

       ];
   }
}
