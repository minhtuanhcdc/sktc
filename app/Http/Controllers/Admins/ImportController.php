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
}
