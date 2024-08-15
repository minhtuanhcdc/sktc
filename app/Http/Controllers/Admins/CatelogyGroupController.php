<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CatelogyGroup;
use Inertia\Inertia;
use Auth;

class CatelogyGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categolies = CatelogyGroup::paginate(15);

        return Inertia::render('Danhmuc/CatelogyGroup',
        [
            'catelogies'=>$categolies,
            'can' => [
                'view' => Auth::user()->checkView(config('permission.access.view_catalogy_group')),
                'create' => Auth::user()->checkCreate(config('permission.access.create_catalogy_group')),
                'edit' => Auth::user()->checkEdit(config('permission.access.edit_catalogy_group')),
                'delete' => Auth::user()->checkDelete(config('permission.access.delete_catalogy_group')),
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
       $data = $request->validate([
        'name'=>['required','string'],
        'content'=>['required','string']
       ]);
    
       CatelogyGroup::create($data);
       return back()->withInput()->with('success','Create Catelogy successfully!');
        
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
        $data = $request->validate([
            'name'=>['required','string'],
            'content'=>['required','string']
           ]);
        
           CatelogyGroup::where('id',$id)->update($data);

           return back()->withInput()->with('success','Update successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth()->user();
        if($user->username == 'administrator' || $user->username == 'minhtuan'){
            CatelogyGroup::where('id',$id)->delete();
            return back()->withInput()->with('success','Delete successfull!');
        }
        else{
            return back()->withInput()->with('failure','Không xóa được!');
        }
       
    }
}
