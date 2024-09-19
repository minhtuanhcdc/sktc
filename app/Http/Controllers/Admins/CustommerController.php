<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use App\Models\Catelory;
use App\Models\Custommer;
use App\Models\Billcustommers;
use App\Models\Billservices;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use App\Models\Post;
use App\Models\CosoModel;
use App\Models\ExchangeFix;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

use DB;
use Auth;

class CustommerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //dd($request->all());
        $wards='';
        $query='';
        $bills='';
        $unconfimred='';
        $unpaid='';
        $hcdcconfimred='';
        $total_pay='';
        $perPage=$request->perPage?$request->perPage:20;
        $termFill=$request->termSearch;
        $currentDate=Carbon::now();
        $startDate=$request->startDate;
        $endDate=$request->endDate;
        $id_post_auth='';
        $id_coso='';
        $getPost='';
        $transfer = $this->transfer();

        $id_coso = Auth()->user()->id_post;
        $admin=Auth()->user();
        $userLogin=$admin->id;
        $tienMat='';
        $chuyenKhoan="";

        $coso=CosoModel::where('id',$id_coso)->first();
        $currentExchangeFix=ExchangeFix::where('status',1)->latest()->first();
        foreach($transfer as $key=>$value){
             if($value['CurrencyCode']){
                 $getTransfer['Transfer']= $value;
             }
        }
        //dd($getTransfer);
        if($request->termDistrict){
            $wards=Ward::where('id_district',$request->termDistrict)->get();
        }
        if($admin->is_admin!=1){ 
           // dd($id_coso); 
                    if (request('termSearch')) {
                        $query = Billcustommers::query();
                        $query
                         ->where('id', 'like', '%' . request('termSearch') . '%')
                         ->orwhereHas('custommer', function($qr) use($termFill){
                            $qr->where('name','like', '%' . $termFill.'%');
                        })->with('custommer')
                        ->where('id_province',$id_province);
                         
                    }
                    if($startDate && $endDate){
                        $bills=Billcustommers::where('user_created',$admin->id)
                                            ->where('user_created',$userLogin)
                                            ->with(['custommer','services','catelogies','cosos','user'])
                                            ->whereDate('created_at','>=',$startDate)
                                            ->whereDate('created_at','<=',$endDate)
                                            ->orderBy('created_at','desc')
                                            ->paginate($perPage)->withQueryString();
                        $tienMat=Billcustommers::where('user_created',$admin->id)
                                            ->where('user_created',$userLogin)
                                            ->whereDate('created_at','>=',$startDate)
                                            ->whereDate('created_at','<=',$endDate)
                                            ->where('pay_cash',1)->count();
                     
                        $chuyenKhoan=Billcustommers::where('user_created',$admin->id)
                                            ->where('user_created',$userLogin)
                                            ->whereDate('created_at','>=',$startDate)
                                            ->whereDate('created_at','<=',$endDate)
                                            ->where('pay_transfer',null)->count();
                    }
                    else{
                        $bills=Billcustommers::where('user_created',$admin->id)
                                            ->where('user_created',$userLogin)
                                            ->with(['custommer','services','catelogies','cosos','user'])
                                            ->whereDate('created_at',$currentDate)
                                            ->orderBy('created_at','desc')
                                            ->paginate($perPage)->withQueryString();
                        $tienMat=Billcustommers::where('user_created',$admin->id)
                        ->where('user_created',$userLogin)
                        ->whereDate('created_at',$currentDate)
                                            ->where('pay_cash',1)->count();
                        $chuyenKhoan=Billcustommers::where('user_created',$admin->id)
                        ->where('user_created',$userLogin)
                        ->whereDate('created_at',$currentDate)
                                            ->where('pay_transfer',1)->count();
                        // $unpaid=Billcustommers::where('id_province',$id_province) ->whereDate('created_at',$currentDate)->where('pay_status',null)->count();
                        // $unconfimred=Billcustommers::where('id_province',$id_province)
                        //                             ->whereDate('created_at',$currentDate)
                        //                             ->where('pay_unconfirm',null)->count();
                       // dd($bills);
                    }
        }
        if($admin->is_admin==1){
            if (request('termSearch')) {
                $query = Billcustommers::query();
                $query
                 ->where('id', 'like', '%' . request('termSearch') . '%')
                 ->orwhereHas('custommer', function($qr) use($termFill){
                    $qr->where('name','like', '%' . $termFill.'%');
                })->with('custommer')
                 ->orwhereHas('posts', function($qr) use($termFill){
                    $qr->where('code','like', '%' . $termFill.'%');
                })->with('posts')
                 ;
            }
            if($startDate && $endDate){
              
                $bills=Billcustommers::with(['custommer','services','catelogies','posts','user'])
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->orderBy('created_at','desc')->paginate($perPage)->withQueryString();
                $tienMat=Billcustommers::
                                    whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_cash',1)->count();
             
                $chuyenKhoan=Billcustommers::
                                    whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_transfer',1)->count();
            }
            else{
                $bills=Billcustommers::with(['custommer','services','catelogies','posts','user'])
                                    ->whereDate('created_at',$currentDate)
                                    ->orderBy('created_at','desc')->paginate($perPage)->withQueryString();
                //$tienMat=Billcustommers::whereDate('created_at',$currentDate)
                                    //->where('pay_cash',1)->count();
             
                //$chuyenKhoan=Billcustommers::whereDate('created_at',$currentDate)
                                    //->where('pay_transfer',1)->count();
            }
        } 
        $filters=[
            'perPage'=>$request->perPage,
            'startDate'=>$request->startDate,
            'endDate'=>$request->endDate
        ];
        return Inertia::render('InputInformation/Index',[
            'bills'=>$query?fn() => $query->with('custommer','posts','catelogies','services','user')->paginate($perPage)->withQueryString():$bills,
            //'bills'=>Billcustommers::with(['custommer','services','catelogies','posts'])->withSum('catelogies','Billservices.don_gia')->orderBy('created_at','desc')->paginate(5),
            'catelogies'=>Catelory::select('id','name','don_gia')->get(),
            'getTransfer'=>$getTransfer,
            'currencyVietcomBank'=>$getTransfer,
            'provinces'=>Province::get(),
            'districts'=>District::get(),
            'wards'=>$wards?$wards:'',
            'filters'=>$filters,
            'tienMat'=>$tienMat?$tienMat:'',
            'chuyenKhoan'=>$chuyenKhoan?$chuyenKhoan:'',
            'total_pay'=>$total_pay,
            'currentExchangeFix'=>$currentExchangeFix?$currentExchangeFix->exchange_usd:'',
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
        dd(123);
        $year = Carbon::now()->year;
        $two_year=substr($year,2,2);
        $data=$request->validate([
            'name'=>[ 'required','string'],
            "qty" => ['nullable'],
            "email" => ['nullable','string'],
            "phone" =>['nullable','string'],
            "mst" => ['nullable','string'],
            "tokhai" => ['nullable','string'],
            "address" => ['nullable','string'],
            "id_post" => ['nullable'],
            "id_province" => ['nullable'],
            "id_district" => ['nullable'],
            "id_ward" => ['nullable'],
            'data'=>['nullable'],         
            'sohieu'=>['nullable'],         
        ],
        [
            'name.required'=>"Tên Không để trống...!",
        ]);
        $userCreate = Auth()->user()->id;
        $id_post = Auth()->user()->id_post;
        $post = Auth()->user()->posts;
        //dd($post->province_code);
        if($id_post){
            try{
                DB::beginTransaction();
    
                $custommer=Custommer::create($data);
                $total=0;
    
                foreach($request->data[0] as $key=>$value){
                   $total = $total + $value['don_gia']*$value['sl'];  
                   
                }
               // dd($request->data[2]);
                //dd($this->convert_number_to_words($request->data[2]));
               
                $Id_bill=$custommer->billcustommer()->insertGetId([
                    'id_custommer'=>$custommer->id,
                    'tokhai'=>$request->tokhai,
                    'sohieu'=>$request->sohieu,
                    'id_province'=>$post?$post->province_code:'',
                    'usd_exchange'=>$request->data[1],
                    'user_created'=> $userCreate,
                    'id_post'=> Auth()->user()->id_post,
                    'total_price'=>$total,
                    'total_pay'=>str_replace('.','', $request->data[2]),
                    //'text_total_pay'=>$this->convert_number_to_words($request->data[2]),
                    'created_at' => date('Y-m-d H:i:s'),
                    ]);
                    $get_seriBille = sprintf("%06s",$Id_bill);
                    Billcustommers::where('id',$Id_bill)->update([
                        'seri_bill'=>$two_year.''.$get_seriBille
                    ]);
                    foreach($request->data[0] as $key=>$value){
                        Billservices::insert([
                            'id_bill'=>$Id_bill,
                            'id_service'=>$value['id_service'],
                            'don_gia'=>$value['don_gia'],
                            'sl'=>$value['sl'],
                        ]);
                    }
      
                DB::commit();
                return back()->withInput()->with('success','Add successfully!');
            }catch(Exception $excepton){
                    DB::rollBack();
                    Log::error('Message:'.$excepton->getMessage().'---Line:'.$excepton->getLine());
                }
                return back()->withInput()->with('success','Add successfully!');
            }
        else{
            return back()->withInput()->with('failure','Không có chức năng nhập!'); 
        }
        
   
    }
    public function storeLocal(Request $request)
    { 
        $dt =  $date = Carbon::now()->format('Ymd');
        $hour =  $date = Carbon::now()->format('H');
        //dd($hour);
        $buoi = $hour >= 12 ? 'pm' : 'am';
        $date = str($dt);
       // $getdata =['data'=>$request->all()];
       $data= $request->validate([
        'name'=>[ 'required','string'],
        "qty" => ['required'],
        "usd_exchange" => ['required'],
        "services" => ['nullable'],
        "email" => ['nullable','string'],
        "phone" =>['nullable','string'],
        "mst" => ['nullable','string'],
        "tokhai" => ['nullable','string'],
        "address" => ['nullable','string'],
        "id_post" => ['nullable'],
        "id_province" => ['nullable'],
        "id_district" => ['nullable'],
        "id_ward" => ['nullable'],
        'data'=>['nullable'],         
        'sohieu'=>['nullable'],         
       ],
        [
            'name.required'=>"Tên Không để trống...!",
            'qty.required'=>"Nhập số lượng vắc xin!",
        ]);
   
        $userCreate = Auth()->user()->id;
        $id_post = Auth()->user()->id_post;
        $post = Auth()->user()->posts;
        $admin = Auth()->user();
     //dd(str_replace('.','',$request->usd_exchange));
      
        if($userCreate || $admin->is_admin==1){
            try{
                DB::beginTransaction();
                $custommer=Custommer::create($data);
                $total=0;
                foreach($request->qty as $key=>$value){
                    if($value != null){ 
                        $services = Catelory::where('id',$key)->first();
                        $total = $total + $services['don_gia']*$value;  
                    }
                }
                //$totalPay = $total*$request->data;
                //dd($total*$request->data);

                // foreach($request->qty as $key=>$value){
                //    $total = $total + $value['don_gia']*$value['sl'];  
                // }
             // dd($total);
                $Id_bill=$custommer->billcustommer()->insertGetId([
                    'id_custommer'=>$custommer->id,
                    'buoi'=>$buoi,
                    'sohieu'=>$request->sohieu,
                   // 'id_province'=>$post?$post->province_code:'',
                    'usd_exchange'=>$request->usd_exchange,
                    'user_created'=> $userCreate,
                    'id_post'=> Auth()->user()->id_post,
                    'total_price'=>$total,
                    'total_pay'=>$total*$request->usd_exchange,
                   //'text_total_pay'=>$this->convert_number_to_words($total),
                    'created_at' => date('Y-m-d H:i:s'),
                    ]);
                    $get_seriBille = sprintf("%05s",$Id_bill);
                    //dd($dt);
                    
                    Billcustommers::where('id',$Id_bill)->update([
                        'seri_bill'=>$date.''.$get_seriBille
                    ]);
                    foreach($request->qty as $key=>$value){
                        if($value != null){ 
                            $services = Catelory::where('id',$key)->first();
                            //dd($services['don_gia']);
                            Billservices::insert([
                                'id_bill'=>$Id_bill,
                                'id_service'=>$key,
                                'don_gia'=>$services['don_gia'],
                                'sl'=>$value,
                            ]);
                        }
                    }
                    // foreach($request->data as $key=>$value){
                    //     Billservices::insert([
                    //         'id_bill'=>$Id_bill,
                    //         'id_service'=>$value['id_service'],
                    //         'don_gia'=>$value['don_gia'],
                    //         'sl'=>$value['sl'],
                    //     ]);
                    // }
                DB::commit();
                return back()->withInput()->with('success','Add successfully!');
            }catch(Exception $excepton){
                    DB::rollBack();
                    Log::error('Message:'.$excepton->getMessage().'---Line:'.$excepton->getLine());
                }
                return back()->withInput()->with('success','Add successfully!');
            }
        else{
            return back()->withInput()->with('failure','Không có chức năng nhập!'); 
        }   
    }
    
    public function updateBill(Request $request)
    {
     //dd($request->all());
        //    $transfer = $this->transfer();  
        //    foreach($transfer as $key=>$value){
        //     if($value['CurrencyCode']){
        //         $getTransfer['Transfer']= $value;
        //     }
        //     }
            //dd($getTransfer['Transfer']['Transfer']);
        $data=$request->validate([
            'name'=>[ 'required','string'],
            "qty" => ['nullable'],
            "email" => ['nullable','string'],
            "phone" =>['nullable','string'],
            "mst" => ['nullable','string'],
            "tokhai" => ['nullable','string'],
            "address" => ['nullable','string'],
            "id_post" => ['nullable'],
            "id_province" => ['nullable'],
            "id_district" => ['nullable'],
            "id_ward" => ['nullable'],
            'data'=>['nullable'],         
        ],
        [
            'name.required'=>"Tên Không để trống...!",
        ]);
        $userLogin= Auth()->user()->id;
        $userGet=BillCustommers::where('user_created',$userLogin)->first();
        $id_post = Auth()->user()->id_post;
        if($userGet || (Auth()->user()->is_admin==1)){
            try{
                DB::beginTransaction();
                $custommer=Custommer::where('id',$request->id_custommer)->update(
                    [
                        'name'=>$request->name,
                        "email" =>$request->email,
                        "phone" =>$request->phone,
                        "mst" =>$request->mst,
                        "address" =>$request->address,
                        "id_province" =>$request->id_province,
                        "id_district" =>$request->id_district,
                        "id_ward" =>$request->id_ward,
                    ]
                );
                //if($request->editChangeService){ 
                    $total=0;
                    foreach($request->data as $key=>$value){
                    $total = $total + $value['don_gia']*$value['sl'];  
                    }
                    //dd((string)$request->data[2]);
    
                    BillCustommers::where('id',$request->id)->update([
                        'tokhai'=>$request->tokhai,
                        //'usd_exchange'=>$getTransfer['Transfer']['Transfer'],
                       // 'usd_exchange'=>$request->data[1],
                        //'user_created'=> $userUpdated,
                        'id_post'=> Auth()->user()->id_post,
                        'total_price'=>$total,
                        'total_pay'=>$total,
                        //'text_total_pay'=>$this->convert_number_to_words($total),
                        'created_at' => date('Y-m-d H:i:s'),
                        ]);
                        Billservices::where('id_bill',$request->id_bill)->delete();
                        foreach($request->data as $key=>$value){
                            Billservices::insert([
                                'id_bill'=>$request->id_bill,
                                'id_service'=>$value['id_service'],
                                'don_gia'=>$value['don_gia'],
                                'sl'=>$value['sl'],
                            ]);
                        }
               // }
                DB::commit();
                return back()->withInput()->with('success','Add successfully!');
            }catch(Exception $excepton){
                    DB::rollBack();
                    Log::error('Message:'.$excepton->getMessage().'---Line:'.$excepton->getLine());
                }
                return back()->withInput()->with('success','Add successfully!');
        }
        else{
            return back()->withInput()->with('success','Bạn không được phép cập nhật!');
        }
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
        dd(123);
       $data=$request->validate([
        'name'=>[ 'required','string'],
        "qty" => ['nullable'],
        "email" => ['nullable','string'],
        "phone" =>['nullable','string'],
        "mst" => ['nullable','string'],
        "tokhai" => ['nullable','string'],
        "address" => ['nullable','string'],
        "id_post" => ['nullable'],
        "id_province" => ['nullable'],
        "id_district" => ['nullable'],
        "id_ward" => ['nullable'],
        'data'=>['nullable'],         
        ],
        [
            'name.required'=>"Tên Không để trống...!",
        ]);
        $total=0;
        $services = Catelory::whereIn('id',$request->services)->select('id','don_gia','name')->get();
        foreach( $services as $key=>$value){
            $total = $total + $value->don_gia;    
        }
        //dd($total);

        $userCreate = Auth()->user()->id;
        $id_post = Auth()->user()->id_post;
        try{
            DB::beginTransaction();
            $custommer=Custommer::where('id',$request->id_custommer)->update(
                [
                    'name'=>$request->name,
                    "email" =>$request->email,
                    "phone" =>$request->phone,
                    "mst" =>$request->mst,
                    "address" =>$request->address,
                    "id_province" =>$request->id_province,
                    "id_district" =>$request->id_district,
                    "id_ward" =>$request->id_ward,
                ]
            );
      
        if($request->editChangeService){
            dd($request->all());
            foreach($request->data[0] as $key=>$value){
            $total = $total + $value['don_gia']*$value['sl'];    
            }
            $Id_bill=$custommer->billcustommer()->insertGetId([
                    'id_custommer'=>$custommer->id,
                    'tokhai'=>$request->tokhai,
                    'usd_exchange'=>$request->data[1],
                    'user_created'=> $userCreate,
                    'id_post'=> Auth()->user()->id_post,
                    'total_price'=>$total,
                    'total_pay'=>$total,
                    //'text_total_pay'=>$this->convert_number_to_words($request->data[2]),
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                foreach($request->data[0] as $key=>$value){
                    Billservices::insert([
                        'id_bill'=>$Id_bill,
                        'id_service'=>$value['id_service'],
                        'don_gia'=>$value['don_gia'],
                        'sl'=>$value['sl'],
                    ]);
                }
                }
            DB::commit();
            return back()->withInput()->with('success','Add successfully!');
        }catch(Exception $excepton){
                DB::rollBack();
                Log::error('Message:'.$excepton->getMessage().'---Line:'.$excepton->getLine());
            }
            return back()->withInput()->with('success','Add successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $admin = Auth()->user();
        $userCreated = Billcustommers::where('user_created', $admin->id)->first()->user_created;

        if($admin->is_admin==1  || $userCreated){ 
            Billcustommers::where('id',$id)->delete();
            Billservices::where('id_bill',$id)->delete();
            return back()->withInput()->with('success','Xóa thành công!');

        }
        else{
            return back()->withInput()->with('failure','Bạn không được phép xóa!');
        }
    }
    public function race_exchange(){
        //$host = "https://portal.vietcombank.com.vn/Usercontrols/TVPortal.TyGia/pXML.aspx?b=10"?"https://portal.vietcombank.com.vn/Usercontrols/TVPortal.TyGia/pXML.aspx?b=10":'';
        //$host = "https://portal.vietcombank.com.vn/UserControls/TVPortal.TyGia/pListTyGia.aspx?txttungay=11/08/2021&BacrhID=1&isEn=False";
         $host = "https://portal.vietcombank.com.vn/Usercontrols/TVPortal.TyGia/pXML.aspx";
        //$response = Http::get('https://portal.vietcombank.com.vn/Usercontrols/TVPortal.TyGia/pXML.aspx');
        // $http = new \GuzzleHttp\Client([
        //     'base_uri' => 'https://portal.vietcombank.com.vn/Usercontrols/TVPortal.TyGia/pXML.aspx',
        //     'verify' => base_path('cacert.pem'),
        // ]);
        
        // $response = Http::get('$https://portal.vietcombank.com.vn/Usercontrols/TVPortal.TyGia/pXML.aspx');
        // dd($response);
     $fileContents= file_get_contents($host);
//dd($fileContents);
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
    public static function convert_number_to_words($value) {

        $number = str_replace('.','', $value);
       // dd($test);
        // $hyphen      = ' ';//Gach noi
        // $conjunction = ' ';//lien ket
        // $separator   = ' ';//Phan cach
        // $negative    = 'negative ';
        // $decimal     = ' ngàn ';//point


        $hyphen      = ' ';
		$conjunction = ' ';
		$separator   = ' ';
		$negative    = 'âm ';
		$decimal     = ' phẩy ';
		$one		 = 'mốt';
		$ten         = 'lẻ';
        $dictionary  = array(
            0                   => 'không',
            1                   => 'một',
            2                   => 'hai',
            3                   => 'ba',
            4                   => 'bốn',
            5                   => 'năm',
            6                   => 'sáu',
            7                   => 'bảy',
            8                   => 'tám',
            10                  => 'mười',
            11                  => 'mười một',
            12                  => 'mười hai',
            13                  => 'mười ba',
            14                  => 'mười bốn',
            15                  => 'mười lăm',
            16                  => 'mười sáu',
            17                  => 'mười bảy',
            18                  => 'mười tám',
            19                  => 'mười chín',
            20                  => 'hai mươi',
            30                  => 'ba mươi',
            40                  => 'bốn mươi',
            50                  => 'năm mươi',
            60                  => 'sáu mươi',
            70                  => 'bảy mươi',
            80                  => 'tám mươi',
            90                  => 'chín mươi',
            100                 => 'trăm',
            1000                => 'ngàn',
            1000000             => 'triệu',
            1000000000          => 'tỷ',
            1000000000000       => 'nghìn tỷ',
            1000000000000000    => 'ngàn triệu triệu',
            1000000000000000000 => 'tỷ tỷ'
        );
        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . Self::convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . Self::convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = Self::convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= Self::convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }
    public function payTransfer($id){
       //dd('chuyen khoan');
        Billcustommers::where('id',$id)->update([
            'pay_status'=>1,
            'pay_transfer'=>1,
            'pay_cash'=>0
        ]);
        return back()->withInput()->with('success','Ghi nhận thành công!');
     }
    public function payCash($id){
       // dd('tiền mặt');
        Billcustommers::where('id',$id)->update([
            'pay_status'=>1,
            'pay_transfer'=>0,
            'pay_cash'=>1
        ]);
        return back()->withInput()->with('success','Ghi nhận thành công!');
     }
}
