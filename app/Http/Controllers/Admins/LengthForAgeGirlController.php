<?php

namespace App\Http\Controllers\Admins;

use App\Models\LengthForAgeGirl;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Inertia\Inertia;

class LengthForAgeGirlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lengthforages= LengthForAgeGirl::paginate(20);
      
        return Inertia::render('BangSoDo/Lenght_age_girl',[
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(LengthForAgeGirl $lengthForAgeGirl)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LengthForAgeGirl $lengthForAgeGirl)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LengthForAgeGirl $lengthForAgeGirl)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LengthForAgeGirl $lengthForAgeGirl)
    {
        //
    }
}
