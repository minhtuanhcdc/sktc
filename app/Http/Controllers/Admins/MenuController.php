<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Models\fs;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use App\Models\Menu;


class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   //$transferExchange='hehe';
        $menues=Menu::orderby('order','asc')->get();
       
        return Inertia::render('Admin/Menu',[
            'menus'=>$menues,
            
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
        // $data= Validator::make($request->all(), 
        // [
        //     'name'=>'required|max:255',
        //     'id_parent'=>'nullable|integer'
        // ]);

        $data=$request->validate([
            'name'=>['required','string'],
            'id_parent'=>['nullable','integer'],
            'icon'=>['nullable','string'],
            'menu_group'=>['nullable','integer'],
            'url'=>['nullable','string'],
            'status'=>['nullable'],
            
           ]);
           $data['status']=$request->status?$request->status:0;
       // dd($data);
        Menu::insert($data);

        return back()->withInput()->with('success','Create Menu successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(fs $fs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(fs $fs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
       //dd($request->all());
        $data=$request->validate([
            'name'=>['required','string'],
            'id_parent'=>['nullable','integer'],
            'icon'=>['nullable','string'],
            'menu_group'=>['nullable','integer'],
            'url'=>['nullable','string'],
            'status'=>['nullable'],
            
           ]);
        Menu::where('id',$id)->update($data);
        return back()->withInput()->with('success','Update Menu successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Menu::where('id',$id)->delete();
        return back()->withInput()->with('success','Delete Menu successfully!');
    }
}
