<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Post;
use App\Models\Role;
use Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       /// $posts="";
        $perPaper=$request->perPage?$request->perPage:10;
        $query='';
        if (request('term')) {
            $query = Post::query();
            $query
                
                ->where('code', 'like', '%' . request('term') . '%')
                ->orWhere('name', 'like', '%' . request('term') . '%');
        }
        $post = Post::orderBy('code','asc')->paginate($perPaper)->withQueryString();
       
        $filters=[
            'perPage'=>$request->perPage
            
        ];
       
      
        return Inertia::render('Post/Post',[
         'posts'=>$query?fn() => $query->paginate($perPage)->withQueryString():$post,
         'filters'=>$filters,
         'can' => [
            'view' => Auth::user()->checkView(config('permission.access.view_post')),
            'create' => Auth::user()->checkCreate(config('permission.access.create_post')),
            'edit' => Auth::user()->checkEdit(config('permission.access.edit_post')),
            'delete' => Auth::user()->checkDelete(config('permission.access.delete_post')),
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
            'code'=>['required','string'],
            'address'=>['nullable'],
            'phone'=>['nullable'] ,
           
            ],
            [
                'name.required'=>"Tên Không để trống",
                'code.required'=>'Nhập mã bưu cục',
            ]
        );
       Post::create($data);
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
    public function update(Request $request, string $id)
    {
        $data= $request->validate(
            [
            'name'=>['required','string'],
            'code'=>['required','string'],
            'address'=>['nullable'],
            'phone'=>['nullable'] ,
           
            ],
            [
                'name.required'=>"Tên Không để trống",
                'code.required'=>'Nhập mã bưu cục',
            ]
        );
        Post::where('id',$id)->update($data);
        return back()->withInput()->with('success','Update successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
