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
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Events\BeforeImport;


class ImportInfomation implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public $duplicates = [];
   public function collection(Collection $rows)
   {
      /*
      "tentre" => "Lê Thanh Hiền A"
        "gioitinh" => 1
        "madinhdanh" => 54687
        "ngaysinh" => 44557
        "tenme" => "Hoa A"
        "dienthoai" => 906510173
        "diachi" => "124 ADL"
        "maphuong" => 26734
        "maquan" => 760
        "matinh" => 79
        "cannanglucsinh" => 3
        "ngaycando" => 45572
        "chieucao" => 80
        "cannang" => 20
        "ngaykham" => 45572
        "vitamin" => 45572
        "candotai" => 26734
      */ 
      $id_user = Auth()->user()->id;
     dd($rows);
      foreach ($rows as $row){  

            $birthDay = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['ngaysinh'])->format('Y-m-d');
            // $ngayBatDauCarbon = Carbon::parse($birthDay); 
            // $soThang = (int)($ngayBatDauCarbon->diffInDays(Carbon::now()))/30.4375;
            // $month=(int)round($soThang);
            // $BMI=round($row['cannang']*10000/($row['chieucao']*$row['chieucao']),2);
            $id_children = Infobase::insertGetId([
               "name"=>$row['tentre'],
               "sex"=>$row['gioitinh'],
               "madinhdanh"=>$row['madinhdanh'],
               "birthday"=>$birthDay,
               "parent"=>$row['tenme'],
               "phone"=>$row['dienthoai'],
               "address"=>$row['diachi'],
               "id_ward"=>$row['maphuong'],
               "id_district"=>$row['maquan'],
               "id_province"=>$row['matinh'],
               "weightbirth"=>$row['cannanglucsinh'],
               "id_user"=>1,
               "status"=>1,
            ]);
            // $getHightforAge="";
            // $getWeigthforAge="";
            // $getWeightforLength="";
            // $getHasInTable = Infobase::Where('name',$row['tentre'])->where('parent',$row['tenme'])
            //                            ->where('birthday',\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['ngaysinh'])->format('Y-m-d'))->first();
            // if(!$getHasInTable){ 
            //    if($row['gioitinh'] == 1){
            //             $getHightforAge=LengthForAgeBoy::where('month', $month)->first();
            //             $getWeigthforAge=WeightForAgeBoy::where('month', $month)->first();
            //             $getWeightforLength=WeightForHeightBoy::where('length',round($row['chieucao']))->first();
                     
            //             //Chiều cao theo tuổi: 
            //             if($row['chieucao'] > $getHightforAge->neg2SD){
            //                $lengthForAge="BT";
            //             }
            //             elseif (($row['chieucao'] >= $getHightforAge->neg3SD) && ($row['chieucao'] < $getHightforAge->neg2SD)) {
            //                $lengthForAge="Thấp còi vừa";
            //             } 
            //             elseif ($row['chieucao'] < $getHightforAge->neg3SD)  {
            //                $lengthForAge="Thấp còi nặng";
            //             } 
            //             else {
            //                $lengthForAge="";
            //             }

            //             //Cân nặng theo chiều cao: 
            //             if($row['cannang'] < $getWeightforLength->neg3SD){
            //                $weightforLength="Gầy còm nặng";
                           
            //             }
            //             elseif (($row['cannang'] > $getWeightforLength->neg3SD) && ($row['cannang'] < $getWeightforLength->neg2SD)) {
            //                $weightforLength="Gầy còm";
                           
            //             } 
                     
            //             elseif (($row['cannang'] > $getWeightforLength->hai_SD) && ($row['cannang'] < $getWeightforLength->ba_SD)) {
            //                $weightforLength="Thừa cân";
                        
            //             } 
            //             elseif (($row['cannang']) > $getWeightforLength->ba_SD) {
            //                $weightforLength="Béo phì";
                           
            //             } 
            //             elseif (($row['cannang'] > $getWeightforLength->neg2SD) && ($row['cannang'] < $getWeightforLength->hai_SD)) {
            //                $weightforLength="BT";
            //             } 
            //             else {
            //                $weightforLength="";
            //             }

            //             //cân nặng theo tuổi: 
            //             if(($row['cannang'] > $getWeigthforAge->neg2SD) && ($row['cannang'] < $getWeigthforAge->hai_SD)){
            //                $weightforAge="BT";
                           
            //             }
            //             elseif (($row['cannang'] > $getWeigthforAge->ba_SD)) {
            //                $weightforAge="Béo phì";
                           
            //             } 
            //             elseif (($row['cannang'] >= $getWeigthforAge->neg3SD) && $row['cannang'] < $getWeigthforAge->neg2SD) {
            //                $weightforAge="Suy DD vừa";
                           
            //             } 
            //             elseif ($row['cannang'] < $getWeigthforAge->neg3SD)  {
            //                $weightforAge="Suy DD Nặng";
                           
            //             } 
            //             else {
            //                $weightforAge="";
            //             }
                     
            //    }

            //    $id_children = Infobase::insertGetId([
            //       "name"=>$row['tentre'],
            //       "sex"=>$row['gioitinh'],
            //       "madinhdanh"=>$row['madinhdanh'],
            //       "birthday"=>\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['ngaysinh'])->format('Y-m-d'),
            //       "parent"=>$row['tenme'],
            //       "phone"=>$row['dienthoai'],
            //       "address"=>$row['diachi'],
            //       "id_ward"=>$row['maphuong'],
            //       "id_district"=>$row['maquan'],
            //       "id_province"=>$row['matinh'],
            //       "weightbirth"=>$row['cannanglucsinh'],
            //       "id_user"=>$id_user,
            //       "status"=>1,
            //    ]);
            //    Paraminput::insert([
            //       'id_children'=>$id_children,
            //       'input_date'=>\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['ngaycando'])->format('Y-m-d'),
            //       'month'=>$month,
            //       'length'=>$row['chieucao'],
            //       'weigth'=>$row['cannang'],
            //       'BMI'=>$BMI,
            //       'lengthForAge'=>$lengthForAge,
            //       'weigthForLength'=>$weightforLength,
            //       'weigthForAge'=>$weightforAge,
            //       'id_user'=>$id_user,
            //    ]);
            //    if($row['ngaykham']){
            //       $checkngay=Khamdinhky::where('id_children',$id_children)->where('ngay_kham',$row['ngaykham'])->first();
            //       if(!$checkngay)
            //       {
            //          Khamdinhky::insert([
            //             'id_children'=>$id_children,
            //             'ngay_kham'=>\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['ngaykham'])->format('Y-m-d'),
            //             'id_user'=> $id_user
            //          ]);
            //       }
            //    }
            //    if($row['vitamin']){
            //          $checkvitamin=Vitamin::where('id_children',$id_children)->where('ngay_uong',$row['vitamin'])->first();
            //             if(!$checkvitamin)
            //             {
            //                Vitamin::insert([
            //                      'id_children'=>$id_children,
            //                      'ngay_uong'=>\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['vitamin'])->format('Y-m-d'),
            //                      'id_user'=> $id_user
            //                ]);
            //             }
            //    }     
            // }
            // else{
            //    $this->duplicates[] = [
            //       'ten' => $row['tentre'],
            //       'ngaySinh' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['ngaysinh'])->format('d-m-Y'),
            //    ];
            // }
         }
         //dd($this->getDuplicates());
      
   }
   // public function getDuplicates(){
      
   //    return $this->duplicates;
   //  }     

   
       
}
