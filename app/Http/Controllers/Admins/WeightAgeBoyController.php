<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WeghtAgeBoy;
use Inertia\Inertia;
use Auth;

class WeightAgeBoyController extends Controller
{
    public function index(){
        // $weight_age_boys = WeghtAgeBoy::paginate(15);
        // return Inertia::render('BangSoDo/Weight_age_boy',[
        //     'weight_age_boys'=>$weight_age_boys,
        // ]); 

    }
    public function store(Request $request){
        dd($request->all());
    }
}
