<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Menu;
use App\Models\MenuRole;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   $roles=Role::with('permissions')->get();
        $menus=Menu::get();
        
        return Inertia::render('Admin/Role',[
            'roles'=>$roles,
            'menus'=>$menus,
            'can' => [
                'view' => Auth::user()->checkView(config('permission.access.view_role')),
                'create' => Auth::user()->checkCreate(config('permission.access.create_role')),
                'edit' => Auth::user()->checkEdit(config('permission.access.edit_role')),
                'delete' => Auth::user()->checkDelete(config('permission.access.delete_role')),
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
        $data=$request->validate([
            'name'=>['required','string'],
            'status'=>['nullable','integer'],
            
           ]);
        Role::create($data);
        return back()->withInput()->with('success','Update Role successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
       //dd($request->all());
        $data=$request->validate([
            'name'=>['required','string'],
            'status'=>['nullable','integer'],
            
           ]);
        
        Role::where('id',$request->id)->update($data);

        MenuRole::where('id_role',$request->id)->delete();
        if($request -> id_menu){
            foreach($request -> id_menu as $menu_id){
                $show_='';
                $add_='';
                $edit_='';
                $delete_='';
                $in_='';

                foreach($request->show_ as $sh){
                    if($sh == $menu_id){
                        $show_= $sh;
                    }
                }
                foreach($request->add_ as $ad){
                    if($ad == $menu_id){
                        $add_= $ad;
                    }
                }
                foreach($request->edit_ as $ed){
                    if($ed == $menu_id){
                        $edit_= $ed;
                    }
                }
                foreach($request->delete_ as $del){
                    if($del == $menu_id){
                        $delete_= $del;
                    }
                }
                // foreach($request->in_ as $in){
                //     if($in == $menu_id){
                //         $in_= $in;
                //     }
                // }
                $data1[]=[
                    'id_menu'=>$menu_id,
                    'id_role'=>$request->id,
                    'show_'=>$show_?$show_:null,
                    'add_'=>$add_?$add_:null,
                    'edit_'=>$edit_?$edit_:null,
                    'delete_'=>$delete_?$delete_:null,
                    //'in_'=>$in_?$in_:null,
                    'updated_at'=>date('Y-m-d H:i:s'),
                ];
            }
            MenuRole::insert($data1);
        }
      
        return back()->withInput()->with('success','Update Role successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Role::where('id',$id)->delete();
        return back()->withInput()->with('success','Update Role successfully!');
    }
}
