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
use Illuminate\Support\Facades\Validator;

class InputInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $rq)
    {
        $wards='';
        if($rq->termDistrict){
            $wards=Ward::where('id_district',$$rq->termDistrict)->get();
        }
        return Inertia::render("InputInformation/Index",[
            'provinces'=>Province::get(),
            'districts'=>District::get(),
            'wards'=>$wards?$wards:'',
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
        /*
                     madinhdanh:'',
                    matiemchung:'',
                    weightbirth:'',
                    weigth:'',
                    length:'',
                    paramdate:'',
                    name:'',
                    parent:"",
                    email:'',
                    phone:'',
                    address:'',
                    id_province:'',
                    id_district:'',
                    id_ward:'',
        */ 
       //dd($request->all());
       $id_user = Auth()->user()->id;
       $data=$request->validate([
        'name'=>['required','string'],
        'birthday'=>['required','string'],
        'sex'=>['required','integer'],
        'parent'=>['required','string'],
        'madinhdanh'=>['nullable','string'],
        'matiemchung'=>['nullable','string'],
        'weightbirth'=>['nullable','numeric'],
        'address'=>['required','string'],
        'id_province'=>['required','integer'],
        'id_district'=>['required','integer'],
        'id_ward'=>['required','integer'],
       
       ]);
       //$data['status']=$request->status?$request->status:0;
         $data['id_user']=$id_user;
        $id_children=Infobase::insertGetId($data);
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
}
