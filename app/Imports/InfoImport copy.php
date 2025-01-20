<?php

namespace App\Imports;

use App\Models\Infobase;
use App\Models\Paraminput;
use App\Models\Vitamin;
use App\Models\Khamdinhky;
use App\Models\LengthForAgeBoy;
use App\Models\LengthForAgeGirl;
use App\Models\WeightForAgeBoy;
use App\Models\WeightForAgeGirl;
use App\Models\WeightForHeightBoy;
use App\Models\WeightForHeightGirl;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

use PHPUnit\Exception;

class InfoImport implements ToCollection, WithHeadingRow
{
    public $duplicates = [];
    public function collection(Collection $rows)
   {
     
     foreach ($rows as $row){ 
           
            DB::transaction(function() use($row){ 
                $id_user = Auth()->user()->id;
                $birthDay = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['ngaysinh'])->format('Y-m-d');
                $ngayCanDo = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['ngaycando'])->format('Y-m-d');
                $ngayBatDauCarbon = Carbon::parse($birthDay);
               $soNgay = (int)($ngayBatDauCarbon->diffInDays(Carbon::now()))/30.4375;
               $month=(int)round($soNgay);

                if($row['cannang'] && $row['chieucao']){
                  $BMI=round($row['cannang']*10000/($row['chieucao']*$row['chieucao']),2);
                }
                $id_children="";
                $lengthForAge="";
                $weightforLength="";
                $weightforAge="";
                $getHightforAge="";
                $getWeigthforAge="";
                $getWeightforLength="";
                $existingChild  = Infobase::Where('name',$row['tentre'])->where('parent',$row['tenme'])->where('id_ward',$row['maphuong'])
                                           ->where('birthday',\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['ngaysinh'])->format('Y-m-d'))->first(); 

            if(!$existingChild &&($row['cannang'] && $row['chieucao'])){ 
               if($row['gioitinh'] == 1){
                        $getHightforAge=LengthForAgeBoy::where('month', $month)->first();
                        $getWeigthforAge=WeightForAgeBoy::where('month', $month)->first();
                        $getWeightforLength=WeightForHeightBoy::where('length',round($row['chieucao']))->first();
                    //cân nặng theo tuổi: 
                    if(($row['cannang'] > $getWeigthforAge->neg2SD) && ($row['cannang'] <= $getWeigthforAge->hai_SD)){
                     $weightforAge="BT";
                     
                     }
                     elseif (($row['cannang'] >= $getWeigthforAge->neg3SD) && $row['cannang'] < $getWeigthforAge->neg2SD) {
                        $weightforAge="Suy DD độ I";
                        
                     } 
                     elseif ($row['cannang'] < $getWeigthforAge->neg3SD)  {
                        $weightforAge="Suy DD độ II";
                        
                     } 
                     else {
                        $weightforAge="";
                     }
                     //Chiều cao theo tuổi: 
                     if($row['chieucao'] > $getHightforAge->neg2SD){
                        $lengthForAge="BT";
                     }
                     elseif (($row['chieucao'] >= $getHightforAge->neg3SD) && ($row['chieucao'] < $getHightforAge->neg2SD)) {
                        $lengthForAge="Thấp còi độ I";
                     } 
                     elseif ($row['chieucao'] < $getHightforAge->neg3SD)  {
                        $lengthForAge="Thấp còi độ II";
                     } 
                     else {
                        $lengthForAge="";
                     }

                     //Cân nặng theo chiều cao: 
                     if($row['cannang'] < $getWeightforLength->neg3SD){
                        $weightforLength="Gầy còm độ II";
                     }
                     elseif (($row['cannang'] >= $getWeightforLength->neg3SD) && ($row['cannang'] < $getWeightforLength->neg2SD)) {
                        $weightforLength="Gầy độ I";
                        
                     } 
                     elseif (($row['cannang'] >= $getWeightforLength->neg2SD) && ($row['cannang'] < $getWeightforLength->hai_SD)) {
                        $weightforLength="BT";
                     } 
                     elseif (($row['cannang'] > $getWeightforLength->hai_SD) && ($row['cannang'] <= $getWeightforLength->ba_SD)) {
                        $weightforLength="Thừa cân";
                     
                     } 
                     elseif (($row['cannang']) > $getWeightforLength->ba_SD) {
                        $weightforLength="Béo phì";
                        
                     } 
                     else {
                        $weightforLength="";
                     } 
               }
               else{
                  $getHightforAge=LengthForAgeGirl::where('month', $month)->first();
                  $getWeigthforAge=WeightForAgeGirl::where('month', $month)->first();
                  $getWeightforLength=WeightForHeightGirl::where('length',round($row['chieucao']))->first();
                      // $WFA=($row['cannang']/$getWeigthforAge->median)*100;
                 

                   if(($row['cannang'] > $getWeigthforAge->neg2SD) && ($row['cannang'] <= $getWeigthforAge->hai_SD)){
                     $weightforAge="BT";
                  }
                  elseif (($row['cannang'] >= $getWeigthforAge->neg3SD) && $row['cannang'] < $getWeigthforAge->neg2SD) {
                     $weightforAge="Suy DD độ I";
                     
                  } 
                  elseif ($row['cannang'] < $getWeigthforAge->neg3SD)  {
                     $weightforAge="Suy DD độ II";
                     
                  } 
                  else {
                     $weightforAge="";
                  }

                  //Chiều cao theo tuổi: 
                  if($row['chieucao'] > $getHightforAge->neg2SD){
                     $lengthForAge="BT";
                  }
                  elseif (($row['chieucao'] >= $getHightforAge->neg3SD) && ($row['chieucao'] < $getHightforAge->neg2SD)) {
                     $lengthForAge="Thấp còi độ I";
                  } 
                  elseif ($row['chieucao'] < $getHightforAge->neg3SD)  {
                     $lengthForAge="Thấp còi độ II";
                  } 
                  else {
                     $lengthForAge="";
                  }

                  
                  if($row['cannang'] < $getWeightforLength->neg3SD){
                     $weightforLength="Gầy còm độ II";
                     
                  }
                  elseif (($row['cannang'] >= $getWeightforLength->neg3SD) && ($row['cannang'] < $getWeightforLength->neg2SD)) {
                     $weightforLength="Gầy còm độ I";
                     
                  } 
                  elseif (($row['cannang'] >= $getWeightforLength->neg2SD) && ($row['cannang'] < $getWeightforLength->hai_SD)) {
                     $weightforLength="BT";
                  } 
                  elseif (($row['cannang'] > $getWeightforLength->hai_SD) && ($row['cannang'] <= $getWeightforLength->ba_SD)) {
                     $weightforLength="Thừa cân";
                  
                  } 
                  elseif (($row['cannang']) > $getWeightforLength->ba_SD) {
                     $weightforLength="Béo phì";
                  } 
                 
                  else {
                     $weightforLength="";
                     
                  }
               }
            
               $id_children = Infobase::insertGetId([
                  "name"=>$row['tentre'],
                  "sex"=>$row['gioitinh'],
                  "madinhdanh"=>$row['madinhdanh'],
                  "birthday"=>\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['ngaysinh'])->format('Y-m-d'),
                  "parent"=>$row['tenme'],
                  "phone"=>$row['dienthoai'],
                  "address"=>$row['diachi'],
                  "id_ward"=>$row['maphuong'],
                  "id_district"=>$row['maquan'],
                  "id_province"=>$row['matinh'],
                  "weightbirth"=>$row['cannanglucsinh'],
                  "id_user"=>$id_user,
                  "status"=>1,
               ]);
            //    if ($model) {
            //       $currentData = json_decode($model->data, true);
            //       if (!is_array($currentData)) {
            //           $currentData = [];
            //       }
              
            //       foreach ($dataInfo as $newItem) {
            //           $currentData[] = $newItem; 
            //       }
            //       $model->data = json_encode($currentData);
            //       $model->save();
              
            //   } else {
            //       $model = new Paraminput();
            //       $model->id_children = $id;
            //       $model->data = $jsonData; 
            //       $model->save();
            //   }

               if($id_children){
                  // $data = [
                  //    'id_children'=>$id_children,
                  //    'input_date'=>\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['ngaycando'])->format('Y-m-d'),
                  //    'month'=>$month,
                  //    'length'=>$row['chieucao'],
                  //    'weigth'=>$row['cannang'],
                  //    'BMI'=>$BMI,
                  //    'lengthForAge'=>$lengthForAge,
                  //    'weigthForLength'=>$weightforLength,
                  //    'weigthForAge'=>$weightforAge,
                  //    'id_user'=>$id_user,
                  // ];

                  $dataInsert['id_children']=$id_children;
                  $data = [array(
                     'id_children'=>$id_children,
                     'input_date'=>\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['ngaycando'])->format('Y-m-d'),
                     'month'=>$month,
                     'length'=>$row['chieucao'],
                     'weigth'=>$row['cannang'],
                     'BMI'=>$BMI,
                     'lengthForAge'=>$lengthForAge,
                     'weigthForLength'=>$weightforLength,
                     'weigthForAge'=>$weightforAge,
                     'id_user'=>$id_user,
                  )];
                  
                  // Chuyển mảng thành chuỗi JSON
                  $jsonData = json_encode($data);
                  //$dataInsert['data']=$jsonData;
                 
                  if($row['chieucao'] && $row['cannang']){
                     //Paraminput::insert($dataInsert);
                     $model = new Paraminput();
                     $model->id_children = $id_children;
                     $model->data = $jsonData; 
                     $model->save();
                  }
                }    
            }
             else{
               //dd(123);
              // $this->duplicates[] = [                  // 'ten' => $row['tentre'],
                  // 'gt' =>$existingChild->sex,
                  // 'address' =>$existingChild->address,
                  // 'ward' =>$existingChild->id_ward,
                  // 'parent' =>$existingChild->parent,
                  // 'ngaySinh' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['ngaysinh'])->format('d-m-Y'),
               //];
            }
               
    });
    }      
   }

    public function getDuplicates(){
      
      return $this->duplicates;
    }  
}