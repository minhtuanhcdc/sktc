<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LengthForAgeBoy;
use Inertia\Inertia;

class LenghtForAgeBoyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lengthforages= LengthForAgeBoy::paginate(10);
      
        return Inertia::render('BangSoDo/Lenght_age_boy',[
            'lengthforages'=>$lengthforages
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
        $filds=$request->validate(
                [
                    'month'=>['required'],
                    'L'=>['required'],
                    'M'=>['required'],
                    'S'=>['required'],
                    'SD'=>['required'],
                    'neg3SD'=>['required'],
                    'neg2SD'=>['required'],
                    'neg1SD'=>['required'],
                    'median'=>['required'],
                    'mot_SD'=>['required'],
                    'hai_SD'=>['required'],
                    'ba_SD'=>['required'],
                    'status'=>['nullable'],            
                ],
                [
                    'month.required'=>"Nhập tháng",
                    'L.required'=>'Nhập L',
                    'M.required'=>'Nhập M',
                    'S.required'=>'Nhập S',
                    'SD.required'=>'Nhập SD',
                    'neg3SD.required'=>'Nhập -3SD',
                    'neg2SD.required'=>'Nhập -2SD',
                    'neg1SD.required'=>'Nhập -1SD',
                    'median.required'=>'Nhập Median',
                    'mot_SD.required'=>'Nhập 1SD',
                    'hai_SD.required'=>'Nhập 2SD',
                    'ba_SD.required'=>'Nhập 3SD',
                ]
            );
            $month=LengthForAgeBoy::where('month',$request->month)->first();
            
           if(!$month){
               LengthForAgeBoy::create($filds);
               return back()->withInput()->with('success','Create successfully!');
           }
           else{
            return back()->withInput()->with('failure','Có dữ liệu');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
