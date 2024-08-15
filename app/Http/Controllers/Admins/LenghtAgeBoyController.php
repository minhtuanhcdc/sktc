<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Models\LenghtAgeBoy;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LenghtAgeBoyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('BangSoDo/Lenght_age_boy');
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
            'month'=>['required','string'],
            'am_3SD'=>['required','unique:users','string'],
            'am_2SD'=>'required|email',
            'am_1SD'=>['nullable'],
            'median'=>['nullable'],
            'mot_SD'=>['nullable'],
            'hai_SD'=>['nullable'],
            'ba_SD'=>1,
            'status'=>['nullable'],
          
        ],
        [
            'month.required'=>'Tên không để trống',
            
        ]
        );   
        dd($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(LenghtAgeBoy $lenghtAgeBoy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LenghtAgeBoy $lenghtAgeBoy)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LenghtAgeBoy $lenghtAgeBoy)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LenghtAgeBoy $lenghtAgeBoy)
    {
        //
    }
}
