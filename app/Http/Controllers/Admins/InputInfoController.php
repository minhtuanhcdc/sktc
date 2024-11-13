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
        $getChild="";
        $perPage = $rq->perPage?:20;
        if($rq->termSearch){
            $getChild= Infobase::Where('name','like','%'.$rq->termSearch.'%')->orwhere('madinhdanh',$rq->termSearch)->orderBy('id','asc')->with(['paraminput','khamdinhkis','vitamins'])->paginate(10);
        }
        if($rq->termDistrict){
            //dd($rq->termDistrict);
            $wards=Ward::where('id_district',$rq->termDistrict)->get();
        }
        $info_childs=Infobase::orderBy('id','asc')->with(['paraminput','khamdinhkis','vitamins'])->paginate($perPage);
        $fillters=[
            'perPage'=>$perPage
        ]; 
        return Inertia::render("InputInformation/Index",[
            'provinces'=>Province::get(),
            'districts'=>District::get(),
            'wards'=>$wards?$wards:'',
            'fillters'=>$fillters,
            'info_childs'=>$getChild?$getChild:$info_childs
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
            $getHightforAge=LengthForAgeGirl::where('month', $month)->first();
            $getWeigthforAge=WeightForAgeGirl::where('month', $month)->first();
            $getWeightforLength=WeightForHeightGirl::where('length',round(($request->length)))->first();
           //dd($getWeightforLength);
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
            if($getWeightforLength){ 
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
            }
            else{
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
            foreach($request->khamDinhKy as $date){
                $checkngay=Khamdinhky::where('id_children',$id_children)->where('ngay_kham',$date)->first();
                if(!$checkngay)
                {
                    Khamdinhky::insert([
                        'id_children'=>$id_children,
                        'ngay_kham'=>$date,
                        'id_user'=> $id_user
                    ]);
                }
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
    
        $user_update = Auth()->user()->id;
       
        $infoupdate = $request->e['updateInfo'];
       
        //dd($infoupdate);
        switch($infoupdate){
            case "infobase":
                $data = $request->e['formInfo'];
                $user_update = Auth()->user()->id;
                $data['user_update']=$user_update;
                Infobase::where('id',$id)->update($data );
        
                break;
            case "ngaykham":
                $date = $request->e['formInfo'];
                Khamdinhky::where('id_children',$id)->delete();
                foreach($date['ngay_kham'] as $date){
                        Khamdinhky::insert([
                            'id_children'=>$id,
                            'ngay_kham'=>$date,
                            'id_user'=> $user_update,
                        ]);
                    
                }
                break;
            case "vitamin":
                $date = $request->e['formInfo'];
                Vitamin::where('id_children',$id)->delete();
                foreach($date['ngay_uong'] as $date){
                    Vitamin::insert([
                            'id_children'=>$id,
                            'ngay_uong'=>$date,
                            'id_user'=> $user_update,
                        ]);   
                }
                break;
            case "param":
                $data = $request->e['formInfo'];
                $data['user_update']=$user_update;
                $getHightforAge="";
                $lengthForAge="";
                $weightforLength="";
                $weightforAge="";
                
                $ngaySinh = Carbon::parse($data['birthday']);
                $ngayBatDauCarbon = Carbon::parse($data['input_date']);
                $soNgay = (int)($ngaySinh->diffInDays($ngayBatDauCarbon))/30.4375;
                $month=(int)round($soNgay);

                if($data['sex'] ==1){
               
                    $getHightforAge=LengthForAgeBoy::where('month', $month)->first();
                    $getWeigthforAge=WeightForAgeBoy::where('month', $month)->first();
                    $getWeightforLength=WeightForHeightBoy::where('length',round(($data['length'])))->first();
                    
                    //Chiều cao theo tuổi: 
                    if($data['length'] > $getHightforAge->neg2SD){
                        $lengthForAge="BT";
                    }
                    elseif (($data['length'] >= $getHightforAge->neg3SD) && ($data['length'] < $getHightforAge->neg2SD)) {
                        $lengthForAge="Thấp còi vừa";
                    } 
                    elseif ($data['length'] < $getHightforAge->neg3SD)  {
                        $lengthForAge="Thấp còi nặng";
                    } 
                    else {
                        $lengthForAge="";
                    }
                    //Cân nặng theo chiều cao: 
                    if($data['weigth'] < $getWeightforLength->neg3SD){
                        $weightforLength="Gầy còm nặng"; 
                    }
                    elseif (($data['weigth'] > $getWeightforLength->neg3SD) && ($data['weigth'] < $getWeightforLength->neg2SD)) {
                        $weightforLength="Gầy còm";
                    }                
                    elseif (($data['weigth'] > $getWeightforLength->hai_SD) && ($data['weigth'] < $getWeightforLength->ba_SD)) {
                        $weightforLength="Thừa cân";
                    } 
                    elseif (($request['weigth']) > $getWeightforLength->ba_SD) {
                        $weightforLength="Béo phì";
                    } 
                    elseif (($data['weigth'] > $getWeightforLength->neg2SD) && ($data['weigth'] < $getWeightforLength->hai_SD)) {
                    $weightforLength="BT";
                    } 
                    else {
                        $weightforLength="";
                    }
                    //cân nặng theo tuổi: 
                    if(($data['weigth'] > $getWeigthforAge->neg2SD) && ($data['weigth'] < $getWeigthforAge->hai_SD)){
                        $weightforAge="BT";  
                    }
                    elseif (($data['weigth'] > $getWeigthforAge->ba_SD)) {
                        $weightforAge="Béo phì";
                    
                    } 
                    elseif (($data['weigth'] >= $getWeigthforAge->neg3SD) && $data['weigth'] < $getWeigthforAge->neg2SD) {
                        $weightforAge="Suy DD vừa";
                    
                    } 
                    elseif ($data['weigth'] < $getWeigthforAge->neg3SD)  {
                        $weightforAge="Suy DD Nặng";
                    } 
                    else {
                        $weightforAge="";
                    }
                    
                }
                else{
                    $getHightforAge=LengthForAgeGirl::where('month', $month)->first();
                    $getWeigthforAge=WeightForAgeGirl::where('month', $month)->first();
                    $getWeightforLength=WeightForHeightGirl::where('length',round(($request->length)))->first();
                    //dd($getWeightforLength);
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
                    if($getWeightforLength){ 
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
                    }
                    else{
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
                $BMI=round($data['weigth']*10000/($data['length']*$data['length']),2);
                $dataInsert['id_children']=$id;
                $dataInsert['input_date']=$data['input_date'];
                $dataInsert['month']=$month;
                $dataInsert['length']=$data['length'];
                $dataInsert['weigth']=$data['weigth'];
                $dataInsert['BMI']=$BMI;

                $dataInsert['lengthForAge']=$lengthForAge;
                $dataInsert['weigthForLength']=$weightforLength;
                $dataInsert['weigthForAge']=$weightforAge;
                $dataInsert['user_update']=$user_update;
                
                $getparam = Paraminput::where('id_children',$id)->where('month', $data['month'])->first();
                if($getparam){
                    Paraminput::where('id_children',$id)->where('month', $data['month'])->update($dataInsert );
                }
                else{
                    Paraminput::insert($dataInsert);
                }
                break;
            case "addParam":
              
                    $data = $request->e['formInfo'];
                    $data['user_update']=$user_update;
                    $getHightforAge="";
                    $lengthForAge="";
                    $weightforLength="";
                    $weightforAge="";
                    
                    $ngaySinh = Carbon::parse($data['birthday']);
                    $ngayBatDauCarbon = Carbon::parse($data['input_date']);
                    $soNgay = (int)($ngaySinh->diffInDays($ngayBatDauCarbon))/30.4375;
                    $month=(int)round($soNgay);
    
                    if($data['sex'] ==1){
                   
                        $getHightforAge=LengthForAgeBoy::where('month', $month)->first();
                        $getWeigthforAge=WeightForAgeBoy::where('month', $month)->first();
                        $getWeightforLength=WeightForHeightBoy::where('length',round(($data['length'])))->first();
                        //dd($getWeightforLength);
                        //Chiều cao theo tuổi: 
                        if($data['length'] > $getHightforAge->neg2SD){
                            $lengthForAge="BT";
                        }
                        elseif (($data['length'] >= $getHightforAge->neg3SD) && ($data['length'] < $getHightforAge->neg2SD)) {
                            $lengthForAge="Thấp còi vừa";
                        } 
                        elseif ($data['length'] < $getHightforAge->neg3SD)  {
                            $lengthForAge="Thấp còi nặng";
                        } 
                        else {
                            $lengthForAge="";
                        }
                        //Cân nặng theo chiều cao: 
                        if($data['weigth'] < $getWeightforLength->neg3SD){
                            $weightforLength="Gầy còm nặng"; 
                        }
                        elseif (($data['weigth'] > $getWeightforLength->neg3SD) && ($data['weigth'] < $getWeightforLength->neg2SD)) {
                            $weightforLength="Gầy còm";
                        }                
                        elseif (($data['weigth'] > $getWeightforLength->hai_SD) && ($data['weigth'] < $getWeightforLength->ba_SD)) {
                            $weightforLength="Thừa cân";
                        } 
                        elseif (($request['weigth']) > $getWeightforLength->ba_SD) {
                            $weightforLength="Béo phì";
                        } 
                        elseif (($data['weigth'] > $getWeightforLength->neg2SD) && ($data['weigth'] < $getWeightforLength->hai_SD)) {
                        $weightforLength="BT";
                        } 
                        else {
                            $weightforLength="";
                        }
                        //cân nặng theo tuổi: 
                        if(($data['weigth'] > $getWeigthforAge->neg2SD) && ($data['weigth'] < $getWeigthforAge->hai_SD)){
                            $weightforAge="BT";  
                        }
                        elseif (($data['weigth'] > $getWeigthforAge->ba_SD)) {
                            $weightforAge="Béo phì";
                        
                        } 
                        elseif (($data['weigth'] >= $getWeigthforAge->neg3SD) && $data['weigth'] < $getWeigthforAge->neg2SD) {
                            $weightforAge="Suy DD vừa";
                        
                        } 
                        elseif ($data['weigth'] < $getWeigthforAge->neg3SD)  {
                            $weightforAge="Suy DD Nặng";
                        } 
                        else {
                            $weightforAge="";
                        }
                        
                    }
                    else{
                        $getHightforAge=LengthForAgeGirl::where('month', $month)->first();
                        $getWeigthforAge=WeightForAgeGirl::where('month', $month)->first();
                        $getWeightforLength=WeightForHeightGirl::where('length',round(($request->length)))->first();
                        //dd($getWeightforLength);
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
                        if($getWeightforLength){ 
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
                        }
                        else{
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
                    $BMI=round($data['weigth']*10000/($data['length']*$data['length']),2);
                    $dataInsert['id_children']=$id;
                    $dataInsert['input_date']=$data['input_date'];
                    $dataInsert['month']=$month;
                    $dataInsert['length']=$data['length'];
                    $dataInsert['weigth']=$data['weigth'];
                    $dataInsert['BMI']=$BMI;
    
                    $dataInsert['lengthForAge']=$lengthForAge;
                    $dataInsert['weigthForLength']=$weightforLength;
                    $dataInsert['weigthForAge']=$weightforAge;
                    $dataInsert['user_update']=$user_update;
                    //dd($dataInsert);
                    $getparam = Paraminput::where('id_children',$id)->where('month', $data['month'])->first();
                    if($getparam){
                       // dd(123);
                        Paraminput::where('id_children',$id)->where('month', $data['month'])->update($dataInsert );
                    }
                    else{
                        Paraminput::insert($dataInsert);
                    }
                    break;
        }
       
        return back()->withInput()->with('success','Create Menu successfully!');
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
    public function updateInfo(Request $r){
        dd($r->all());
    }
}
