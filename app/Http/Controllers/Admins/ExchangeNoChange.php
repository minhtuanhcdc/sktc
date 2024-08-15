<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExchangeFix;
use Inertia\Inertia;
use Auth;

class ExchangeNoChange extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $exchanges=ExchangeFix::with('user')->orderBy('id','desc')->paginate(10);

        return Inertia::render('ExchangFix/Index',[
            'exchanges'=>$exchanges
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
        $data = $request->validate([
            'exchange_usd'=>['required','string'],
            'status'=>['nullable'],
           ]);
           $data['id_user']=$id_user;
           $data['status']=1;
           ExchangeFix::create($data);
           return back()->withInput()->with('success','Create Tỉ giá successfully!');
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
    public function update($id)
    {
        //dd('disable');
        ExchangeFix::where('id',$id)->update([
            'status'=>0
        ]);
        return back()->withInput()->with('success','Create Tỉ giá successfully!');
    }
    public function clockExchange(Request $request)
    {
       
        ExchangeFix::where('status',1)->update([
            'status'=>0
        ]);
        return back()->withInput()->with('success','Create Tỉ giá successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
