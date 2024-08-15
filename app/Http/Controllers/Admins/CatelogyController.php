<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Catelory;
use App\Models\CatelogyGroup;
use Auth;

class CatelogyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->perPage?:20;
        $catelogies=Catelory::with('group')->paginate($perPage)->withQueryString();
        $groups=CatelogyGroup::get();
        $termFill=$request->termSearch;
        $query = "";
        if (request('termSearch')) {
            $query = Catelory::query();
            $query
             ->where('name', 'like', '%' . request('termSearch') . '%')
             ->orWhere('code', 'like', '%' . request('termSearch') . '%')
             ->orwhereHas('group', function($qr) use($termFill){
                $qr->where('name','like', '%' . $termFill.'%');
            })->with('group')
             ->orwhereHas('group', function($qr) use($termFill){
                $qr->where('id',$termFill);
            })->with('group')
             ;
        }
        $fillters=[
            'perPage'=>$perPage
        ]; 
        return Inertia::render('Danhmuc/Catelogy',
            [   
                'catelogies'=>$query?fn() => $query->with('group')->paginate($perPage)->withQueryString():$catelogies,
                'groups'=>$groups,
                'fillters'=>$fillters,
                'can' => [
                    'view' => Auth::user()->checkView(config('permission.access.view_catalogy')),
                    'create' => Auth::user()->checkCreate(config('permission.access.create_catalogy')),
                    'edit' => Auth::user()->checkEdit(config('permission.access.edit_catalogy')),
                    'delete' => Auth::user()->checkDelete(config('permission.access.delete_catalogy')),
                ],
            ]);
    }
    public function banggia(Request $request)
    {
        $perPage = $request->perPage?:20;
        $catelogies=Catelory::with('group')->paginate($perPage)->withQueryString();
        $groups=CatelogyGroup::get();
        $termFill=$request->termSearch;
        $query = "";
        if (request('termSearch')) {
            $query = Catelory::query();
            $query
             ->where('name', 'like', '%' . request('termSearch') . '%')
             ->orWhere('code', 'like', '%' . request('termSearch') . '%')
             ->orwhereHas('group', function($qr) use($termFill){
                $qr->where('name','like', '%' . $termFill.'%');
            })->with('group')
             ->orwhereHas('group', function($qr) use($termFill){
                $qr->where('id',$termFill);
            })->with('group')
             ;
        }
        $fillters=[
            'perPage'=>$perPage
        ]; 
        return Inertia::render('Danhmuc/Banggia',
            [   
                'catelogies'=>$query?fn() => $query->with('group')->paginate($perPage)->withQueryString():$catelogies,
                'groups'=>$groups,
                'fillters'=>$fillters,
                'can' => [
                    'view' => Auth::user()->checkView(config('permission.access.view_catalogy')),
                    'create' => Auth::user()->checkCreate(config('permission.access.create_catalogy')),
                    'edit' => Auth::user()->checkEdit(config('permission.access.edit_catalogy')),
                    'delete' => Auth::user()->checkDelete(config('permission.access.delete_catalogy')),
                ],
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
       $data= $request->validate(
            [
                'name'=>['required','string'],
                'code'=>['nullable'],
                'id_group'=>['required','integer'],
                'don_gia'=>['required','numeric'] ,
                'donvi_tinh'=>['required','string'],
                //'origin_price'=>['required','numeric'],
            ],
            [
                'name.required'=>"Tên Không để trống",
                'id_group.required'=>'Chọn Nhóm danh mục',
                'don_gia.required'=>'Nhập đơn giá',
                'don_gia.numeric'=>'Đơn giá phải là số',
                //'origin_price.numeric'=>'Đơn giá phải là số',
                //'donvi_tinh.required'=>'Nhập đơn vị tính'
            ]
        );
       Catelory::create($data);
       return back()->withInput()->with('success','Create successfully!');
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
    public function update(Request $request,$id)
    {
        $data= $request->validate([
            'name'=>['required','string'],
            'code'=>['nullable'],
            'id_group'=>['required','integer'],
            'don_gia'=>['required','numeric'] ,
            'donvi_tinh'=>['required','string'],
            //'origin_price'=>['required','numeric'],
           
        ],
        [
            'name.required'=>'Tên không để trống',
            'id_group.required'=>'Chọn nhóm danh mục',
            'donvi_tinh.required'=>'Đơn vị tính không để trống',
            'don_gia.required'=>'Đơn giá không để trống',
            'don_gia.numeric'=>'Đơn giá phải là số',
            //'origin_price.required'=>'Nhập giá vốn',
            //'origin_price.numeric'=>'Đơn giá phải là số',
        ]
        );
         
         
           Catelory::where('id',$id)->update($data);
         return back()->withInput()->with('success','Update successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth()->user();
        if($user->username == 'administrator' || $user->username == 'minhtuan'){
            Catelory::where('id',$id)->delete();
            return back()->withInput()->with('success','Delete successfull!');
        }
        else{
            return back()->withInput()->with('failure','Không xóa được!');
        }
    }
    public function messages(){
        return[
            'don_gia.required'=>'đơn giá',
            'don_gia.numeric'=>'đơn giá phải là số',
           
        ];
    }
}
