<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   
    public function index()
    { 
       //$currencyWolrd = json_decode(file_get_contents('http://apilayer.net/api/live?access_key=1edb4aaf37b727c600cfe644e360d747&currencies=VND&source=USD&format=1'));
       //$result_array=$this->race_exchange();
      // $transfer = $this->transfer();
    //    foreach($transfer as $key=>$value){
    //         if($value['CurrencyCode']){
    //             $getTransfer['Transfer']= $value;
    //         }
    //    }
       
       //dd($getTransfer['Transfer']);
        return Inertia::render('Dashboard',
        [
            //'currencyWolrd'=>$currencyWolrd,
            //'currencyVietcomBank'=>$result_array,
           // 'transfer_race'=>$getTransfer
        ]
        );
    }
    public function indexExchange()
    { 
       $currencyWolrd = json_decode(file_get_contents('http://apilayer.net/api/live?access_key=1edb4aaf37b727c600cfe644e360d747&currencies=VND&source=USD&format=1'));
       $result_array=$this->race_exchange();
       $transfer = $this->transfer();
       foreach($transfer as $key=>$value){
            if($value['CurrencyCode']){
                $getTransfer['Transfer']= $value;
            }
       }
       
       //dd($getTransfer['Transfer']);
        return Inertia::render('Home/Index',
        [
            'currencyWolrd'=>$currencyWolrd,
            'currencyVietcomBank'=>$result_array,
            'transfer_race'=>$getTransfer
        ]
        );
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
    public function race_exchange(){
        $host = "https://portal.vietcombank.com.vn/Usercontrols/TVPortal.TyGia/pXML.aspx?b=10";
        $fileContents= file_get_contents($host);
        $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
        $fileContents = trim(str_replace('"', "'", $fileContents));
        $simpleXml = simplexml_load_string($fileContents);
        $json = json_encode($simpleXml);
        $result_array = json_decode($json, true);
        return $result_array;
    }
    public function transfer(){
        $result = $this->race_exchange();
        foreach ($result as $key=>$value) {
            $attrs[] = $value;
           //echo $attrs['campaignID'];
           
            // and so on
        }
        foreach ($attrs['1'] as $key => $value) {
       
            $getTransfer[] = $value['@attributes'];
       }
        return $getTransfer;
    }

}
