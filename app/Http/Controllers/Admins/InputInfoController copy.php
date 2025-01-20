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
        //dd(123);
        $wards='';
        $getChild="";
        $perPage = $rq->perPage?:50;
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
            'info_childs'=>$getChild?$getChild->withQueryString():$info_childs
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
      //dd($request->all());
       $id_user = Auth()->user()->id;
       $data=$request->validate([
                                    'name'=>['required','string'],
                                    'weigth'=>['required','array'],
                                    'length'=>['required','array'],
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
                                    'input_date'=>['nullable','array'],
                                
                                ]);
     
       foreach ($request->input_date as $index=> $value) { 
            $getHightforAge="";
            $lengthForAge="";
            $weightforLength="";
            $weightforAge="";
        
            $ngayBatDauCarbon = Carbon::parse($request->birthday);
            $soNgay = (int)($ngayBatDauCarbon->diffInDays(Carbon::now()))/30.4375;
            $month=(int)round($soNgay);
            if($data['sex'] ==1){
                $getHightforAge=LengthForAgeBoy::where('month', $month)->first();
                $getWeigthforAge=WeightForAgeBoy::where('month', $month)->first();
                $getWeightforLength=WeightForHeightBoy::where('length',round(($data['length'][$index])))->first();
                
                if(($data['weigth'][$index] > $getWeigthforAge->neg2SD) && ($data['weigth'][$index] <= $getWeigthforAge->hai_SD)){
                    $weightforAge="BT";  
                }
                
                elseif (($data['weigth'][$index] >= $getWeigthforAge->neg3SD) && $data['weigth'][$index] < $getWeigthforAge->neg2SD) {
                    $weightforAge="Suy DD độ I";
                
                } 
                elseif ($data['weigth'][$index] < $getWeigthforAge->neg3SD)  {
                    $weightforAge="Suy DD độ II";
                } 
                else {
                    $weightforAge="";
                }
                //Chiều cao theo tuổi: 
                if($data['length'][$index] > $getHightforAge->neg2SD){
                    $lengthForAge="BT";
                }
                elseif (($data['length'][$index] >= $getHightforAge->neg3SD) && ($data['length'][$index] < $getHightforAge->neg2SD)) {
                    $lengthForAge="Thấp còi độ I";
                } 
                elseif ($data['length'][$index] < $getHightforAge->neg3SD)  {
                    $lengthForAge="Thấp còi độ II";
                } 
                else {
                    $lengthForAge="";
                }
                
                //Cân nặng theo chiều cao: 
                if($data['weigth'][$index] < $getWeightforLength->neg3SD){
                    $weightforLength="SDD gầy còm độ I"; 
                }
                elseif (($data['weigth'][$index] >= $getWeightforLength->neg3SD) && ($data['weigth'] < $getWeightforLength->neg2SD)) {
                    $weightforLength="SDD gầu còm độ II";
                }  
                elseif (($data['weigth'][$index] >= $getWeightforLength->neg2SD) && ($data['weigth'][$index] < $getWeightforLength->hai_SD)) {
                    $weightforLength="BT";
                    }               
                elseif (($data['weigth'][$index] > $getWeightforLength->hai_SD) && ($data['weigth'][$index] <= $getWeightforLength->ba_SD)) {
                    $weightforLength="Thừa cân";
                } 
                elseif (($request['weigth'][$index]) > $getWeightforLength->ba_SD) {
                    $weightforLength="Béo phì";
                } 
            
                else {
                    $weightforLength="";
                }
            }
            else{
                //dd($data['length']);
                $getHightforAge=LengthForAgeGirl::where('month', $month)->first();
                $getWeigthforAge=WeightForAgeGirl::where('month', $month)->first();
                $getWeightforLength=WeightForHeightGirl::where('length',round(($data['length'][$index])))->first();
                //dd($getWeightforLength);
                //cân nặng theo tuổi: 
                if(($data['weigth'][$index] > $getWeigthforAge->neg2SD) && ($data['weigth'][$index] <= $getWeigthforAge->hai_SD)){
                    $weightforAge="BT";  
                }
                
                elseif (($data['weigth'][$index] >= $getWeigthforAge->neg3SD) && $data['weigth'][$index] < $getWeigthforAge->neg2SD) {
                    $weightforAge="Suy DD độ I";
                
                } 
                elseif ($data['weigth'][$index] < $getWeigthforAge->neg3SD)  {
                    $weightforAge="Suy DD độ II";
                } 
                else {
                    $weightforAge="";
                }
                //Chiều cao theo tuổi: 
                if($data['length'][$index] > $getHightforAge->neg2SD){
                    $lengthForAge="BT";
                }
                elseif (($data['length'][$index] >= $getHightforAge->neg3SD) && ($data['length'][$index] < $getHightforAge->neg2SD)) {
                    $lengthForAge="Thấp còi độ I";
                } 
                elseif ($data['length'][$index] < $getHightforAge->neg3SD)  {
                    $lengthForAge="Thấp còi độ II";
                } 
                else {
                    $lengthForAge="";
                }
                
                //Cân nặng theo chiều cao: 
                if($data['weigth'][$index] < $getWeightforLength->neg3SD){
                    $weightforLength="SDD gầy còm độ I"; 
                }
                elseif (($data['weigth'][$index] >= $getWeightforLength->neg3SD) && ($data['weigth'] < $getWeightforLength->neg2SD)) {
                    $weightforLength="SDD gầu còm độ II";
                }  
                elseif (($data['weigth'][$index] >= $getWeightforLength->neg2SD) && ($data['weigth'][$index] < $getWeightforLength->hai_SD)) {
                    $weightforLength="BT";
                    }               
                elseif (($data['weigth'][$index] > $getWeightforLength->hai_SD) && ($data['weigth'][$index] <= $getWeightforLength->ba_SD)) {
                    $weightforLength="Thừa cân";
                } 
                elseif (($request['weigth'][$index]) > $getWeightforLength->ba_SD) {
                    $weightforLength="Béo phì";
                } 
            
                else {
                    $weightforLength="";
                }
                
            }
        }
         //$BMI=round($request->weigth*10000/($request->length*$request->length),2);
         //$data['id_user']=$id_user;
         $getData = Infobase::Where('name',$request->name)->where('sex',$request->sex)->where('birthday',$request->birthday)->where('parent',$request->parent)->where('id_ward',$request->id_ward)->exists();
         if(!$getData){ 
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
            $dataInfo[]= [
                'input_date' => $value,
                'month' => $month,
                'length' => $data['length'][$index],  
                'weigth' => $data['weigth'][$index],  
                'lengthForAge' => $lengthForAge,
                'weigthForLength' => $weightforLength,
                'weigthForAge' => $weightforAge,
                'user_update' => $id_user
            ];
            $jsonData = json_encode($dataInfo);
            $model = new Paraminput();
            $model->id_children = $id_children;
            $model->data = $jsonData; 
            $model->save();
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
                foreach($request->ngay_uong as $date){
                    $checkvitamin=Vitamin::where('id_children',$id_children)->where('ngay_uong',$data)->first();
                        if(!$checkvitamin)
                        {
                            Vitamin::insert([
                                'id_children'=>$id_children,
                                'ngay_uong'=>$data,
                                'id_user'=> $id_user
                            ]);
                        }
                    }
            }
        }
       
        
        // if($getHightforAge){
        //         Paraminput::insert([
        //             'id_children'=>$id_children,
        //             'input_date'=>$request->input_date,
        //             'month'=>$month,
        //             'length'=>$request->length,
        //             'weigth'=>$request->weigth,
        //             //'BMI'=>$BMI,
        //             'lengthForAge'=>$lengthForAge,
        //             'weigthForLength'=>$weightforLength,
        //             'weigthForAge'=>$weightforAge,
        //             'id_user'=>$id_user,
        //         ]);
        //     }
        if($getData){
            back()->withInput()->with('failure','Trẻ đã có trong dữ liệu');
        }
        else{
            return back()->withInput()->with('success','Create Menu successfully!');

        }
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
        $data = $request->e['formInfo'];
        //$dataInsert = [
            //'id_children' => $data['id_children'],
           $dataInfo = [];
        //];
        $data['user_update']=$user_update;
        $getHightforAge="";
        $lengthForAge="";
        $weightforLength="";
        $weightforAge="";
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
                foreach ($data['input_date'] as $index=> $value) { 
                    $ngaySinh = Carbon::parse($data['birthday']);
                    $ngayBatDauCarbon = Carbon::parse($data['input_date'][$index]);
                    $soNgay = (int)($ngaySinh->diffInDays($ngayBatDauCarbon))/30.4375;
                    $month=(int)round($soNgay);
                    
                    if($data['sex'] ==1){
                        $getHightforAge=LengthForAgeBoy::where('month', $month)->first();
                        $getWeigthforAge=WeightForAgeBoy::where('month', $month)->first();
                        $getWeightforLength=WeightForHeightBoy::where('length',round(($data['length'][$index])))->first();
                        
                        if(($data['weigth'][$index] > $getWeigthforAge->neg2SD) && ($data['weigth'][$index] <= $getWeigthforAge->hai_SD)){
                            $weightforAge="BT";  
                        }
                        
                        elseif (($data['weigth'][$index] >= $getWeigthforAge->neg3SD) && $data['weigth'][$index] < $getWeigthforAge->neg2SD) {
                            $weightforAge="Suy DD độ I";
                        
                        } 
                        elseif ($data['weigth'][$index] < $getWeigthforAge->neg3SD)  {
                            $weightforAge="Suy DD độ II";
                        } 
                        else {
                            $weightforAge="";
                        }
                        //Chiều cao theo tuổi: 
                        if($data['length'][$index] > $getHightforAge->neg2SD){
                            $lengthForAge="BT";
                        }
                        elseif (($data['length'][$index] >= $getHightforAge->neg3SD) && ($data['length'][$index] < $getHightforAge->neg2SD)) {
                            $lengthForAge="Thấp còi độ I";
                        } 
                        elseif ($data['length'][$index] < $getHightforAge->neg3SD)  {
                            $lengthForAge="Thấp còi độ II";
                        } 
                        else {
                            $lengthForAge="";
                        }
                        
                        //Cân nặng theo chiều cao: 
                        if($data['weigth'][$index] < $getWeightforLength->neg3SD){
                            $weightforLength="SDD gầy còm độ I"; 
                        }
                        elseif (($data['weigth'][$index] >= $getWeightforLength->neg3SD) && ($data['weigth'] < $getWeightforLength->neg2SD)) {
                            $weightforLength="SDD gầu còm độ II";
                        }  
                        elseif (($data['weigth'][$index] >= $getWeightforLength->neg2SD) && ($data['weigth'][$index] < $getWeightforLength->hai_SD)) {
                            $weightforLength="BT";
                            }               
                        elseif (($data['weigth'][$index] > $getWeightforLength->hai_SD) && ($data['weigth'][$index] <= $getWeightforLength->ba_SD)) {
                            $weightforLength="Thừa cân";
                        } 
                        elseif (($request['weigth'][$index]) > $getWeightforLength->ba_SD) {
                            $weightforLength="Béo phì";
                        } 
                    
                        else {
                            $weightforLength="";
                        }
                    }
                    else{
                    
                        $getHightforAge=LengthForAgeGirl::where('month', $month)->first();
                        $getWeigthforAge=WeightForAgeGirl::where('month', $month)->first();
                        $getWeightforLength=WeightForHeightGirl::where('length',round(($data['length'][$index])))->first();
                        //cân nặng theo tuổi: 
                        if(($data['weigth'][$index] > $getWeigthforAge->neg2SD) && ($data['weigth'][$index] <= $getWeigthforAge->hai_SD)){
                            $weightforAge="BT";  
                        }
                        
                        elseif (($data['weigth'][$index] >= $getWeigthforAge->neg3SD) && $data['weigth'][$index] < $getWeigthforAge->neg2SD) {
                            $weightforAge="Suy DD độ I";
                        
                        } 
                        elseif ($data['weigth'][$index] < $getWeigthforAge->neg3SD)  {
                            $weightforAge="Suy DD độ II";
                        } 
                        else {
                            $weightforAge="";
                        }
                        //Chiều cao theo tuổi: 
                        if($data['length'][$index] > $getHightforAge->neg2SD){
                            $lengthForAge="BT";
                        }
                        elseif (($data['length'][$index] >= $getHightforAge->neg3SD) && ($data['length'][$index] < $getHightforAge->neg2SD)) {
                            $lengthForAge="Thấp còi độ I";
                        } 
                        elseif ($data['length'][$index] < $getHightforAge->neg3SD)  {
                            $lengthForAge="Thấp còi độ II";
                        } 
                        else {
                            $lengthForAge="";
                        }
                        
                        //Cân nặng theo chiều cao: 
                        if($data['weigth'][$index] < $getWeightforLength->neg3SD){
                            $weightforLength="SDD gầy còm độ I"; 
                        }
                        elseif (($data['weigth'][$index] >= $getWeightforLength->neg3SD) && ($data['weigth'] < $getWeightforLength->neg2SD)) {
                            $weightforLength="SDD gầu còm độ II";
                        }  
                        elseif (($data['weigth'][$index] >= $getWeightforLength->neg2SD) && ($data['weigth'][$index] < $getWeightforLength->hai_SD)) {
                            $weightforLength="BT";
                            }               
                        elseif (($data['weigth'][$index] > $getWeightforLength->hai_SD) && ($data['weigth'][$index] <= $getWeightforLength->ba_SD)) {
                            $weightforLength="Thừa cân";
                        } 
                        elseif (($request['weigth'][$index]) > $getWeightforLength->ba_SD) {
                            $weightforLength="Béo phì";
                        } 
                    
                        else {
                            $weightforLength="";
                        }
                        
                    }
                    // $BMI=round($data['weigth']*10000/($data['length']*$data['length']),2);
                
                    $dataInfo[]= [
                        'input_date' => $value,
                        'month' => $month,
                        'length' => $data['length'][$index],  
                        'weigth' => $data['weigth'][$index], 
                        'lengthForAge' => $lengthForAge,
                        'weigthForLength' => $weightforLength,
                        'weigthForAge' => $weightforAge,
                        'user_update' => $user_update
                    ];
                }
                $jsonData = json_encode($dataInfo);
                $dataInsert=[
                    'id_children'=>$id,
                    'data'=>$jsonData 
                ];
                $getparam = Paraminput::where('id_children',$id)->first();
                if($getparam){
                    Paraminput::where('id_children',$id)->update($dataInsert );
                }
                else{
                    Paraminput::insert($dataInsert);
                }
                break;
            case "addParam":
                //dd(123);
                    foreach ($data['input_date'] as $index=> $value) {  
                        $ngaySinh = Carbon::parse($data['birthday']);
                        $ngayBatDauCarbon = Carbon::parse($data['input_date'][$index]);
                        $soNgay = (int)($ngaySinh->diffInDays($ngayBatDauCarbon))/30.4375;
                        $month=(int)round($soNgay);
                        //dd($month);
                        if($data['sex'] ==1){
                            $getHightforAge=LengthForAgeBoy::where('month', $month)->first();
                            $getWeigthforAge=WeightForAgeBoy::where('month', $month)->first();
                            $getWeightforLength=WeightForHeightBoy::where('length',round(($data['length'][$index])))->first();
                            
                            if(($data['weigth'][$index] > $getWeigthforAge->neg2SD) && ($data['weigth'][$index] <= $getWeigthforAge->hai_SD)){
                                $weightforAge="BT";  
                            }
                            
                            elseif (($data['weigth'][$index] >= $getWeigthforAge->neg3SD) && $data['weigth'][$index] < $getWeigthforAge->neg2SD) {
                                $weightforAge="Suy DD độ I";
                            
                            } 
                            elseif ($data['weigth'][$index] < $getWeigthforAge->neg3SD)  {
                                $weightforAge="Suy DD độ II";
                            } 
                            else {
                                $weightforAge="";
                            }
                            //Chiều cao theo tuổi: 
                            if($data['length'][$index] > $getHightforAge->neg2SD){
                                $lengthForAge="BT";
                            }
                            elseif (($data['length'][$index] >= $getHightforAge->neg3SD) && ($data['length'][$index] < $getHightforAge->neg2SD)) {
                                $lengthForAge="Thấp còi độ I";
                            } 
                            elseif ($data['length'][$index] < $getHightforAge->neg3SD)  {
                                $lengthForAge="Thấp còi độ II";
                            } 
                            else {
                                $lengthForAge="";
                            }
                            
                            //Cân nặng theo chiều cao: 
                            if($data['weigth'][$index] < $getWeightforLength->neg3SD){
                                $weightforLength="SDD gầy còm độ I"; 
                            }
                            elseif (($data['weigth'][$index] >= $getWeightforLength->neg3SD) && ($data['weigth'] < $getWeightforLength->neg2SD)) {
                                $weightforLength="SDD gầu còm độ II";
                            }  
                            elseif (($data['weigth'][$index] >= $getWeightforLength->neg2SD) && ($data['weigth'][$index] < $getWeightforLength->hai_SD)) {
                                $weightforLength="BT";
                                }               
                            elseif (($data['weigth'][$index] > $getWeightforLength->hai_SD) && ($data['weigth'][$index] <= $getWeightforLength->ba_SD)) {
                                $weightforLength="Thừa cân";
                            } 
                            elseif (($request['weigth'][$index]) > $getWeightforLength->ba_SD) {
                                $weightforLength="Béo phì";
                            } 
                        
                            else {
                                $weightforLength="";
                            }
                        }
                        else{
                            //dd($data['length']);
                            $getHightforAge=LengthForAgeGirl::where('month', $month)->first();
                            $getWeigthforAge=WeightForAgeGirl::where('month', $month)->first();
                            $getWeightforLength=WeightForHeightGirl::where('length',round(($data['length'][$index])))->first();
                            //dd($getWeightforLength);
                            //cân nặng theo tuổi: 
                            if(($data['weigth'][$index] > $getWeigthforAge->neg2SD) && ($data['weigth'][$index] <= $getWeigthforAge->hai_SD)){
                                $weightforAge="BT";  
                            }
                            
                            elseif (($data['weigth'][$index] >= $getWeigthforAge->neg3SD) && $data['weigth'][$index] < $getWeigthforAge->neg2SD) {
                                $weightforAge="Suy DD độ I";
                            
                            } 
                            elseif ($data['weigth'][$index] < $getWeigthforAge->neg3SD)  {
                                $weightforAge="Suy DD độ II";
                            } 
                            else {
                                $weightforAge="";
                            }
                            //Chiều cao theo tuổi: 
                            if($data['length'][$index] > $getHightforAge->neg2SD){
                                $lengthForAge="BT";
                            }
                            elseif (($data['length'][$index] >= $getHightforAge->neg3SD) && ($data['length'][$index] < $getHightforAge->neg2SD)) {
                                $lengthForAge="Thấp còi độ I";
                            } 
                            elseif ($data['length'][$index] < $getHightforAge->neg3SD)  {
                                $lengthForAge="Thấp còi độ II";
                            } 
                            else {
                                $lengthForAge="";
                            }
                            
                            //Cân nặng theo chiều cao: 
                            if($data['weigth'][$index] < $getWeightforLength->neg3SD){
                                $weightforLength="SDD gầy còm độ I"; 
                            }
                            elseif (($data['weigth'][$index] >= $getWeightforLength->neg3SD) && ($data['weigth'] < $getWeightforLength->neg2SD)) {
                                $weightforLength="SDD gầu còm độ II";
                            }  
                            elseif (($data['weigth'][$index] >= $getWeightforLength->neg2SD) && ($data['weigth'][$index] < $getWeightforLength->hai_SD)) {
                                $weightforLength="BT";
                                }               
                            elseif (($data['weigth'][$index] > $getWeightforLength->hai_SD) && ($data['weigth'][$index] <= $getWeightforLength->ba_SD)) {
                                $weightforLength="Thừa cân";
                            } 
                            elseif (($request['weigth'][$index]) > $getWeightforLength->ba_SD) {
                                $weightforLength="Béo phì";
                            } 
                        
                            else {
                                $weightforLength="";
                            }
                            
                        }
                   //$BMI=round($data['weigth']*10000/($data['length']*$data['length']),2);
                    $dataInfo[]= [
                                'input_date' => $value,
                                'month' => $month,
                                'length' => $data['length'][$index],  
                                'weigth' => $data['weigth'][$index],  
                                'lengthForAge' => $lengthForAge,
                                'weigthForLength' => $weightforLength,
                                'weigthForAge' => $weightforAge,
                                'user_update' => $user_update
                            ];
                    }
                    $dataInsert['id_children']=$id;
                    $jsonData = json_encode($dataInfo);
                  
                    $model = Paraminput::where('id_children', $id)->first();
                    if ($model) {
                        $currentData = json_decode($model->data, true);
                        if (!is_array($currentData)) {
                            $currentData = [];
                        }
                        foreach ($dataInfo as $newItem) {
                            $currentData[] = $newItem; 
                        }
                        $model->data = json_encode($currentData);
                        $model->save();
                    
                    } else {
                        $model = new Paraminput();
                        $model->id_children = $id;
                        $model->data = $jsonData; 
                        $model->save();
                    }
                break;
        }
        return back()->withInput()->with('success','successfully!');
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
