<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use App\Imports\PostImport;
use App\Imports\ProvinceImport;
use App\Imports\DistrictImport;
use App\Imports\WardImport;
use App\Imports\ProvincePostImport;
use App\Imports\VaccineImport;
use App\Imports\LengthForAgeBoyImport;
use App\Imports\LengthForAgeGirlImport;
use App\Imports\WeightForAgeBoyImport;
use App\Imports\WeightForAgeGirlImport;
use App\Imports\WeightForHeightBoyImport;
use App\Imports\WeightForHightGirlImport;
use App\Imports\InfoImport;


class ImportController extends Controller
{
    public function importPost(Request $request)
    {
        //dd($request->all());
       Excel::import(new PostImport, $request->file);
        return back()->withInput()->with('success','Add  successfully!');
    }
    public function importProvince(Request $request)
    {
        //dd($request->all());
       Excel::import(new ProvinceImport, $request->file);
        return back()->withInput()->with('success','Add  successfully!');
    }
    public function importDistrict(Request $request)
    {
        //dd($request->all());
       Excel::import(new DistrictImport, $request->file);
        return back()->withInput()->with('success','Add  successfully!');
    }
    public function importWard(Request $request)
    {
        //dd($request->all());
       Excel::import(new WardImport, $request->file);
        return back()->withInput()->with('success','Add  successfully!');
    }
    public function provincePosts(Request $request)
    {
        //dd($request->all());
       Excel::import(new ProvincePostImport, $request->file);
        return back()->withInput()->with('success','Add  successfully!');
    }
    public function importDanhmuc(Request $request)
    {
        //dd($request->all());
       Excel::import(new VaccineImport, $request->file);
        return back()->withInput()->with('success','Add  successfully!');
    }
    public function importLenghtForAgeBoy(Request $request)
    {
        
       Excel::import(new LengthForAgeBoyImport, $request->file);
        return back()->withInput()->with('success','Add  successfully!');
    }
    public function importLenghtForAgeGirl(Request $request)
    {
        Excel::import(new LengthForAgeGirlImport, $request->file);
        return back()->withInput()->with('success','Add  successfully!');
    }
    public function importWeghttForAgeBoy(Request $request)
    {
        Excel::import(new WeightForAgeBoyImport, $request->file);
        return back()->withInput()->with('success','Add  successfully!');
    }
    public function importWeghttForAgeGirl(Request $request)
    {
        
        Excel::import(new WeightForAgeGirlImport, $request->file);
        return back()->withInput()->with('success','Add  successfully!');
    }
    public function importWeghttForHightBoy(Request $request)
    {
       
        Excel::import(new WeightForHeightBoyImport, $request->file);
        return back()->withInput()->with('success','Add  successfully!');
    }
    public function importWeghttForHightGirl(Request $request)
    {
       
        Excel::import(new WeightForHightGirlImport, $request->file);
        return back()->withInput()->with('success','Add  successfully!');
    }
    public function importInfomation(Request $request)
    {
       //dd($request->all());
        $import = new InfoImport;
        Excel::import( $import, $request->file);
       $duplicates = $import->getDuplicates();
        if($duplicates){
            return back()->withInput()->with('duplicates',$duplicates); 
        }
        else{
            return back()->withInput()->with('success','Thanh cong!');
        }
       

    }
}
