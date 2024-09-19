<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\WeightForHeightBoy;
use Inertia\Inertia;

class WeightForHightBoyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $weight_height_boys = WeightForHeightBoy::paginate(25);
        return Inertia::render('BangSoDo/Weight_height_boy',[
            "weight_height_boys"=>$weight_height_boys
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
