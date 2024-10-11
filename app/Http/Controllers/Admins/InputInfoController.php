<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Infobase;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use App\Models\Paraminput;
use App\Models\Vitamin;
use App\Models\Khamdinhky;
use App\Models\LengthForAgeBoy;
use App\Models\LengthForAgeGirl;
use App\Models\WeightForAgeBoy;
use App\Models\WeightForAgeGirl;
use App\Models\WeightForHeightBoy;
use App\Models\WeightForHeightGirl;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class InputInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $rq)
    {
        $wards='';
        if($rq->termDistrict){
            $wards=Ward::where('id_district',$rq->termDistrict)->get();
        }
        $info_childs=Infobase::orderBy('id','desc')->with('paraminput')->paginate(10);
        
        return Inertia::render("InputInformation/Index",[
            'provinces'=>Province::get(),
            'districts'=>District::get(),
            'wards'=>$wards?$wards:'',
            'info_childs'=>$info_childs
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
       
       $id_user = Auth()->user()->id;
       $data=$request->validate([
        'name'=>['required','string'],
        'weigth'=>['required','numeric'],
        'length'=>['required','numeric'],
        'birthday'=>['required','string'],
        'sex'=>['required','integer'],
        'parent'=>['required','string'],
        'madinhdanh'=>['nullable','string'],
        'matiemchung'=>['nullable','string'],
        'weightbirth'=>['nullable','numeric'],
        'address'=>['required','string'],
        'id_province'=>['nullable','integer'],
        'id_district'=>['nullable','integer'],
        'id_ward'=>['nullable','integer'],
        'input_date'=>['nullable','string'],
       
       ]);
       $getHightforAge="";
       $lengthForAge="";
       $weightforLength="";
       $weightforAge="";
       $ngayBatDauCarbon = Carbon::parse($request->birthday);
        // Tính số ngày từ ngày bắt đầu đến hiện tại
        $soNgay = (int)($ngayBatDauCarbon->diffInDays(Carbon::now()))/30.4375;
        $month=(int)round($soNgay);
        if($request->sex ==1){
            $getHightforAge=LengthForAgeBoy::where('month', $month)->first();
            $getWeigthforAge=WeightForAgeBoy::where('month', $month)->first();
            $getWeightforLength=WeightForHeightBoy::where('length',round(($request->length)))->first();
            
            //Chiều cao theo tuổi: 
            if($request->length > $getHightforAge->neg2SD){
                $lengthForAge="BT";
            }
            elseif (($request->length >= $getHightforAge->neg3SD) && ($request->length < $getHightforAge->neg2SD)) {
                $lengthForAge="Thấp còi vừa";
            } 
            elseif ($request->length < $getHightforAge->neg3SD)  {
                $lengthForAge="Thấp còi nặng";
            } 
            else {
                $lengthForAge="";
            }

            //Cân nặng theo chiều cao: 
            if($request->weigth < $getWeightforLength->neg3SD){
                $weightforLength="Gầy còm nặng";
               
            }
            elseif (($request->weigth > $getWeightforLength->neg3SD) && ($request->weigth < $getWeightforLength->neg2SD)) {
                $weightforLength="Gầy còm";
                
            } 
           
            elseif (($request->weigth > $getWeightforLength->hai_SD) && ($request->weigth < $getWeightforLength->ba_SD)) {
                $weightforLength="Thừa cân";
              
            } 
            elseif (($request->weigth) > $getWeightforLength->ba_SD) {
                $weightforLength="Béo phì";
               
            } 
            elseif (($request->weigth > $getWeightforLength->neg2SD) && ($request->weigth < $getWeightforLength->hai_SD)) {
               $weightforLength="BT";
            } 
            else {
                $weightforLength="";
            }

            //cân nặng theo tuổi: 
            if(($request->weigth > $getWeigthforAge->neg2SD) && ($request->weigth < $getWeigthforAge->hai_SD)){
                $weightforAge="BT";
                
            }
            elseif (($request->weigth > $getWeigthforAge->ba_SD)) {
                $weightforAge="Béo phì";
               
            } 
            elseif (($request->weigth >= $getWeigthforAge->neg3SD) && $request->weigth < $getWeigthforAge->neg2SD) {
                $weightforAge="Suy DD vừa";
               
            } 
            elseif ($request->weigth < $getWeigthforAge->neg3SD)  {
                $weightforAge="Suy DD Nặng";
               
            } 
            else {
                $weightforAge="";
            }
            
        }
        else{
            $getHightforAgeGirl=LengthForAgeGirl::where('month', $month)->first();
            $getWeigthforAgeGirl=WeightForAgeGirl::where('month', $month)->first();
            $getWeightforLengthGirl=WeightForHeightGirl::where('length',round(($request->length)))->first();
            
            //Chiều cao theo tuổi: 
            if($request->length > $getHightforAgeGirl->neg2SD){
                $lengthForAge="BT";
            }
            elseif (($request->length >= $getHightforAgeGirl->neg3SD) && ($request->length < $getHightforAgeGirl->neg2SD)) {
                $lengthForAge="Thấp còi vừa";
            } 
            elseif ($request->length < $getHightforAgeGirl->neg3SD)  {
                $lengthForAge="Thấp còi nặng";
            } 
            else {
                $lengthForAge="";
            }

            //Cân nặng theo chiều cao: 
            if($request->weigth < $getWeightforLengthGirl->neg3SD){
                $weightforLength="Gầy còm nặng";
               
            }
            elseif (($request->weigth > $getWeightforLengthGirl->neg3SD) && ($request->weigth < $getWeightforLengthGirl->neg2SD)) {
                $weightforLength="Gầy còm";
                
            } 
           
            elseif (($request->weigth > $getWeightforLengthGirl->hai_SD) && ($request->weigth < $getWeightforLengthGirl->ba_SD)) {
                $weightforLength="Thừa cân";
              
            } 
            elseif (($request->weigth) > $getWeightforLengthGirl->ba_SD) {
                $weightforLength="Béo phì";
               
            } 
            elseif (($request->weigth > $getWeightforLengthGirl->neg2SD) && ($request->weigth < $getWeightforLengthGirl->hai_SD)) {
               $weightforLength="BT";
            } 
            else {
                $weightforLength="";
            }

            //cân nặng theo tuổi: 
            if(($request->weigth > $getWeigthforAgeGirl->neg2SD) && ($request->weigth < $getWeigthforAgeGirl->hai_SD)){
                $weightforAge="BT";
                
            }
            elseif (($request->weigth > $getWeigthforAgeGirl->ba_SD)) {
                $weightforAge="Béo phì";
               
            } 
            elseif (($request->weigth >= $getWeigthforAgeGirl->neg3SD) && $request->weigth < $getWeigthforAgeGirl->neg2SD) {
                $weightforAge="Suy DD vừa";
               
            } 
            elseif ($request->weigth < $getWeigthforAgeGirl->neg3SD)  {
                $weightforAge="Suy DD Nặng";
               
            } 
            else {
                $weightforAge="";
            }
            
        }
        //dd($getWeigthforAge);
       // dd( $getWeightforLength->ba_SD);
       $BMI=round($request->weigth*10000/($request->length*$request->length),2);
      // dd($BMI);
         $data['id_user']=$id_user;
         $data['BMI']=$BMI;
         $id_children=Infobase::insertGetId([
            'name'=>$request->name,
            'sex'=>$request->sex,
            'birthday'=>$request->birthday,
            'parent'=>$request->parent,
            'madinhdanh'=>$request->madinhdanh,
            'matiemchung'=>$request->matiemchung,
            'address'=>$request->address,
            'id_province'=>$request->id_province,
            'id_district'=>$request->id_district,
            'id_ward'=>$request->id_ward,
            'weightbirth'=>$request->weightbirth,
            'id_user'=>$id_user, 
         ]);
         $data['id_children']=$id_children;
        if($request->khamDinhKy){
            $checkngay=Khamdinhky::where('id_children',$id_children)->where('ngay_kham',$request->khamDinhKy)->first();
            if(!$checkngay)
            {
                Khamdinhky::insert([
                    'id_children'=>$id_children,
                    'ngay_kham'=>$request->khamDinhKy,
                    'id_user'=> $id_user
                ]);
            }
        }
        if($request->ngay_uong){
            $checkvitamin=Vitamin::where('id_children',$id_children)->where('ngay_uong',$request->ngay_uong)->first();
                if(!$checkvitamin)
                {
                    Vitamin::insert([
                        'id_children'=>$id_children,
                        'ngay_uong'=>$request->ngay_uong,
                        'id_user'=> $id_user
                    ]);
                }
        }
        
        if($getHightforAge){
                Paraminput::insert([
                    'id_children'=>$id_children,
                    'input_date'=>$request->input_date,
                    'month'=>$month,
                    'length'=>$request->length,
                    'weigth'=>$request->weigth,
                    'BMI'=>$BMI,
                    'lengthForAge'=>$lengthForAge,
                    'weigthForLength'=>$weightforLength,
                    'weigthForAge'=>$weightforAge,
                    'id_user'=>$id_user,
                ]);
            }

    return back()->withInput()->with('success','Create Menu successfully!');
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

public function tinhThangTuoi($ngaySinh) {
    // Chuyển đổi chuỗi ngày sinh thành đối tượng Carbon
    $ngaySinhCarbon = Carbon::parse($ngaySinh);
    
    // Tính tháng tuổi
    $thangTuoi = $ngaySinhCarbon->diffInMonths(Carbon::now());
    
    return $thangTuoi;
}
public function tinhSoNgay($ngayBatDau) {
    // Chuyển đổi chuỗi ngày bắt đầu thành đối tượng Carbon
    $ngayBatDauCarbon = Carbon::parse($ngayBatDau);
    
    // Tính số ngày từ ngày bắt đầu đến hiện tại
    $soNgay = $ngayBatDauCarbon->diffInDays(Carbon::now());
    
    return $soNgay;
}
}
