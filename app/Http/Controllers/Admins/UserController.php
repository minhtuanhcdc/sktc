<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Ward;
use App\Models\District;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $rq)
    {
        
        $districts = District::where('id_province',79)->select('id','name','code')->get();
        $useLogin = Auth()->user();
        $ward='';
        if($rq->termDistrict){
           $ward = Ward::Where('id_district',$rq->termDistrict)->get();
        }
        if($useLogin->username == 'administrator'||$useLogin->username == 'minhtuan'){
            $roles = Role::select('id','name')->get();
            $users = User::with('role','ward','district')->paginate(15);
        }
        else{
            $roles = Role::where('name','!=','administrator')->select('id','name')->get();
            $users = User::where('username','!=','administrator')->where('username','!=','minhtuan')->with('role')->paginate(15);
        }
        return Inertia::render('Admin/User',[
            'users'=>$users,
            'roles'=>$roles,
            'districts'=>$districts,
            'wards'=>$ward?$ward:'',
            'can' => [
                'view' => Auth::user()->checkView(config('permission.access.view_user')),
                'create' => Auth::user()->checkCreate(config('permission.access.create_user')),
                'edit' => Auth::user()->checkEdit(config('permission.access.edit_user')),
                'delete' => Auth::user()->checkDelete(config('permission.access.delete_user')),
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
        $data= $request->validate([
            'name'=>['required','string'],
            'username'=>['required','unique:users','string'],
            'email'=>'required|email',
            'id_post'=>['nullable'],
            'id_role'=>['nullable'],
            'status'=>['nullable'],
        ],
        [
            'name.required'=>'Tên không để trống',
            'username.required'=>'Nhập username',
            'email.required'=>'Nhập email',
        ]
        );   
            $data['password']= Hash::make('246357@');
            $getID=User::insertGetId($data);
            RoleUser::where('user_id',$getID)->insert([
                'user_id'=>$getID,
                'role_id'=>$request->id_role
            ]);

          //dd($getID); 
         return back()->withInput()->with('success','Update successfully!');
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
    public function update(Request $request, $id)
    {
        //dd($request->all());
        $data= $request->validate([
            'name'=>['required','string'],
            'username'=>['required','string'],
            'email'=>'required|email',
            'id_ward'=>['nullable'],
            'id_district'=>['nullable'],
            'id_role'=>['nullable'],
            'status'=>['nullable'],
        ],
        [
            'name.required'=>'Tên không để trống',
            'username.required'=>'Nhập username',
            'email.required'=>'Nhập email',
          
        ]
        );  
   
        User::where('id',$id)->update($data);
        RoleUser::where('user_id',$id)->delete();
        RoleUser::where('user_id',$id)->insert([
            'user_id'=>$id,
            'role_id'=>$request->id_role
          ]);
         return back()->withInput()->with('success','Update successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $isAdmin = Auth()->user();
        if($isAdmin->username =='minhtuan'){
            User::where('id',$id)->delete();
            return back()->withInput()->with('success','Xóa thành công');
        }
        else
        {
            return back()->withInput()->with('failure','Không được phép xóa!');
        }
    }
}
