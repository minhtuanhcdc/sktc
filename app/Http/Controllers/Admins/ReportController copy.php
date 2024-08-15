<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Catelory;
use App\Models\Custommer;
use App\Models\Billcustommers;
use App\Models\Post;
use App\Models\ProvincePost;
use Carbon\Carbon;
use Auth;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       // dd($request->all());
        $currentDate = Carbon::now()->toDateString();
       $startDate = $request->startDate;
       $endDate = $request->endDate;
       $id_post = $request->id_post;
       $id_service = $request->id_service;
       $perPage = $request->perPage?$request->perPage:50;
       $termFill = $request->term;
       $bills='';
       $query='';
       $sum_price="";
       $unpaid='';
       $text_price="";
       $total_pay='';
        $unconfimred='';
        $hcdcconfimred='';
     
       $id_post_auth = Auth()->user()->id_post;
       $admin = Auth()->user();
       $id_province= Auth()->user()->posts?Auth()->user()->posts->province_code:'';
    
       $post=Post::where('id',$id_post_auth)->first();
       $getPost=$post?$post->code:'';

        if($id_post_auth){
            $condition_fill=false;
            if($id_province == $getPost){
                if (request('term')) {
                    
                    $query = Billcustommers::query();
                    $query
                        
                        ->where('seri_bill', 'like', '%' . request('term') . '%')
                        ->orwhereHas('custommer', function($qr) use($termFill){
                            $qr->where('name','like', '%' . $termFill.'%');
                        })->with('custommer')
                        ->where('id_province',$id_province)->where('pay_status',1);
                }
                    $posts=Post::where('province_code',$id_province)->select('id','name','code')->get();
                if($id_post){
                    
                    if($startDate && $endDate && !$id_service){
                        if($request->pay==''){
                            $bills=Billcustommers::where('id_province',$id_province)
                                                ->where('id_post',$id_post)
                                                ->with(['custommer','services','catelogies','posts'])
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->paginate($perPage)->withQueryString();
                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);

                            $unpaid=Billcustommers::where('pay_status',null)
                                                    ->where('id_province',$id_province)
                                                    ->where('id_post',$id_post)
                                                    ->whereDate('created_at','>=',$startDate)
                                                    ->whereDate('created_at','<=',$endDate)
                                                    ->count();
                                                    //dd($unpaid);
                            $total_pay=Billcustommers::where('pay_status',1)
                                                    ->where('id_province',$id_province)
                                                    ->where('id_post',$id_post)
                                                    ->whereDate('created_at','>=',$startDate)
                                                    ->whereDate('created_at','<=',$endDate)
                                                    ->count();
                            $unconfimred=Billcustommers::where('pay_status',1)
                                                        ->where('id_province',$id_province)
                                                        ->where('id_post',$id_post)
                                                        ->whereDate('created_at','>=',$startDate)
                                                        ->whereDate('created_at','<=',$endDate)
                                                        ->where('pay_unconfirm',null)
                                                        ->count();
                            $hcdcconfimred=Billcustommers::where('pay_status',1)
                                                        ->where('id_province',$id_province)
                                                        ->where('id_post',$id_post)
                                                        ->whereDate('created_at','>=',$startDate)
                                                        ->whereDate('created_at','<=',$endDate)
                                                        ->where('pay_unconfirm',1)->count();

                        }
                        if($request->pay=='pay'){
                            $bills=Billcustommers::where('id_province',$id_province)
                                                ->with(['custommer','services','catelogies','posts'])
                                                ->where('id_post',$id_post)
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->where('pay_status',1)
                                                ->paginate($perPage)->withQueryString();
                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);
                            $total_pay=Billcustommers::where('pay_status',1)
                                                    ->where('id_province',$id_province)
                                                    ->where('id_post',$id_post)
                                                    ->whereDate('created_at','>=',$startDate)
                                                    ->whereDate('created_at','<=',$endDate)
                                                    ->count();
                            $unconfimred=Billcustommers::where('pay_status',1)
                                                        ->where('id_province',$id_province)
                                                        ->where('id_post',$id_post)
                                                        ->whereDate('created_at','>=',$startDate)
                                                        ->whereDate('created_at','<=',$endDate)
                                                        ->where('pay_unconfirm',null)
                                                        ->count();
                            $hcdcconfimred=Billcustommers::where('pay_status',1)
                                                        ->where('id_province',$id_province)
                                                        ->whereDate('created_at','>=',$startDate)
                                                        ->whereDate('created_at','<=',$endDate)
                                                        ->where('pay_unconfirm',1)->count();
            

                        }
                        if($request->pay=='notpay'){
                            $bills=Billcustommers::where('id_province',$id_province)
                                                ->with(['custommer','services','catelogies','posts'])
                                                ->where('id_post',$id_post)
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->where('pay_status',null)
                                                ->paginate($perPage)->withQueryString();
                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);
                            $unpaid=Billcustommers::where('pay_status',null)
                                                    ->where('id_province',$id_province)
                                                    ->whereDate('created_at','>=',$startDate)
                                                    ->whereDate('created_at','<=',$endDate)
                                                    ->count();                        
                        }         
                    }
                    if($id_service && $startDate && $endDate){
                        //dd($request->pay);
                        if($request->pay==''){
                            
                            $bills=Billcustommers::where('id_province',$id_province)->with(['custommer','services','catelogies','posts'])
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->where('id_post',$id_post)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })->paginate($perPage)->withQueryString();
                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);
                            
                            $unpaid=Billcustommers::where('id_province',$id_province)
                                                ->where('pay_status',null)
                                                ->where('id_post',$id_post)
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })
                                                ->count();
                        }
                        if($request->pay=='pay'){
                            $bills=Billcustommers::where('id_province',$id_province)->with(['custommer','services','catelogies','posts'])
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->where('id_post',$id_post)
                                                ->where('pay_status',1)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })->paginate($perPage)->withQueryString();
                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);
                            
                            $total_pay=Billcustommers::where('id_province',$id_province)
                                                ->where('pay_status',1)
                                                ->where('id_post',$id_post)
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })
                                                ->count();
                            $unconfimred=Billcustommers::where('id_province',$id_province)
                                                ->where('pay_status',1)
                                                ->where('pay_unconfirm',null)
                                                ->where('id_post',$id_post)
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })->count();
                            $hcdcconfimred=Billcustommers::where('id_province',$id_province)
                                                ->where('pay_status',1)
                                                ->where('pay_unconfirm',1)
                                                ->where('id_post',$id_post)
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })
                                                ->count();
                        }

                        if($request->pay=='notpay'){
                            $bills=Billcustommers::where('id_province',$id_province)->with(['custommer','services','catelogies','posts'])
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->where('id_post',$id_post)
                                                ->where('pay_status',null)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })->paginate($perPage)->withQueryString();
                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);
                            
                            $unpaid=Billcustommers::where('id_province',$id_province)
                                                ->where('pay_status',null)
                                                ->where('id_post',$id_post)
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })
                                                ->count();
                            
                        }
                            
                    
                    }
                    if(!$id_service && !$startDate && !$endDate){
                            $bills=Billcustommers::where('id_province',$id_province)->with(['custommer','services','catelogies','posts'])
                                                ->whereDate('created_at',$currentDate)
                                                ->paginate($perPage)->withQueryString();

                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);
                            
                            $unpaid=Billcustommers::where('id_province',$id_province)
                                                ->where('pay_status',null)
                                                ->whereDate('created_at',$currentDate)
                                                ->count();
                            $total_pay=Billcustommers::where('id_province',$id_province)
                                                ->where('pay_status',1)
                                                ->whereDate('created_at',$currentDate)
                                            
                                                ->count();
                            $unconfimred=Billcustommers::where('id_province',$id_province)
                                                ->where('pay_status',1)
                                                ->whereDate('created_at',$currentDate)
                                                ->where('pay_unconfirm',null)
                                                ->count();
                            $hcdcconfimred=Billcustommers::where('id_province',$id_province)
                                                ->where('pay_status',1)
                                                ->where('pay_unconfirm',1)
                                                ->whereDate('created_at',$currentDate)
                                                ->count();
                        
                    
                    }
                    if($id_service && !$startDate && !$endDate){
                        dd('Nhập khoản thời gian');
                    }
                }
                else{
                    if($startDate && $endDate && !$id_service){
                        if($request->pay==''){
                            $bills=Billcustommers::where('id_province',$id_province)
                                                ->with(['custommer','services','catelogies','posts'])
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->paginate($perPage)->withQueryString();
                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);

                            $unpaid=Billcustommers::where('pay_status',null)
                                                    ->where('id_province',$id_province)
                                                    ->whereDate('created_at','>=',$startDate)
                                                    ->whereDate('created_at','<=',$endDate)
                                                    ->count();
                                                    //dd($unpaid);
                            $total_pay=Billcustommers::where('pay_status',1)
                                                    ->where('id_province',$id_province)
                                                    ->whereDate('created_at','>=',$startDate)
                                                    ->whereDate('created_at','<=',$endDate)
                                                    ->count();
                            $unconfimred=Billcustommers::where('pay_status',1)
                                                        ->where('id_province',$id_province)
                                                        ->whereDate('created_at','>=',$startDate)
                                                        ->whereDate('created_at','<=',$endDate)
                                                        ->where('pay_unconfirm',null)
                                                        ->count();
                            $hcdcconfimred=Billcustommers::where('pay_status',1)
                                                        ->where('id_province',$id_province)
                                                        ->whereDate('created_at','>=',$startDate)
                                                        ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_unconfirm',1)->count();
            

                        }
                        if($request->pay=='pay'){
                            $bills=Billcustommers::where('id_province',$id_province)
                                                ->with(['custommer','services','catelogies','posts'])
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->where('pay_status',1)
                                                ->paginate($perPage)->withQueryString();
                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);
                            $total_pay=Billcustommers::where('pay_status',1)
                                                    ->where('id_province',$id_province)
                                                    ->whereDate('created_at','>=',$startDate)
                                                    ->whereDate('created_at','<=',$endDate)
                                                    ->count();
                            $unconfimred=Billcustommers::where('pay_status',1)
                                                        ->where('id_province',$id_province)
                                                        ->whereDate('created_at','>=',$startDate)
                                                        ->whereDate('created_at','<=',$endDate)
                                                        ->where('pay_unconfirm',null)
                                                        ->count();
                            $hcdcconfimred=Billcustommers::where('pay_status',1)
                                                        ->where('id_province',$id_province)
                                                        ->whereDate('created_at','>=',$startDate)
                                                        ->whereDate('created_at','<=',$endDate)
                                                        ->where('pay_unconfirm',1)->count();
            

                        }
                        if($request->pay=='notpay'){
                            $bills=Billcustommers::where('id_province',$id_province)
                                                ->with(['custommer','services','catelogies','posts'])
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->where('pay_status',null)
                                                ->paginate($perPage)->withQueryString();
                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);
                            $unpaid=Billcustommers::where('pay_status',null)
                                                    ->where('id_province',$id_province)
                                                    ->whereDate('created_at','>=',$startDate)
                                                    ->whereDate('created_at','<=',$endDate)
                                                    ->count();
                                                    
                        }         
                        
                    }
                    if($id_service && $startDate && $endDate){
                            $bills=Billcustommers::where('id_province',$id_province)->with(['custommer','services','catelogies','posts'])
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })->paginate($perPage)->withQueryString();
                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);
                            
                            $unpaid=Billcustommers::where('id_province',$id_province)
                                                ->where('pay_status',null)
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })
                                                ->count();
                            $total_pay=Billcustommers::where('id_province',$id_province)
                                                ->where('pay_status',1)
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })
                                                ->count();
                            $unconfimred=Billcustommers::where('id_province',$id_province)
                                                ->where('pay_status',1)
                                                ->where('pay_unconfirm',null)
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })
                                                
                                                ->count();
                            $hcdcconfimred=Billcustommers::where('id_province',$id_province)
                                                ->where('pay_status',1)
                                                ->where('pay_unconfirm',1)
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })
                                                ->count();
                    
                    }
                    if(!$id_service && !$startDate && !$endDate){
                            $bills=Billcustommers::where('id_province',$id_province)->with(['custommer','services','catelogies','posts'])
                                                ->whereDate('created_at',$currentDate)
                                                ->paginate($perPage)->withQueryString();

                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);
                            
                            $unpaid=Billcustommers::where('id_province',$id_province)
                                                ->where('pay_status',null)
                                                ->whereDate('created_at',$currentDate)
                                                ->count();
                            $total_pay=Billcustommers::where('id_province',$id_province)
                                                ->where('pay_status',1)
                                                ->whereDate('created_at',$currentDate)
                                            
                                                ->count();
                            $unconfimred=Billcustommers::where('id_province',$id_province)
                                                ->where('pay_status',1)
                                                ->whereDate('created_at',$currentDate)
                                                ->where('pay_unconfirm',null)
                                                ->count();
                            $hcdcconfimred=Billcustommers::where('id_province',$id_province)
                                                ->where('pay_status',1)
                                                ->where('pay_unconfirm',1)
                                                ->whereDate('created_at',$currentDate)
                                                ->count();
                        
                    
                    }
                    if($id_service && !$startDate && !$endDate){
                        dd('Nhập khoản thời gian');
                    }
                }
            }
            else{
                $posts='';
                if (request('term')) {
                    
                    $query = Billcustommers::query();
                    $query
                        ->where('seri_bill', 'like', '%' . request('term') . '%')
                        ->orwhereHas('custommer', function($qr) use($termFill){
                            $qr->where('name','like', '%' . $termFill.'%');
                        })->with('custommer')->where('id_post',$id_post_auth);
                        
                        
                }
                if($request->pay==''){ 
                    if($startDate && $endDate && !$id_service){
                            $bills=Billcustommers::where('id_post',$id_post_auth)->with(['custommer','services','catelogies','posts'])
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->paginate($perPage)->withQueryString();
                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);

                            $unpaid=Billcustommers::where('pay_status',null)
                                                    ->where('id_post',$id_post_auth)
                                                    ->whereDate('created_at','>=',$startDate)
                                                    ->whereDate('created_at','<=',$endDate)
                                                    ->count();
                                                    //dd($unpaid);
                            $total_pay=Billcustommers::where('pay_status',1)
                                                    ->where('id_post',$id_post_auth)
                                                    ->whereDate('created_at','>=',$startDate)
                                                    ->whereDate('created_at','<=',$endDate)
                                                    ->count();
                            $unconfimred=Billcustommers::where('pay_status',1)
                                                        ->where('id_post',$id_post_auth)
                                                        ->whereDate('created_at','>=',$startDate)
                                                        ->whereDate('created_at','<=',$endDate)
                                                        ->where('pay_unconfirm',null)
                                                        ->count();
                            $hcdcconfimred=Billcustommers::where('pay_status',1)
                                                        ->where('id_post',$id_post_auth)
                                                        ->whereDate('created_at','>=',$startDate)
                                                        ->whereDate('created_at','<=',$endDate)
                                                        ->where('pay_unconfirm',1)->count();
                                
                    
                        
                    }
                
                    if($id_service && $startDate && $endDate){
                        if($request->pay == ''){ 
                            $bills=Billcustommers::where('id_post',$id_post_auth)->with(['custommer','services','catelogies','posts'])
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })->paginate($perPage)->withQueryString();
                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);
                            
                            $unpaid=Billcustommers::where('id_post',$id_post_auth)
                                                ->where('pay_status',null)
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })
                                                ->count();
                            $total_pay=Billcustommers::where('id_post',$id_post_auth)
                                                ->where('pay_status',1)
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })
                                                ->count();
                            $unconfimred=Billcustommers::where('id_post',$id_post_auth)
                                                ->where('pay_status',1)
                                                ->where('pay_unconfirm',null)
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })
                                                
                                                ->count();
                            $hcdcconfimred=Billcustommers::where('id_post',$id_post_auth)
                                                ->where('pay_status',1)
                                                ->where('pay_unconfirm',1)
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })
                                                ->count();
                        }
                        
                    
                    }
                    if(!$id_service && !$startDate && !$endDate){
                            $bills=Billcustommers::where('id_post',$id_post_auth)->with(['custommer','services','catelogies','posts'])
                                                ->whereDate('created_at',$currentDate)
                                                ->paginate($perPage)->withQueryString();

                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);
                            
                            $unpaid=Billcustommers::where('id_post',$id_post_auth)
                                                ->where('pay_status',null)
                                                ->whereDate('created_at',$currentDate)
                                                ->count();
                            $total_pay=Billcustommers::where('id_post',$id_post_auth)
                                                ->where('pay_status',1)
                                                ->whereDate('created_at',$currentDate)
                                            
                                                ->count();
                            $unconfimred=Billcustommers::where('id_post',$id_post_auth)
                                                ->where('pay_status',1)
                                                ->whereDate('created_at',$currentDate)
                                                ->where('pay_unconfirm',null)
                                                ->count();
                            $hcdcconfimred=Billcustommers::where('id_post',$id_post_auth)
                                                ->where('pay_status',1)
                                                ->where('pay_unconfirm',1)
                                                ->whereDate('created_at',$currentDate)
                                                ->count();
                        
                    
                    }
                    if($id_service && !$startDate && !$endDate){
                        dd('Nhập khoản thời gian');
                    }
                }
                if($request->pay=='pay'){ 
                    if($startDate && $endDate && !$id_service){
                            $bills=Billcustommers::where('id_post',$id_post_auth)->with(['custommer','services','catelogies','posts'])
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->where('pay_status',1)
                                                ->paginate($perPage)->withQueryString();
                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);

                            $total_pay=Billcustommers::where('pay_status',1)
                                                    ->where('id_post',$id_post_auth)
                                                    ->whereDate('created_at','>=',$startDate)
                                                    ->whereDate('created_at','<=',$endDate)
                                                    ->count();
                            $unconfimred=Billcustommers::where('pay_status',1)
                                                        ->where('id_post',$id_post_auth)
                                                        ->whereDate('created_at','>=',$startDate)
                                                        ->whereDate('created_at','<=',$endDate)
                                                        ->where('pay_unconfirm',null)
                                                        ->count();
                            $hcdcconfimred=Billcustommers::where('pay_status',1)
                                                        ->where('id_post',$id_post_auth)
                                                        ->whereDate('created_at','>=',$startDate)
                                                        ->whereDate('created_at','<=',$endDate)
                                                        ->where('pay_unconfirm',1)->count();
                                
                    
                        
                    }
                
                    if($id_service && $startDate && $endDate){
                            $bills=Billcustommers::where('id_post',$id_post_auth)->with(['custommer','services','catelogies','posts'])
                                                ->whereDate('created_at','>=',$startDate)
                                                ->where('pay_status',1)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })->paginate($perPage)->withQueryString();
                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);
                            
                            $total_pay=Billcustommers::where('id_post',$id_post_auth)
                                                ->where('pay_status',1)
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })
                                                ->count();
                            $unconfimred=Billcustommers::where('id_post',$id_post_auth)
                                                ->where('pay_status',1)
                                                ->where('pay_unconfirm',null)
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })
                                                
                                                ->count();
                            $hcdcconfimred=Billcustommers::where('id_post',$id_post_auth)
                                                ->where('pay_status',1)
                                                ->where('pay_unconfirm',1)
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })
                                                ->count();
                    
                    }
                    if(!$id_service && !$startDate && !$endDate){
                            $bills=Billcustommers::where('id_post',$id_post_auth)->with(['custommer','services','catelogies','posts'])
                                                ->whereDate('created_at',$currentDate)
                                                ->paginate($perPage)->withQueryString();

                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);
                            
                            $unpaid=Billcustommers::where('id_post',$id_post_auth)
                                                ->where('pay_status',null)
                                                ->whereDate('created_at',$currentDate)
                                                ->count();
                            $total_pay=Billcustommers::where('id_post',$id_post_auth)
                                                ->where('pay_status',1)
                                                ->whereDate('created_at',$currentDate)
                                            
                                                ->count();
                            $unconfimred=Billcustommers::where('id_post',$id_post_auth)
                                                ->where('pay_status',1)
                                                ->whereDate('created_at',$currentDate)
                                                ->where('pay_unconfirm',null)
                                                ->count();
                            $hcdcconfimred=Billcustommers::where('id_post',$id_post_auth)
                                                ->where('pay_status',1)
                                                ->where('pay_unconfirm',1)
                                                ->whereDate('created_at',$currentDate)
                                                ->count();
                        
                    
                    }
                    if($id_service && !$startDate && !$endDate){
                        dd('Nhập khoản thời gian');
                    }
                }
                if($request->pay=='notpay'){ 
                    if($startDate && $endDate && !$id_service){
                            $bills=Billcustommers::where('id_post',$id_post_auth)->with(['custommer','services','catelogies','posts'])
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->where('pay_status',null)
                                                ->paginate($perPage)->withQueryString();
                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);

                            $unpaid=Billcustommers::where('pay_status',null)
                                                    ->where('id_post',$id_post_auth)
                                                    ->whereDate('created_at','>=',$startDate)
                                                    ->whereDate('created_at','<=',$endDate)
                                                    ->count();
                                                    //dd($unpaid);      
                    }
                    if($id_service && $startDate && $endDate){
                            $bills=Billcustommers::where('id_post',$id_post_auth)->with(['custommer','services','catelogies','posts'])
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->where('pay_status',null)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })->paginate($perPage)->withQueryString();
                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);
                            
                            $unpaid=Billcustommers::where('id_post',$id_post_auth)
                                                ->where('pay_status',null)
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })
                                                ->count();
                    
                    }
                    if(!$id_service && !$startDate && !$endDate){
                            $bills=Billcustommers::where('id_post',$id_post_auth)->with(['custommer','services','catelogies','posts'])
                                                ->where('pay_status',null)
                                                ->paginate($perPage)->withQueryString();

                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);
                            
                            $unpaid=Billcustommers::where('id_post',$id_post_auth)
                                                ->where('pay_status',null)
                                                ->whereDate('created_at',$currentDate)
                                                ->count();
                    }
                    if($id_service && !$startDate && !$endDate){
                        dd('Nhập khoản thời gian');
                    }
                }
            }
        }
        if($admin->is_admin==1){ 
            $posts=ProvincePost::select('id','name','code',)->get();
            if((Auth()->user()->username ==='administrator') || (Auth()->user()->username ==='admin')){
                $condition_fill=true;
                if (request('term')) {
                    $query = Billcustommers::query();
                    $query
                        ->where('id', 'like', '%' . request('term') . '%')
                        ->orwhereHas('custommer', function($qr) use($termFill){
                            $qr->where('name','like', '%' . $termFill.'%');
                        })->with('custommer')
                        ->orwhereHas('posts', function($qr) use($termFill){
                            $qr->where('code','like', '%' . $termFill.'%');
                        })->with('post');
                }
                if($id_post && !$id_service){
                    if($request->pay==''){
                     
                        if($startDate && $endDate){
                            $bills=Billcustommers::with(['custommer','services','catelogies','posts'])
                                                ->where('id_province',$id_post)
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->where('pay_status',1)
                                                ->orWhere('cash_status',1)
                                                ->paginate($perPage)->withQueryString();

                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);
                          
                            $total_pay=Billcustommers::where('id_province',$id_post)
                                        ->whereDate('created_at','>=',$startDate)
                                        ->whereDate('created_at','<=',$endDate)
                                        ->where('pay_status',1)->count();
                            $unconfimred=Billcustommers::where('id_province',$id_post)
                                        ->whereDate('created_at','>=',$startDate)
                                        ->whereDate('created_at','<=',$endDate)
                                        ->where('pay_status',1)
                                        ->where('pay_unconfirm',null)->count();
                            $hcdcconfimred=Billcustommers::where('id_province',$id_post)
                                        ->whereDate('created_at','>=',$startDate)
                                        ->whereDate('created_at','<=',$endDate)
                                        ->where('pay_status',1)
                                        ->where('pay_unconfirm',1)->count();          
                        }
                        else{
                           dd('Chọn thêm khoản thời gian');
                        }
                    }
                    if($request->pay=='pay'){
                        if($startDate && $endDate){
                            $bills=Billcustommers::with(['custommer','services','catelogies','posts'])
                                                ->where('id_province',$id_post)
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->where('pay_status',1)
                                                ->where('pay_unconfirm',1)
                                                ->paginate($perPage)->withQueryString();

                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);
                           
                            $hcdcconfimred=Billcustommers::where('id_province',$id_post)
                                        ->whereDate('created_at','>=',$startDate)
                                        ->whereDate('created_at','<=',$endDate)
                                        ->where('pay_status',1)
                                        ->where('pay_unconfirm',1)->count();          
                        }
                        else{
                            $bills=Billcustommers::with(['custommer','services','catelogies','posts'])
                                                ->where('id_province',$id_post)
                                                ->where('pay_status',1)
                                                ->where('pay_unconfirm',1)
                                                ->paginate($perPage)->withQueryString();
                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);

                            
                            $total_pay=Billcustommers::where('id_post',$id_post)
                                        ->where('pay_status',1)
                                        ->where('pay_unconfirm',1)->count();
                            $unconfimred=Billcustommers::where('id_province',$id_post)
                                        ->where('pay_status',1)
                                        ->where('pay_unconfirm',null)->count();
                            $hcdcconfimred=Billcustommers::where('id_province',$id_post)
                                        ->where('pay_status',1)
                                        ->where('pay_unconfirm',1)->count();
                        }
                    }
                    if($request->pay=='notpay'){
                        if($startDate && $endDate){
                            $bills=Billcustommers::with(['custommer','services','catelogies','posts'])
                                                ->where('id_province',$id_post)
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->where('pay_status',1)
                                                ->where('pay_unconfirm',null)
                                                ->paginate($perPage)->withQueryString();

                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);
                           
                            $total_pay=Billcustommers::where('id_province',$id_post)
                                        ->whereDate('created_at','>=',$startDate)
                                        ->whereDate('created_at','<=',$endDate)
                                        ->where('pay_status',1)->count();
                            $unconfimred=Billcustommers::where('id_province',$id_post)
                                        ->whereDate('created_at','>=',$startDate)
                                        ->whereDate('created_at','<=',$endDate)
                                        ->where('pay_status',1)
                                        ->where('pay_unconfirm',null)->count();      
                        }
                        else{
                           dd('Chọn thêm khoản thời gian');
                        }
                    }
                }
                if(!$id_post && $id_service){
                    if($startDate && $endDate){
                       
                        if($request->pay == ''){
                            $bills=Billcustommers::with(['custommer','services','catelogies','posts'])
                                ->whereDate('created_at','>=',$startDate)
                                ->whereDate('created_at','<=',$endDate)
                                ->where('pay_status',1)
                                ->whereHas('catelogies', function($qr) use($id_service){
                                    $qr->where('id_service',$id_service);
                                })
                                ->paginate($perPage)->withQueryString();

                            $sumTienMat = Billcustommers::with(['custommer','services','catelogies','posts'])
                                        ->whereDate('created_at','>=',$startDate)
                                        ->whereDate('created_at','<=',$endDate)
                                        ->where('cash_status',1)
                                        ->sum('total_pay');                    
                            $sumChuyenKhoan = Billcustommers::with(['custommer','services','catelogies','posts'])
                                            ->whereDate('created_at','>=',$startDate)
                                            ->whereDate('created_at','<=',$endDate)
                                            ->where('pay_status',1)
                                            ->sum('total_pay'); 
                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);
                            
                            $unpaid=Billcustommers::where('id_province',$id_post)
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_status',null)->count();
                            $total_pay=Billcustommers::where('id_province',$id_post)
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_status',1)->count();
                            $unconfimred=Billcustommers::where('id_province',$id_post)
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_status',1)
                                    ->where('pay_unconfirm',null)->count();
                            $hcdcconfimred=Billcustommers::where('id_province',$id_post)
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_status',1)
                                    ->where('pay_unconfirm',1)->count();
                        }
                        if($request->pay == 'pay'){
                            $bills=Billcustommers::with(['custommer','services','catelogies','posts'])
                                ->whereDate('created_at','>=',$startDate)
                                ->whereDate('created_at','<=',$endDate)
                                ->where('pay_status',1)
                                ->where('pay_unconfirm',1)
                                ->whereHas('catelogies', function($qr) use($id_service){
                                    $qr->where('id_service',$id_service);
                                })
                                ->paginate($perPage)->withQueryString();
                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);
                            
                           
                            $total_pay=Billcustommers::where('id_province',$id_post)
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_status',1)->count();
                            $unconfimred=Billcustommers::where('id_province',$id_post)
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_status',1)
                                    ->where('pay_unconfirm',null)->count();
                            $hcdcconfimred=Billcustommers::where('id_province',$id_post)
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_status',1)
                                    ->where('pay_unconfirm',1)->count();
                        }
                        if($request->pay == 'notpay'){
                            $bills=Billcustommers::with(['custommer','services','catelogies','posts'])
                                ->whereDate('created_at','>=',$startDate)
                                ->whereDate('created_at','<=',$endDate)
                                ->where('pay_status',1)
                                ->where('pay_unconfirm',null)
                                ->whereHas('catelogies', function($qr) use($id_service){
                                    $qr->where('id_service',$id_service);
                                })
                                ->paginate($perPage)->withQueryString();
                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);
                            
                           
                            $total_pay=Billcustommers::where('id_province',$id_post)
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_status',1)->count();
                            $unconfimred=Billcustommers::where('id_province',$id_post)
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_status',1)
                                    ->where('pay_unconfirm',null)->count();
                            $hcdcconfimred=Billcustommers::where('id_province',$id_post)
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_status',1)
                                    ->where('pay_unconfirm',1)->count();
                        }
                      
                    }
                    else{
                        dd('Nhập khoản thời gian');
                    }
                }
                if($id_post && $id_service){
                    if($startDate & $endDate){

                        if($request->pay==''){
                           
                            $bills=Billcustommers::with(['custommer','services','catelogies','posts'])
                                                ->where('id_province',$id_post)
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->where('pay_status',1)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })->paginate($perPage)->withQueryString();
                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);
                            
                            $unpaid=Billcustommers::where('id_province',$id_post)
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_status',null)->count();
                            $total_pay=Billcustommers::where('id_province',$id_post)
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_status',1)->count();
                            $unconfimred=Billcustommers::where('id_province',$id_post)
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_status',1)
                                    ->where('pay_unconfirm',null)->count();
                            $hcdcconfimred=Billcustommers::where('id_province',$id_post)
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_status',1)
                                    ->where('pay_unconfirm',1)->count();
                        }
                        if($request->pay=='pay'){
                            $bills=Billcustommers::with(['custommer','services','catelogies','posts'])
                                                ->where('id_province',$id_post)
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->where('pay_status',1)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })->paginate($perPage)->withQueryString();
                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);

                            $unpaid=Billcustommers::where('id_province',$id_post)
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_status',null)->count();
                            $total_pay=Billcustommers::where('id_province',$id_post)
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_status',1)->count();
                            $unconfimred=Billcustommers::where('id_province',$id_post)
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_status',1)
                                    ->where('pay_unconfirm',null)->count();
                            $hcdcconfimred=Billcustommers::where('id_province',$id_post)
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_status',1)
                                    ->where('pay_unconfirm',1)->count();
                        }
                        if($request->pay=='notpay'){
                            $bills=Billcustommers::with(['custommer','services','catelogies','posts'])
                                                ->where('id_province',$id_post)
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->where('pay_status',1)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })->paginate($perPage)->withQueryString();
                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);

                            $unpaid=Billcustommers::where('id_province',$id_post)
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_status',null)->count();
                            $total_pay=Billcustommers::where('id_province',$id_post)
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_status',1)->count();
                            $unconfimred=Billcustommers::where('id_province',$id_post)
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_status',1)
                                    ->where('pay_unconfirm',null)->count();
                            $hcdcconfimred=Billcustommers::where('id_province',$id_post)
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_status',1)
                                    ->where('pay_unconfirm',1)->count();
                        }
                       
                    }
                    else{
                     dd('Nhập khoản thời gian');
                    }
                }
                if(!$id_post && !$id_service){
                    if($startDate & $endDate){
                        if($request->pay==''){
                            $bills=Billcustommers::with(['custommer','services','catelogies','posts'])
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->where('pay_status',1)
                                                ->paginate($perPage)->withQueryString();
                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);
                            $unpaid=Billcustommers::where('id_province',$id_post)
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_status',null)->count();
                            $total_pay=Billcustommers::where('id_province',$id_post)
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_status',1)->count();
                            $unconfimred=Billcustommers::where('id_province',$id_post)
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_status',1)
                                    ->where('pay_unconfirm',null)->count();
                            $hcdcconfimred=Billcustommers::where('id_province',$id_post)
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_status',1)
                                    ->where('pay_unconfirm',1)->count();
                        
                        }
                        if($request->pay=='pay'){
                            $bills=Billcustommers::with(['custommer','services','catelogies','posts'])
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->where('pay_status',1)
                                                ->where('pay_unconfirm',1)
                                                ->paginate($perPage)->withQueryString();
                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);

                            $total_pay=Billcustommers::where('id_province',$id_post)
                                            ->whereDate('created_at','>=',$startDate)
                                            ->whereDate('created_at','<=',$endDate)
                                            ->where('pay_status',1)->count();
                            $unconfimred=Billcustommers::where('id_province',$id_post)
                                            ->whereDate('created_at','>=',$startDate)
                                            ->whereDate('created_at','<=',$endDate)
                                            ->where('pay_status',1)
                                            ->where('pay_unconfirm',null)->count();
                            $hcdcconfimred=Billcustommers::where('id_province',$id_post)
                                            ->whereDate('created_at','>=',$startDate)
                                            ->whereDate('created_at','<=',$endDate)
                                            ->where('pay_status',1)
                                            ->where('pay_unconfirm',1)->count();
                        
                        }

                        if($request->pay=='notpay'){
                            $bills=Billcustommers::with(['custommer','services','catelogies','posts'])
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->where('pay_status',1)
                                                ->where('pay_unconfirm',null)
                                                ->paginate($perPage)->withQueryString();
                                    $sum_price = $bills->sum('total_pay');
                                    $text_price= $this->convert_number_to_words($sum_price);
                                   
                                    $total_pay=Billcustommers::where('id_province',$id_post)
                                            ->whereDate('created_at','>=',$startDate)
                                            ->whereDate('created_at','<=',$endDate)
                                            ->where('pay_status',1)->count();
                                    $unconfimred=Billcustommers::where('id_province',$id_post)
                                            ->whereDate('created_at','>=',$startDate)
                                            ->whereDate('created_at','<=',$endDate)
                                            ->where('pay_status',1)
                                            ->where('pay_unconfirm',null)->count();
                                    $hcdcconfimred=Billcustommers::where('id_province',$id_post)
                                            ->whereDate('created_at','>=',$startDate)
                                            ->whereDate('created_at','<=',$endDate)
                                            ->where('pay_status',1)
                                            ->where('pay_unconfirm',1)->count();
                        
                        }
                    }
                    else{
                        if($request->pay==''){
                           
                            $bills=Billcustommers::with(['custommer','services','catelogies','posts'])
                                                ->whereDate('created_at',$currentDate)
                                                ->where('pay_status',1)
                                                ->orwhere('cash_status',1)
                                                ->paginate($perPage)->withQueryString();

                             $sumTienMat = Billcustommers::with(['custommer','services','catelogies','posts'])
                                            ->whereDate('created_at',$currentDate)
                                            ->where('cash_status',1)
                                            ->sum('total_pay');                    
                             $sumChuyenKhoan = Billcustommers::with(['custommer','services','catelogies','posts'])
                                            ->whereDate('created_at',$currentDate)
                                            ->where('pay_status',1)
                                            ->sum('total_pay'); 

                            $sum_price = $bills->sum('total_pay')? $bills->sum('total_pay'):'';

                          
                            $text_price= $this->convert_number_to_words($sum_price);

                            $unpaid=Billcustommers::whereDate('created_at',$currentDate)
                                                        ->where('pay_status',null)->count();
                            $total_pay=Billcustommers::whereDate('created_at',$currentDate)
                                                ->where('pay_status',1)->count();
                            $unconfimred=Billcustommers::whereDate('created_at',$currentDate)
                                                ->where('pay_status',1)
                                                ->where('pay_unconfirm',null)->count();
                            $hcdcconfimred=Billcustommers::whereDate('created_at',$currentDate)
                                                ->where('pay_status',1)
                                                ->where('pay_unconfirm',1)->count();
                        }
                        if($request->pay=='pay'){
                            $bills=Billcustommers::with(['custommer','services','catelogies','posts'])
                                                ->whereDate('created_at',$currentDate)
                                                ->where('pay_status',1)
                                                ->where('pay_unconfirm',1)
                                                ->paginate($perPage)->withQueryString();
                            $sum_price = $bills->sum('total_pay')? $bills->sum('total_pay'):'';
                            $text_price= $this->convert_number_to_words($sum_price);

                            $unpaid=Billcustommers::whereDate('created_at',$currentDate)
                                                        ->where('pay_status',null)->count();
                            $total_pay=Billcustommers::whereDate('created_at',$currentDate)
                                                ->where('pay_status',1)->count();
                            $unconfimred=Billcustommers::whereDate('created_at',$currentDate)
                                                ->where('pay_status',1)
                                                ->where('pay_unconfirm',null)->count();
                            $hcdcconfimred=Billcustommers::whereDate('created_at',$currentDate)
                                                ->where('pay_status',1)
                                                ->where('pay_unconfirm',1)->count();
                        }
                        if($request->pay=='notpay'){
                            $bills=Billcustommers::with(['custommer','services','catelogies','posts'])
                                                ->whereDate('created_at',$currentDate)
                                                ->where('pay_status',1)
                                                ->where('pay_unconfirm',null)
                                                ->paginate($perPage)->withQueryString();
                            $sum_price = $bills->sum('total_pay')? $bills->sum('total_pay'):'';
                            $text_price= $this->convert_number_to_words($sum_price);

                            $total_pay=Billcustommers::whereDate('created_at',$currentDate)
                                                ->where('pay_status',1)->count();
                            $unconfimred=Billcustommers::whereDate('created_at',$currentDate)
                                                ->where('pay_status',1)
                                                ->where('pay_unconfirm',null)->count();
                            $hcdcconfimred=Billcustommers::whereDate('created_at',$currentDate)
                                                ->where('pay_status',1)
                                                ->where('pay_unconfirm',1)->count();    
                        }
                    }
                
                }
            }
            else{
                dd('Không có phép truy cập!');
            }
            
        }
        $filters=[
            'perPage'=>$request->perPage,
            'startDate'=>$request->startDate,
            'endDate'=>$request->endDate
        ];
        return Inertia::render('Report/Index',[
            'bills'=>$query?fn() => $query->with(['custommer','services','catelogies','posts'])->orderBy('id','desc')->paginate($perPage)->withQueryString():$bills,
            'posts'=>$posts,
            'services'=>Catelory::select('id','name')->get(),
            'filters'=>$filters,
            'sum_pay'=>$sum_price?$sum_price:'',
            'text_price'=>$text_price,
            'unpaid'=>$unpaid,
            'total_pay'=>$total_pay,
            'unconfimred'=>$unconfimred,
            'hcdcconfimred'=>$hcdcconfimred,
            'sumTienMat'=>$sumTienMat,
            'sumChuyenKhoan'=>$sumChuyenKhoan,
            'can' => [
                'view' => Auth::user()->checkView(config('permission.access.view_report')),
                'create' => Auth::user()->checkCreate(config('permission.access.create_report')),
                'edit' => Auth::user()->checkEdit(config('permission.access.edit_report')),
               
            ],
            'condition_fill'=>$condition_fill
            
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
            0                   => 'Không',
            1                   => 'Một',
            2                   => 'Hai',
            3                   => 'Ba',
            4                   => 'Bốn',
            5                   => 'Năm',
            6                   => 'Sáu',
            7                   => 'Bảy',
            8                   => 'Tám',
            9                   => 'Chín',
            10                  => 'Mười',
            11                  => 'Mười một',
            12                  => 'Mười hai',
            13                  => 'Mười ba',
            14                  => 'Mười bốn',
            15                  => 'Mười lăm',
            16                  => 'Mười sáu',
            17                  => 'Mười bảy',
            18                  => 'Mười tám',
            19                  => 'Mười chín',
            20                  => 'Hai mươi',
            30                  => 'Ba mươi',
            40                  => 'Bốn mươi',
            50                  => 'Năm mươi',
            60                  => 'Sáu mươi',
            70                  => 'Bảy mươi',
            80                  => 'Tám mươi',
            90                  => 'Chín mươi',
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

    public function confirmPay($id){
        Billcustommers::where('id',$id)->update(
            [
                'pay_unconfirm'=>1
            ]
            );
            return back()->withInput()->with('success','Xác nhận thành công successfully!');
    }
}
