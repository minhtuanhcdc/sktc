<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Catelory;
use App\Models\Custommer;
use App\Models\Billcustommers;
use App\Models\Billservices;
use App\Models\Post;
use App\Models\CosoModel;
use App\Models\ProvincePost;
use Carbon\Carbon;
use Auth;
use DB;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       //dd($request->all());
        $currentDate = Carbon::now()->toDateString();
       $startDate = $request->startDate;
       $endDate = $request->endDate;
       $id_post = $request->id_coso;
    
       $id_service = $request->id_service;
       $perPage = $request->perPage?$request->perPage:50;
       $termFill = $request->term;
       $buoi = $request->buoi;
       $bills='';
       $query='';
       $sum_price="";
       $unpaid='';
       $text_price="";
       $total_pay='';
        $unconfimred='';
        $hcdcconfimred='';
        $posts="";
        $tienMat='';
         $chuyenKhoan='';
        $condition_fill=false;
       
       $is_admin=false;
       $admin = Auth()->user();
       $cosos= CosoModel::get();
       //$id_province= Auth()->user()->posts?Auth()->user()->posts->province_code:'';
    
       //$post=Post::where('id',$id_post_auth)->first();
      // $getPost=$post?$post->code:'';
      if($buoi){
        if($admin->is_admin !=1 || $admin->is_admin ==null){
            $is_admin=false;
            if (request('term')) {
                $query = Billcustommers::query();
                $query
                    ->where('seri_bill', 'like', '%' . request('term') . '%')
                    ->orwhereHas('custommer', function($qr) use($termFill){
                        $qr->where('name','like', '%' . $termFill.'%');
                    })->with('custommer')
                    ->where('id_province',$id_province)->where('pay_status',1);
            }
           if($id_service){
                if($startDate && $endDate){
                    $bills=Billcustommers::where('user_created',$admin->id)->with(['custommer','services','catelogies','posts','user'])
                                            ->whereDate('created_at','>=',$startDate)
                                            ->whereDate('created_at','<=',$endDate)
                                            ->where('buoi',$buoi)
                                            ->where('pay_status',1)
                                            ->whereHas('catelogies', function($qr) use($id_service){
                                                $qr->where('id_service',$id_service);
                                            })->paginate($perPage)->withQueryString();

                    $sum_price =$bills->sum('total_pay');
                    $text_price= $this->convert_number_to_words($sum_price);
                    $sumTienMat = Billcustommers::where('user_created',$admin->id)
                                            ->with(['custommer','services','catelogies','posts'])
                                            ->whereDate('created_at','>=',$startDate)
                                            ->whereDate('created_at','<=',$endDate)
                                            ->where('buoi',$buoi)
                                            ->where('pay_cash',1)
                                            ->whereHas('catelogies', function($qr) use($id_service){
                                                $qr->where('id_service',$id_service);
                                            })
                                            ->sum('total_pay');                    
                    $sumChuyenKhoan = Billcustommers::where('user_created',$admin->id)
                                            ->with(['custommer','services','catelogies','posts'])
                                            ->whereDate('created_at','>=',$startDate)
                                            ->whereDate('created_at','<=',$endDate)
                                            ->where('buoi',$buoi)
                                            ->whereHas('catelogies', function($qr) use($id_service){
                                                $qr->where('id_service',$id_service);
                                            })
                                            ->where('pay_transfer',1)
                                            ->sum('total_pay'); 
                    $tienMat=Billcustommers:: whereHas('catelogies', function($qr) use($id_service){
                                                $qr->where('id_service',$id_service);
                                            })->whereDate('created_at','>=',$startDate)
                                            ->whereDate('created_at','<=',$endDate)
                                            ->where('buoi',$buoi)
                                            ->where('pay_cash',1)->count();
                    
                    $chuyenKhoan=Billcustommers::whereHas('catelogies', function($qr) use($id_service){
                                                $qr->where('id_service',$id_service);
                                            }) 
                                            ->whereDate('created_at','>=',$startDate)                                      
                                            ->whereDate('created_at','<=',$endDate)
                                            ->where('buoi',$buoi)
                                            ->where('pay_transfer',1)->count();
                    
                }
                else{
                    $bills=Billcustommers::where('user_created',$admin->id)->with(['custommer','services','catelogies','posts','user'])
                                            ->whereDate('created_at',$currentDate)
                                            ->where('buoi',$buoi)
                                            ->where('pay_status',1)
                                            ->whereHas('catelogies', function($qr) use($id_service){
                                                $qr->where('id_service',$id_service);
                                            })->paginate($perPage)->withQueryString();

                    $sum_price =$bills->sum('total_pay');
                    $text_price= $this->convert_number_to_words($sum_price);
                    $sumTienMat = Billcustommers::where('user_created',$admin->id)
                                            ->with(['custommer','services','catelogies','posts'])
                                            ->whereDate('created_at',$currentDate)
                                            ->where('buoi',$buoi)
                                            ->where('pay_cash',1)
                                            ->whereHas('catelogies', function($qr) use($id_service){
                                                $qr->where('id_service',$id_service);
                                            })
                                            ->sum('total_pay');                    
                    $sumChuyenKhoan = Billcustommers::where('user_created',$admin->id)
                                            ->with(['custommer','services','catelogies','posts'])
                                            ->whereDate('created_at',$currentDate)
                                            ->where('buoi',$buoi)
                                            ->whereHas('catelogies', function($qr) use($id_service){
                                                $qr->where('id_service',$id_service);
                                            })
                                            ->where('pay_transfer',1)
                                            ->sum('total_pay'); 
                    $tienMat=Billcustommers::where('user_created',$admin->id)-> whereHas('catelogies', function($qr) use($id_service){
                                                $qr->where('id_service',$id_service);
                                            })
                                            ->whereDate('created_at',$currentDate)
                                            ->where('buoi',$buoi)
                                            ->where('pay_cash',1)->count();
                    
                    $chuyenKhoan=Billcustommers::where('user_created',$admin->id)->whereHas('catelogies', function($qr) use($id_service){
                                                $qr->where('id_service',$id_service);
                                            }) 
                                            ->whereDate('created_at',$currentDate)
                                            ->where('buoi',$buoi)
                                            ->where('pay_transfer',1)->count();
                    
                }
           }
           else{
                if($startDate && $endDate && !$id_service){
                    $bills=Billcustommers::where('user_created',$admin->id)->where('buoi',$buoi)
                                        ->with(['custommer','services','catelogies','posts','user'])
                                        ->whereDate('created_at','>=',$startDate)
                                        ->whereDate('created_at','<=',$endDate)
                                        ->paginate($perPage)->withQueryString();

                    $sum_price =$bills->sum('total_pay');
                    $sumTienMat = Billcustommers::where('user_created',$admin->id)->where('buoi',$buoi)
                                        ->with(['custommer','services','catelogies','posts'])
                                        ->whereDate('created_at','>=',$startDate)
                                        ->whereDate('created_at','<=',$endDate)
                                        ->where('pay_cash',1)
                                        ->sum('total_pay');                    
                    $sumChuyenKhoan = Billcustommers::where('user_created',$admin->id)->where('buoi',$buoi)
                                        ->with(['custommer','services','catelogies','posts'])
                                        ->whereDate('created_at','>=',$startDate)
                                        ->whereDate('created_at','<=',$endDate)
                                        ->where('pay_transfer',1)
                                        ->sum('total_pay');    
                }
                if(!$startDate && !$endDate && !$id_service){
                    $bills=Billcustommers::where('user_created',$admin->id)->where('buoi',$buoi)
                                        ->with(['custommer','services','catelogies','posts','user'])
                                        ->whereDate('created_at',$currentDate)
                                       
                                        ->paginate($perPage)->withQueryString();

                    $sum_price =$bills->sum('total_pay');
                    $sumTienMat = Billcustommers::where('user_created',$admin->id)->where('buoi',$buoi)
                                        ->with(['custommer','services','catelogies','posts'])
                                        ->whereDate('created_at',$currentDate)
                                        ->where('pay_cash',1)
                                        ->sum('total_pay');                    
                    $sumChuyenKhoan = Billcustommers::where('user_created',$admin->id)->where('buoi',$buoi)
                                        ->with(['custommer','services','catelogies','posts'])
                                        ->whereDate('created_at',$currentDate)
                                        ->where('pay_transfer',1)
                                        ->sum('total_pay');    
                }
           }

           
        }
        if($admin->is_admin==1){ 
          
                $is_admin=true;
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
                    if($startDate && $endDate){
                        //dd(123);
                        $bills=Billcustommers::with(['custommer','services','catelogies','posts','user'])
                                            
                                            ->whereDate('created_at','>=',$startDate)
                                            ->whereDate('created_at','<=',$endDate)
                                            ->where('buoi',$buoi)
                                            ->where('pay_status',1)
                                            ->paginate($perPage)->withQueryString();

                        $sum_price = $bills->sum('total_pay');
                        $text_price= $this->convert_number_to_words($sum_price);
                        
                        $total_pay=Billcustommers::
                                    whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('buoi',$buoi)
                                    ->where('pay_status',1)->count();
                        $tienMat=Billcustommers::
                                    whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('buoi',$buoi)
                                    ->where('pay_cash',1)->count();

                 $chuyenKhoan=Billcustommers::whereDate('created_at','>=',$startDate)
                            ->whereDate('created_at','<=',$endDate)
                            ->where('pay_transfer',1)->count();
                            $sumTienMat = Billcustommers::with(['custommer','services','catelogies','posts'])
                            ->whereDate('created_at','>=',$startDate)
                            ->whereDate('created_at','<=',$endDate)
                            ->where('buoi',$buoi)
                            ->where('pay_cash',1)
                            ->sum('total_pay');                    
                    $sumChuyenKhoan = Billcustommers::with(['custommer','services','catelogies','posts'])
                            ->whereDate('created_at','>=',$startDate)
                            ->whereDate('created_at','<=',$endDate)
                            ->where('buoi',$buoi)
                            ->where('pay_transfer',1)
                            ->sum('total_pay'); 
                        
                    }
                    else{
                        dd('Chọn thêm khoản thời gian');
                    }
                }
                if(!$id_post && $id_service){
                  // dd($id_service);
                    if($startDate && $endDate){
                        $bills=Billcustommers::with(['custommer','services','catelogies','posts','user'])
                            ->where('pay_status',1)
                            ->whereDate('created_at','>=',$startDate)
                            ->whereDate('created_at','<=',$endDate)
                            ->where('buoi',$buoi)
                            ->whereHas('catelogies', function($qr) use($id_service){
                                $qr->where('id_service',$id_service);
                            })->with('catelogies')
                            ->paginate($perPage)->withQueryString();

                        $sumTienMat = Billcustommers::with(['custommer','services','catelogies','posts'])
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('buoi',$buoi)
                                    ->where('pay_cash',1)
                                    ->whereHas('catelogies', function($qr) use($id_service){
                                        $qr->where('id_service',$id_service);
                                    })->with('catelogies')
                                    ->sum('total_pay');                    
                        $sumChuyenKhoan = Billcustommers::with(['custommer','services','catelogies','posts'])
                                        ->whereDate('created_at','>=',$startDate)
                                        ->whereDate('created_at','<=',$endDate)
                                        ->where('buoi',$buoi)
                                        ->where('pay_transfer',1)
                                        ->whereHas('catelogies', function($qr) use($id_service){
                                            $qr->where('id_service',$id_service);
                                        })
                                        ->sum('total_pay'); 
                        $sum_price = $bills->sum('total_pay');
                        $text_price= $this->convert_number_to_words($sum_price);
                        $tienMat=Billcustommers::whereDate('created_at','>=',$startDate)
                                ->whereDate('created_at','<=',$endDate)
                                ->where('buoi',$buoi)
                                ->where('pay_cash',1)
                                ->whereHas('catelogies', function($qr) use($id_service){
                                    $qr->where('id_service',$id_service);
                                })->with('catelogies')->count();

                        $chuyenKhoan=Billcustommers::whereDate('created_at','>=',$startDate)
                                        ->whereDate('created_at','<=',$endDate)
                                        ->where('pay_transfer',1)
                                        ->where('buoi',$buoi)
                                        ->whereHas('catelogies', function($qr) use($id_service){
                                            $qr->where('id_service',$id_service);
                                        })->with('catelogies')->count();
                       
                    }
                    else{
                        $bills=Billcustommers::with(['custommer','services','catelogies','posts','user'])
                                            ->whereDate('created_at',$currentDate)
                                            ->where('pay_status',1)
                                            ->where('buoi',$buoi)
                                            ->whereHas('catelogies', function($qr) use($id_service){
                                                $qr->where('id_service',$id_service);
                                            })
                                            ->withSum('services','billservices.sl')
                                            ->paginate($perPage)->withQueryString();

                        $sumTienMat = Billcustommers::with(['custommer','services','catelogies','posts'])
                                    ->whereDate('created_at',$currentDate)
                                    ->where('pay_cash',1)
                                    ->where('buoi',$buoi)
                                    ->whereHas('catelogies', function($qr) use($id_service){
                                        $qr->where('id_service',$id_service);
                                    })
                                    ->sum('total_pay');                    
                        $sumChuyenKhoan = Billcustommers::with(['custommer','services','catelogies','posts'])
                                    ->whereDate('created_at',$currentDate)
                                    ->where('pay_transfer',1)
                                    ->where('buoi',$buoi)
                                    ->whereHas('catelogies', function($qr) use($id_service){
                                        $qr->where('id_service',$id_service);
                                    })
                                    ->sum('total_pay'); 
                       
                        $sum_price = $bills->sum('total_pay')? $bills->sum('total_pay'):'';
                        $text_price= $this->convert_number_to_words($sum_price);
                        $total_pay=Billcustommers::whereDate('created_at',$currentDate)
                                                ->where('pay_status',1)
                                                ->where('buoi',$buoi)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })->count();
                        $tienMat=Billcustommers::whereDate('created_at',$currentDate)
                                                ->where('pay_cash',1)
                                                ->where('buoi',$buoi)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })->count();
            
                        $chuyenKhoan=Billcustommers::whereDate('created_at',$currentDate)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })
                                                    ->where('pay_transfer',1)->count();
                  
                    }
                }
                if($id_post && $id_service){
                    if($startDate & $endDate){
                    
                            $bills=Billcustommers::with(['custommer','services','catelogies','posts','user'])
                                                ->where('id_province',$id_post)
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->where('buoi',$buoi)
                                                ->where('pay_status',1)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })->paginate($perPage)->withQueryString();
                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);
                        
                            $total_pay=Billcustommers::where('id_province',$id_post)
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('buoi',$buoi)
                                    ->where('pay_status',1)->count();
                            $tienMat=Billcustommers::whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('buoi',$buoi)
                                    ->where('pay_cash',1)->count();

                            $chuyenKhoan=Billcustommers::whereDate('created_at','>=',$startDate)
                                            ->whereDate('created_at','<=',$endDate)
                                            ->where('pay_transfer',1)->count();
                          
                       
                    }
                    else{
                     dd('Nhập khoản thời gian');
                    }
                }
                if(!$id_post && !$id_service){
                    if($startDate & $endDate){
                        $bills=Billcustommers::with(['custommer','services','catelogies','posts','user'])
                                            ->whereDate('created_at','>=',$startDate)
                                            ->whereDate('created_at','<=',$endDate)
                                            ->where('buoi',$buoi)
                                            ->where('pay_status',1)
                                            ->paginate($perPage)->withQueryString();

                        $sum_price = $bills->sum('total_pay');
                        $text_price= $this->convert_number_to_words($sum_price);
                        
                        $total_pay=Billcustommers::
                                whereDate('created_at','>=',$startDate)
                                ->whereDate('created_at','<=',$endDate)
                                ->where('buoi',$buoi)
                                ->where('pay_status',1)->count();
                        $sumTienMat = Billcustommers::with(['custommer','services','catelogies','posts'])
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('buoi',$buoi)
                                    ->where('pay_cash',1)
                                    ->sum('total_pay');                    
                        $sumChuyenKhoan = Billcustommers::with(['custommer','services','catelogies','posts'])
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('buoi',$buoi)
                                    ->where('pay_transfer',1)
                                    ->sum('total_pay'); 
                        $tienMat=Billcustommers::whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->where('buoi',$buoi)
                                                ->where('pay_cash',1)->count();
         
                        $chuyenKhoan=Billcustommers::whereDate('created_at','>=',$startDate)
                                        ->whereDate('created_at','<=',$endDate)
                                        ->where('buoi',$buoi)
                                        ->where('pay_transfer',1)->count();
                    }
                    else{
                        $bills=Billcustommers::with(['custommer','services','catelogies','posts','user'])
                                            ->whereDate('created_at',$currentDate)
                                            ->where('pay_status',1)
                                            ->where('buoi',$buoi)
                                            ->paginate($perPage)->withQueryString();
                        $sum_price =$bills->sum('total_pay');
                        $text_price= $this->convert_number_to_words($sum_price);
                        $sumTienMat = Billcustommers::with(['custommer','services','catelogies','posts'])
                                        ->whereDate('created_at',$currentDate)
                                        ->where('buoi',$buoi)
                                        ->where('pay_cash',1)
                                        ->sum('total_pay');                    
                        $sumChuyenKhoan = Billcustommers::with(['custommer','services','catelogies','posts'])
                                        ->whereDate('created_at',$currentDate)
                                        ->where('buoi',$buoi)
                                        ->where('pay_transfer',1)
                                        ->sum('total_pay'); 
                        $tienMat=Billcustommers::whereDate('created_at',$currentDate)
                        ->where('buoi',$buoi)
                                        ->where('pay_cash',1)->count();
                 
                        $chuyenKhoan=Billcustommers::whereDate('created_at',$currentDate)
                        ->where('buoi',$buoi)
                                        ->where('pay_transfer',1)->count();
                      
                    }
                }
        }
      }
      else{
        if($admin->is_admin!=1 || $admin->is_admin==null){
            $is_admin=false;
            if (request('term')) {
                $query = Billcustommers::query();
                $query
                    ->where('seri_bill', 'like', '%' . request('term') . '%')
                    ->orwhereHas('custommer', function($qr) use($termFill){
                        $qr->where('name','like', '%' . $termFill.'%');
                    })->with('custommer')
                    ->where('id_province',$id_province)->where('pay_status',1);
            }
            //$posts=Post::where('province_code',$id_province)->select('id','name','code')->get();
            if($startDate && $endDate && !$id_service){
                    $bills=Billcustommers::where('user_created',$admin->id)
                                        ->with(['custommer','services','catelogies','posts','user'])
                                        ->whereDate('created_at','>=',$startDate)
                                        ->whereDate('created_at','<=',$endDate)
                                        ->paginate($perPage)->withQueryString();
                    $sum_price =$bills->sum('total_pay');

                    $sumTienMat = Billcustommers::where('user_created',$admin->id)
                                        ->with(['custommer','services','catelogies','posts'])
                                         ->whereDate('created_at','>=',$startDate)
                                        ->whereDate('created_at','<=',$endDate)
                                        ->where('pay_cash',1)
                                        ->sum('total_pay');                    
                    $sumChuyenKhoan = Billcustommers::where('user_created',$admin->id)
                                        ->with(['custommer','services','catelogies','posts'])
                                        ->whereDate('created_at','>=',$startDate)
                                        ->whereDate('created_at','<=',$endDate)
                                        ->where('pay_transfer',1)
                                        ->sum('total_pay');    
            }
            if($id_service && $startDate && $endDate){
                $bills=Billcustommers::where('user_created',$admin->id)->with(['custommer','services','catelogies','posts','user'])
                                        ->whereDate('created_at','>=',$startDate)
                                        ->whereDate('created_at','<=',$endDate)
                                        ->where('pay_status',1)
                                        ->whereHas('catelogies', function($qr) use($id_service){
                                            $qr->where('id_service',$id_service);
                                        })->paginate($perPage)->withQueryString();

                $sum_price =$bills->sum('total_pay');
                $text_price= $this->convert_number_to_words($sum_price);
                $sumTienMat = Billcustommers::where('user_created',$admin->id)
                                        ->with(['custommer','services','catelogies','posts'])
                                        ->whereDate('created_at','>=',$startDate)
                                        ->whereDate('created_at','<=',$endDate)
                                        ->where('pay_cash',1)
                                        ->whereHas('catelogies', function($qr) use($id_service){
                                            $qr->where('id_service',$id_service);
                                        })
                                        ->sum('total_pay');                    
                $sumChuyenKhoan = Billcustommers::where('user_created',$admin->id)
                                        ->with(['custommer','services','catelogies','posts'])
                                        ->whereDate('created_at','>=',$startDate)
                                        ->whereDate('created_at','<=',$endDate)
                                        ->whereHas('catelogies', function($qr) use($id_service){
                                            $qr->where('id_service',$id_service);
                                        })
                                        ->where('pay_transfer',1)
                                        ->sum('total_pay'); 
                $tienMat=Billcustommers:: whereHas('catelogies', function($qr) use($id_service){
                                            $qr->where('id_service',$id_service);
                                        })->whereDate('created_at','>=',$startDate)
                                        ->where('pay_cash',1)->count();
                 
                $chuyenKhoan=Billcustommers::whereHas('catelogies', function($qr) use($id_service){
                                            $qr->where('id_service',$id_service);
                                        })                                       
                                        ->whereDate('created_at','<=',$endDate)
                                        ->where('pay_transfer',1)->count();
                  
            }
            if(!$id_service && !$startDate && !$endDate){
                //dd(123);
                    $bills=Billcustommers::where('user_created',$admin->id)
                                        ->with(['custommer','services','catelogies','posts','user'])
                                        ->whereDate('created_at',$currentDate)
                                        ->where('pay_status',1)
                                        ->paginate($perPage)->withQueryString();
                   
                    $sum_price =$bills->sum('total_pay');
                    $text_price= $this->convert_number_to_words($sum_price);
                    
                    $sumTienMat = Billcustommers::where('user_created',$admin->id)
                                        ->with(['custommer','services','catelogies','posts'])
                                        ->whereDate('created_at',$currentDate)
                                        ->where('pay_cash',1)
                                        ->sum('total_pay');                    
                    $sumChuyenKhoan = Billcustommers::where('user_created',$admin->id)
                                        ->with(['custommer','services','catelogies','posts'])
                                        ->whereDate('created_at',$currentDate)
                                        ->where('pay_transfer',1)
                                        ->sum('total_pay'); 
                    $tienMat=Billcustommers::where('user_created',$admin->id)->whereDate('created_at',$currentDate)
                                        ->where('pay_cash',1)->count();
                 
                    $chuyenKhoan=Billcustommers::where('user_created',$admin->id)->whereDate('created_at',$currentDate)
                                        ->where('pay_transfer',1)->count();
            }
            if($id_service && !$startDate && !$endDate){
                dd('Nhập khoản thời gian');
            } 
        }
        if($admin->is_admin==1){ 
           // dd($request->all());
            if($admin->is_admin==1){
                $is_admin=true;
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
                    if($startDate && $endDate){
                        //dd(123);
                        $bills=Billcustommers::with(['custommer','services','catelogies','posts','user'])
                                            ->where('id_post',$id_post)
                                            ->whereDate('created_at','>=',$startDate)
                                            ->whereDate('created_at','<=',$endDate)
                                            ->where('pay_status',1)
                                            ->paginate($perPage)->withQueryString();

                        $sum_price = $bills->sum('total_pay');
                        $text_price= $this->convert_number_to_words($sum_price);
                        
                        $total_pay=Billcustommers::where('id_post',$id_post)
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_status',1)->count();
                        $tienMat=Billcustommers::where('id_post',$id_post)
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_cash',1)->count();

                 $chuyenKhoan=Billcustommers::where('id_post',$id_post)->whereDate('created_at','>=',$startDate)
                            ->whereDate('created_at','<=',$endDate)
                            ->where('pay_transfer',1)->count();
                            $sumTienMat = Billcustommers::with(['custommer','services','catelogies','posts'])
                            ->whereDate('created_at','>=',$startDate)
                            ->whereDate('created_at','<=',$endDate)
                            ->where('pay_cash',1)
                            ->sum('total_pay');                    
                    $sumChuyenKhoan = Billcustommers::with(['custommer','services','catelogies','posts'])
                            ->whereDate('created_at','>=',$startDate)
                            ->whereDate('created_at','<=',$endDate)
                            ->where('pay_transfer',1)
                            ->sum('total_pay'); 
                        
                    }
                    else{
                        dd('Chọn thêm khoản thời gian');
                    }
                }
                if(!$id_post && $id_service){
                  // dd($id_service);
                    if($startDate && $endDate){
                        $bills=Billcustommers::with(['custommer','services','catelogies','posts','user'])
                            ->where('pay_status',1)
                            ->whereDate('created_at','>=',$startDate)
                            ->whereDate('created_at','<=',$endDate)
                            ->whereHas('catelogies', function($qr) use($id_service){
                                $qr->where('id_service',$id_service);
                            })->with('catelogies')
                            ->paginate($perPage)->withQueryString();

                        $sumTienMat = Billcustommers::with(['custommer','services','catelogies','posts'])
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_cash',1)
                                    ->whereHas('catelogies', function($qr) use($id_service){
                                        $qr->where('id_service',$id_service);
                                    })->with('catelogies')
                                    ->sum('total_pay');                    
                        $sumChuyenKhoan = Billcustommers::with(['custommer','services','catelogies','posts'])
                                        ->whereDate('created_at','>=',$startDate)
                                        ->whereDate('created_at','<=',$endDate)
                                        ->where('pay_transfer',1)
                                        ->whereHas('catelogies', function($qr) use($id_service){
                                            $qr->where('id_service',$id_service);
                                        })
                                        ->sum('total_pay'); 
                        $sum_price = $bills->sum('total_pay');
                        $text_price= $this->convert_number_to_words($sum_price);
                        $tienMat=Billcustommers::whereDate('created_at','>=',$startDate)
                                ->whereDate('created_at','<=',$endDate)
                                ->where('pay_cash',1)
                                ->whereHas('catelogies', function($qr) use($id_service){
                                    $qr->where('id_service',$id_service);
                                })->with('catelogies')->count();

                        $chuyenKhoan=Billcustommers::whereDate('created_at','>=',$startDate)
                                        ->whereDate('created_at','<=',$endDate)
                                        ->where('pay_transfer',1)
                                        ->whereHas('catelogies', function($qr) use($id_service){
                                            $qr->where('id_service',$id_service);
                                        })->with('catelogies')->count();
                       
                    }
                    else{
                        $bills=Billcustommers::with(['custommer','services','catelogies','posts','user'])
                                            ->whereDate('created_at',$currentDate)
                                            ->where('pay_status',1)
                                            ->whereHas('catelogies', function($qr) use($id_service){
                                                $qr->where('id_service',$id_service);
                                            })
                                            ->withSum('services','billservices.sl')
                                            ->paginate($perPage)->withQueryString();

                        $sumTienMat = Billcustommers::with(['custommer','services','catelogies','posts'])
                                    ->whereDate('created_at',$currentDate)
                                    ->where('pay_cash',1)
                                    ->whereHas('catelogies', function($qr) use($id_service){
                                        $qr->where('id_service',$id_service);
                                    })
                                    ->sum('total_pay');                    
                        $sumChuyenKhoan = Billcustommers::with(['custommer','services','catelogies','posts'])
                                    ->whereDate('created_at',$currentDate)
                                    ->where('pay_transfer',1)
                                    ->whereHas('catelogies', function($qr) use($id_service){
                                        $qr->where('id_service',$id_service);
                                    })
                                    ->sum('total_pay'); 
                       
                        $sum_price = $bills->sum('total_pay')? $bills->sum('total_pay'):'';
                        $text_price= $this->convert_number_to_words($sum_price);
                        $total_pay=Billcustommers::whereDate('created_at',$currentDate)
                                                ->where('pay_status',1)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })->count();
                        $tienMat=Billcustommers::whereDate('created_at',$currentDate)
                                                ->where('pay_cash',1)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })->count();
            
                        $chuyenKhoan=Billcustommers::whereDate('created_at',$currentDate)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })
                                                    ->where('pay_transfer',1)->count();
                  
                    }
                }
                if($id_post && $id_service){
                    if($startDate & $endDate){
                    
                            $bills=Billcustommers::with(['custommer','services','catelogies','posts','user'])
                                                ->where('id_province',$id_post)
                                                ->whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->where('pay_status',1)
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })->paginate($perPage)->withQueryString();
                            $sum_price = $bills->sum('total_pay');
                            $text_price= $this->convert_number_to_words($sum_price);
                        
                            $total_pay=Billcustommers::where('id_province',$id_post)
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_status',1)->count();
                            $tienMat=Billcustommers::whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_cash',1)->count();

                            $chuyenKhoan=Billcustommers::whereDate('created_at','>=',$startDate)
                                            ->whereDate('created_at','<=',$endDate)
                                            ->where('pay_transfer',1)->count();
                          
                       
                    }
                    else{
                     dd('Nhập khoản thời gian');
                    }
                }
                if(!$id_post && !$id_service){
                    if($startDate & $endDate){
                        $bills=Billcustommers::with(['custommer','services','catelogies','posts','user'])
                                            ->whereDate('created_at','>=',$startDate)
                                            ->whereDate('created_at','<=',$endDate)
                                            ->where('pay_status',1)
                                            ->paginate($perPage)->withQueryString();

                        $sum_price = $bills->sum('total_pay');
                        $text_price= $this->convert_number_to_words($sum_price);
                        
                        $total_pay=Billcustommers::
                                whereDate('created_at','>=',$startDate)
                                ->whereDate('created_at','<=',$endDate)
                                ->where('pay_status',1)->count();
                        $sumTienMat = Billcustommers::with(['custommer','services','catelogies','posts'])
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_cash',1)
                                    ->sum('total_pay');                    
                        $sumChuyenKhoan = Billcustommers::with(['custommer','services','catelogies','posts'])
                                    ->whereDate('created_at','>=',$startDate)
                                    ->whereDate('created_at','<=',$endDate)
                                    ->where('pay_transfer',1)
                                    ->sum('total_pay'); 
                        $tienMat=Billcustommers::whereDate('created_at','>=',$startDate)
                                                ->whereDate('created_at','<=',$endDate)
                                                ->where('pay_cash',1)->count();
         
                        $chuyenKhoan=Billcustommers::whereDate('created_at','>=',$startDate)
                                        ->whereDate('created_at','<=',$endDate)
                                        ->where('pay_transfer',1)->count();
                    }
                    else{
                        $bills=Billcustommers::with(['custommer','services','catelogies','posts','user'])
                                            ->whereDate('created_at',$currentDate)
                                            ->where('pay_status',1)
                                            ->paginate($perPage)->withQueryString();
                        $sum_price =$bills->sum('total_pay');
                        $text_price= $this->convert_number_to_words($sum_price);
                        $sumTienMat = Billcustommers::with(['custommer','services','catelogies','posts'])
                                        ->whereDate('created_at',$currentDate)
                                        ->where('pay_cash',1)
                                        ->sum('total_pay');                    
                        $sumChuyenKhoan = Billcustommers::with(['custommer','services','catelogies','posts'])
                                        ->whereDate('created_at',$currentDate)
                                        ->where('pay_transfer',1)
                                        ->sum('total_pay'); 
                        $tienMat=Billcustommers::whereDate('created_at',$currentDate)
                                        ->where('pay_cash',1)->count();
                 
                        $chuyenKhoan=Billcustommers::whereDate('created_at',$currentDate)
                                        ->where('pay_transfer',1)->count();
                      
                    }
                }
            }
            else{
                dd('Không có phép truy cập!');
            }   
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
            'tienMat'=>$tienMat,
            'chuyenKhoan'=>$chuyenKhoan,
            'is_admin'=>$is_admin,
            'sumTienMat'=>$sumTienMat,
            'sumChuyenKhoan'=>$sumChuyenKhoan,
            'cosos'=>$cosos,
            
            'can' => [
                'view' => Auth::user()->checkView(config('permission.access.view_report')),
                'create' => Auth::user()->checkCreate(config('permission.access.create_report')),
                'edit' => Auth::user()->checkEdit(config('permission.access.edit_report')),
               
            ],
           // 'condition_fill'=>$condition_fill
            
        ]);
    }
    public function indexVaccine(Request $request)
    {
     //dd($request->all());
        $currentDate = Carbon::now()->toDateString();
       $startDate = $request->startDate;
       $endDate = $request->endDate;
       $id_post = $request->id_coso;
       $buoi = $request->buoi;
    
       $id_service = $request->id_service;
       $perPage = $request->perPage?$request->perPage:50;
       $termFill = $request->term;
       $bills='';
       $query='';
       $sum_price="";
       $tong= '';
       $unpaid='';
       $text_price="";
       $total_pay='';
        $unconfimred='';
        $hcdcconfimred='';
        $posts="";
        $condition_fill=false;
       $isAdmin = Auth()->user();
      //dd($id_post_auth->is_admin);
       //$is_admin=false;
       //$admin = Auth()->user();
       $cosos= CosoModel::get(); 
       $buoi=$request->buoi;
       $qui=$request->qui;
   
        
         if($buoi){
            if($isAdmin->is_admin==1){ 
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
                if($id_service){
                    if($startDate && $endDate){
                    
                        $bills = Billservices::where('id_service',$id_service)
                                            ->whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                                           
                                            ->whereHas('bills',function($qr) use($buoi){
                                                $qr->where('buoi',$buoi);})
                                            ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'))
                                            ->groupBy('id_service')
                                            ->groupBy('don_gia')
                                            ->with([ 'catelogies','bills'])
                                            ->paginate($perPage)->withQueryString();
                        $sum_price = Billcustommers::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                            ->where('buoi',$buoi) ->whereHas('catelogies', function($qr) use($id_service){
                                $qr->where('id_service',$id_service);})->sum('total_pay');
                    }
                    else{
                        $bills = Billservices::where('id_service',$id_service)
                                            ->whereDate('created_at',$currentDate)
                                            ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'),)
                                            ->groupBy('id_service')
                                            ->groupBy('don_gia')
                                            
                                            ->with('catelogies')
                                            ->paginate($perPage)->withQueryString();
                        $sum_price = Billcustommers::whereDate('created_at',$currentDate)
                                            ->where('buoi',$buoi)
                                            ->whereHas('catelogies', function($qr) use($id_service){
                                                $qr->where('id_service',$id_service);})
                                            ->sum('total_pay');

                    }
                }
                if(!$id_service){
                    if(($startDate !=null && $endDate!=null) && ($startDate == $endDate)){
                        $bills = Billservices::whereDate('created_at', $startDate)
                                            ->with([ 'catelogies'])
                                            ->whereHas('bills',function($qr) use($buoi){
                                                $qr->where('buoi',$buoi);
                                            })->with('bills')
                                            ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'))
                                            ->orderBy('id_service','desc')
                                            ->groupBy('id_service')
                                            ->groupBy('don_gia')
                                            ->paginate($perPage)->withQueryString();
                        $sum_price = Billcustommers::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                                                    ->where('buoi',$buoi)->sum('total_pay');
                    }
                    else{ 
                        
                        $bills = Billservices::whereDate('created_at', $currentDate)
                                            ->with([ 'catelogies','bills'])
                                            ->whereHas('bills',function($qr) use($buoi){
                                                $qr->where('buoi',$buoi);
                                            })->with('bills')
                                            ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'),)
                                           
                                            ->orderBy('id_service','desc')
                                            ->groupBy('id_service')
                                            ->groupBy('don_gia')
                                            ->paginate($perPage)->withQueryString();
                                            
                        $sum_price = Billcustommers::whereDate('created_at',$currentDate)->where('buoi',$buoi)->sum('total_pay');
                    }

                }    
            }
            if($isAdmin->is_admin !=1 || $isAdmin->is_admin == null){ 
                    if($id_service){
                        if($startDate && $endDate){
                            $bills = Billservices::where('id_service',$id_service)->whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                                                    ->with([ 'catelogies','bills'])
                                                    ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'), DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as formatdate"))
                                                    ->whereHas('bills', function($qr) use($isAdmin){
                                                            $qr->where('user_created',$isAdmin->id);
                                                    })
                                                    
                                                    ->groupBy('don_gia')
                                                    ->groupBy('id_service')
                                                    ->groupBy('formatdate')
                                                    ->paginate($perPage)->withQueryString();
                            $sum_price = Billcustommers::where('user_created',$isAdmin->id)->whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                                                    ->where('buoi',$buoi) ->whereHas('catelogies', function($qr) use($id_service){
                                                        $qr->where('id_service',$id_service);})->sum('total_pay');
                        }
                        else{
                            $bills = Billservices::where('id_service',$id_service)
                                                ->whereDate('created_at',$currentDate)
                                                ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'), DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as formatdate"))
                                                ->whereHas('bills', function($qr) use($isAdmin){
                                                    $qr->where('user_created',$isAdmin->id);
                                                })
                                                ->groupBy('id_service')
                                                ->groupBy('don_gia')
                                                ->groupBy('formatdate')
                                                ->with(['catelogies','bills'])
                                                ->paginate($perPage)->withQueryString();
                        $sum_price = Billcustommers::where('user_created',$isAdmin->id)->whereDate('created_at','>=', $currentDate)
                                                ->where('buoi',$buoi) ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);})->sum('total_pay');
                        }
                    }
                    else{
                        if($startDate & $endDate){
                            $bills = Billservices::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                                                    ->with([ 'catelogies','bills'])
                                                    ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'), 
                                                    )
                                                    ->whereHas('bills', function($qr) use($isAdmin){
                                                        $qr->where('user_created',$isAdmin->id);
                                                    })
                                                    ->groupBy('don_gia')
                                                    ->groupBy('id_service')
                                                    ->paginate($perPage)->withQueryString();
                            $sum_price = Billcustommers::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)->where('user_created',$isAdmin->id)->sum('total_pay');
                        }
                        else{ 
                            $bills = Billservices::whereDate('created_at', $currentDate)
                                                    ->with([ 'catelogies','bills'])
                                                    ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'), DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as formatdate"))
                                                    ->whereHas('bills', function($qr) use($isAdmin){
                                                            $qr->where('user_created',$isAdmin->id);
                                                    })
                                                    //->groupBy('id_bill')
                                                    ->groupBy('don_gia')
                                                    ->groupBy('id_service')
                                                    ->groupBy('formatdate')
                                                    ->paginate($perPage)->withQueryString();
                            //$sum_price = $bills->bills()->sum('total_pay');                   
                            $sum_price = Billcustommers::whereDate('created_at',$currentDate)->where('user_created',$isAdmin->id)->sum('total_pay');
                            
                        }
                    } 
            }
            if(!$isAdmin->is_admin==1 && !$isAdmin){
                    dd('Không có phép truy cập!');
            }   
         }
         else{
            if($isAdmin->is_admin==1){ 
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
                if($id_service){
                    if($startDate && $endDate){
                        $bills = Billservices::where('id_service',$id_service)->whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                        ->with('bills')
                        ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'))
                        ->groupBy('id_service')
                        ->groupBy('don_gia')
                       
                        ->with([ 'catelogies','bills'])
                        ->paginate($perPage)->withQueryString();
                        $sum_price = Billcustommers::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)->sum('total_pay');
                    }
                    else{
                        $bills = Billservices::where('id_service',$id_service)
                                            ->whereDate('created_at',$currentDate)
                                            ->select('id_service','don_gia')
                                            ->groupBy('id_service')
                                            ->groupBy('don_gia')
                                            ->with('catelogies')
                                            ->paginate($perPage)->withQueryString();
                    }
                }
                if(!$id_service){
                    if($startDate && $endDate){
                        $bills = Billservices::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                                            ->with([ 'catelogies','bills'])
                                            ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'))
                                            ->orderBy('id_service','desc')
                                            ->groupBy('id_service')
                                            ->groupBy('don_gia')
                                            ->paginate($perPage)->withQueryString();
                                        
                        $sum_price = Billcustommers::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)->sum('total_pay');
                    }
                    else{ 
                       
                        $bills = Billservices::whereDate('created_at', $currentDate)
                                            ->with([ 'catelogies','bills'])
                                            ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'),
                                            )
                                            ->orderBy('id_service','desc')
                                            ->groupBy('id_service')
                                            ->groupBy('don_gia')
                                            ->paginate($perPage)->withQueryString();
                                            
                        $sum_price = Billcustommers::whereDate('created_at',$currentDate)->sum('total_pay');
                    }

                }    
            }
            if($isAdmin->is_admin !=1 || $isAdmin->is_admin == null){ 
                    if($id_service){
                        if($startDate && $endDate){
                            $bills = Billservices::where('id_service',$id_service)->whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                                                    ->with([ 'catelogies','bills'])
                                                    ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'), DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as formatdate"))
                                                    ->whereHas('bills', function($qr) use($id_post_auth){
                                                            $qr->where('user_created',$id_post_auth->id);
                                                    })
                                                    
                                                    ->groupBy('don_gia')
                                                    ->groupBy('id_service')
                                                    ->groupBy('formatdate')
                                                    ->paginate($perPage)->withQueryString();
                        }
                        else{
                            $bills = Billservices::where('id_service',$id_service)
                                                ->whereDate('created_at',$currentDate)
                                                ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'), DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as formatdate"))
                                                ->whereHas('bills', function($qr) use($id_post_auth){
                                                    $qr->where('user_created',$id_post_auth->id);
                                                })
                                                ->groupBy('id_service')
                                                ->groupBy('don_gia')
                                                ->groupBy('formatdate')
                                                ->with(['catelogies','bills'])
                                                ->paginate($perPage)->withQueryString();
                        }
                    }
                    else{
                        if($startDate & $endDate){
                            $bills = Billservices::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                                                    ->with([ 'catelogies','bills'])
                                                    ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'), DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as formatdate"))
                                                    ->whereHas('bills', function($qr) use($isAdmin){
                                                        $qr->where('user_created',$isAdmin->id);
                                                    })
                                                   
                                                    //->groupBy('id_bill')
                                                    ->groupBy('don_gia')
                                                    ->groupBy('id_service')
                                                    ->groupBy('formatdate')
                                                    ->paginate($perPage)->withQueryString();
                            $sum_price = Billcustommers::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)->where('buoi',$buoi)->where('user_created',$isAdmin->id)->sum('total_pay');
                        }
                        else{ 
                            $bills = Billservices::whereDate('created_at', $currentDate)
                                                    ->with([ 'catelogies','bills'])
                                                    ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'), DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as formatdate"))
                                                    ->whereHas('bills', function($qr) use($isAdmin){
                                                            $qr->where('user_created',$isAdmin->id);
                                                    })
                                                    //->groupBy('id_bill')
                                                    ->groupBy('don_gia')
                                                    ->groupBy('id_service')
                                                    ->groupBy('formatdate')
                                                    ->paginate($perPage)->withQueryString();
                            //$sum_price = $bills->bills()->sum('total_pay');
                                                
                            $sum_price = Billcustommers::whereDate('created_at',$currentDate)->where('user_created',$isAdmin->id)->sum('total_pay');
                            
                        }
                    } 
            }
            if(!$isAdmin->is_admin==1 && !$isAdmin){
                    dd('Không có phép truy cập!');
            }   
         }
      
    
       
        $filters=[
            'perPage'=>$request->perPage,
            'startDate'=>$request->startDate,
            'endDate'=>$request->endDate,
            'buoi'=>$request->buoi,
            'qui'=>$request->qui,
        ];
        return Inertia::render('Report/vaccineTiem',[
            'bills'=>$bills,
            'posts'=>$posts,
            'services'=>Catelory::select('id','medicine_name')->get(),
            'filters'=>$filters,
            'sum_price'=>$sum_price?$sum_price:'',
           
            'tong'=>$tong,
            'can' => [
                'view' => Auth::user()->checkView(config('permission.access.view_report')),
                'create' => Auth::user()->checkCreate(config('permission.access.create_report')),
                'edit' => Auth::user()->checkEdit(config('permission.access.edit_report')),
               
            ],
           // 'condition_fill'=>$condition_fill
            
        ]);
    }
    public function DoanhthuGiavon(Request $request){
      
        return Inertia::render('Report/ReportGiavon');
    }
    public function generalReport(Request $request)
    {
      
        $currentDate = Carbon::now()->toDateString();
       $startDate = $request->startDate;
       $endDate = $request->endDate;
       $id_post = $request->id_coso;
       $buoi = $request->buoi;
    
       $id_service = $request->id_service;
       $perPage = $request->perPage?$request->perPage:50;
       $termFill = $request->term;
       $bills='';
       $query='';
       $sum_price="";
       $tong= '';
       $unpaid='';
       $text_price="";
       $total_pay='';
        $unconfimred='';
        $hcdcconfimred='';
        $posts="";
        $condition_fill=false;
       $is_admin=false;
       $admin = Auth()->user();
       $id_user=$admin->id;
       $cosos= CosoModel::get();
        if($admin->is_admin==1){ 
           // dd($request->all());
                $is_admin=true;
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
                if(!$id_post && $id_service){
                    if($startDate && $endDate){
                       // dd($id_service);
                        if($buoi){
                           // dd('buoi');
                            $bills = Billservices::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                            ->with([ 'catelogies','bills'])
                            ->whereHas('bills', function($qr) use($buoi){
                                    $qr->where('buoi',$buoi);
                            })
                            ->whereHas('catelogies', function($qr) use($id_service){
                                $qr->where('id_service',$id_service);
                            })
                            ->orderBy('id_bill','desc')
                            ->paginate($perPage)->withQueryString();
                        }
                        else{
                            $bills = Billservices::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                                                        ->with([ 'catelogies','bills'])
                                                        ->whereHas('catelogies', function($qr) use($id_service){
                                                                $qr->where('id',$id_service);
                                                        
                                                        })
                                                        ->orderBy('id_bill','desc')
                                                        ->paginate($perPage)->withQueryString();
                        }
                 
                       
                       
                    }
                    else{
                        $bills = Billservices::where('id_service',$id_service)->whereDate('created_at',$currentDate)
                                            ->select('id_service','don_gia', DB::raw('sum(sl) as tongSL'))
                                            ->groupBy('id_service')
                                            ->groupBy('don_gia')
                                            ->with('catelogies')
                                            ->paginate($perPage)->withQueryString();
                       
                        $sum_price = Billcustommers::join('billservices','billservices.id_bill','billcustommers.id')
                        ->whereHas('services', function($qr) use($id_service){
                                        $qr->where('id_service',$id_service);
                                        })
                                        
                                        ->whereDate('billservices.created_at',$currentDate)
                                        ->select('billservices.sl','billservices.don_gia')
                                        ->get();
                   
                    }
                }
                if(!$id_post && !$id_service){
                    if($buoi){
                        if($startDate && $endDate){
                            $bills = Billservices::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=', $endDate)
                                    ->with([ 'catelogies','bills'])
                                    ->whereHas('bills', function($qr) use($buoi){
                                            $qr->where('buoi',$buoi);
                                    
                                    })
                                    ->orderBy('id_bill','desc')
                                    ->paginate($perPage)->withQueryString();
                            $sum_price = Billcustommers::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=', $endDate)
                                                        ->where('buoi',$buoi)
                                                        ->sum('total_pay');
                        }
                        else{
                            $bills = Billservices::whereDate('created_at', $currentDate)
                                                ->with([ 'catelogies','bills'])
                                                ->whereHas('bills', function($qr) use($buoi){
                                                        $qr->where('buoi',$buoi);
                                                
                                                })
                                                ->orderBy('id_bill','desc')
                                                ->paginate($perPage)->withQueryString();
                            $sum_price = Billcustommers::where('buoi',$buoi)->whereDate('created_at',$currentDate)->sum('total_pay');
                        }
                        
                    }
                    else{ 
                        if($startDate && $endDate){
                            $bills = Billservices::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=', $endDate)
                                                ->with([ 'catelogies','bills'])
                                                ->orderBy('id_bill','desc')
                                                ->paginate($perPage)->withQueryString();
                            $sum_price = Billcustommers::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=', $endDate)
                                                        ->sum('total_pay');
                        }
                        else{
                            $bills = Billservices::whereDate('created_at', $currentDate)
                                                ->with([ 'catelogies','bills'])
                                               
                                                ->orderBy('id_bill','desc')
                                                ->paginate($perPage)->withQueryString();
                            $sum_price = Billcustommers::whereDate('created_at',$currentDate)->sum('total_pay');
                        }
                    }
                }
        }
        if($admin->is_admin !=1 || $admin->is_admin ==null){ 
            //dd($request->all());
                $is_admin=true;
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
                if($id_service){
                    
                    if($startDate && $endDate){
                       // dd($id_service);
                        if($buoi){
                           // dd('buoi');
                            $bills = Billservices::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                                                ->with([ 'catelogies','bills'])
                                                ->whereHas('bills', function($qr) use($buoi){
                                                        $qr->where('buoi',$buoi);
                                                })
                                                ->whereHas('catelogies', function($qr) use($id_service){
                                                    $qr->where('id_service',$id_service);
                                                })
                                                ->whereHas('bills', function($qr) use($id_user){
                                                    $qr->where('user_created',$id_user);
                                            
                                                })
                                                ->orderBy('id_bill','desc')
                                                ->paginate($perPage)->withQueryString();
                        }
                        else{
                            $bills = Billservices::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=',$endDate)
                                                        ->with([ 'catelogies','bills'])
                                                        ->whereHas('catelogies', function($qr) use($id_service){
                                                                $qr->where('id',$id_service);
                                                        
                                                        })
                                                        ->whereHas('bills', function($qr) use($id_user){
                                                            $qr->where('user_created',$id_user);
                                                    
                                                        })
                                                        ->orderBy('id_bill','desc')
                                                        ->paginate($perPage)->withQueryString();
                        }     
                    }
                    else{
                        $bills = Billservices::whereDate('created_at',$currentDate)
                                    ->with([ 'catelogies','bills'])
                                    ->whereHas('catelogies', function($qr) use($id_service){
                                        $qr->where('id_service',$id_service);
                                        })
                                    ->whereHas('bills', function($qr) use($id_user){
                                        $qr->where('user_created',$id_user);
                                
                                })
                                    ->orderBy('id_bill','desc')
                                    ->paginate($perPage)->withQueryString();

                    $sum_price = Billcustommers::whereDate('created_at', $currentDate)
                        ->where('buoi',$id_user)
                        ->where('user_created',$id_user)
                        ->sum('total_pay');
                   
                    }
                }
               
                if(!$id_service){
                            if($buoi){
                                if($startDate && $endDate){
                                    $bills = Billservices::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=', $endDate)
                                            ->with([ 'catelogies','bills'])
                                            ->whereHas('bills', function($qr) use($buoi){
                                                    $qr->where('buoi',$buoi);
                                            
                                            })
                                            ->whereHas('bills', function($qr) use($id_user){
                                                $qr->where('user_created',$id_user);
                                        
                                        })
                                            ->orderBy('id_bill','desc')
                                            ->paginate($perPage)->withQueryString();
                                    $sum_price = Billcustommers::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=', $endDate)
                                                                ->where('buoi',$buoi)
                                                                ->where('user_created',$id_user)
                                                                ->sum('total_pay');
                                }
                                else{
                                    $bills = Billservices::whereDate('created_at', $currentDate)
                                                        ->with([ 'catelogies','bills'])
                                                        ->whereHas('bills', function($qr) use($buoi){
                                                                $qr->where('buoi',$buoi);
                                                        
                                                        })
                                                        ->whereHas('bills', function($qr) use($id_user){
                                                            $qr->where('user_created',$id_user);
                                                    
                                                    })
                                                        ->orderBy('id_bill','desc')
                                                        ->paginate($perPage)->withQueryString();
                                    $sum_price = Billcustommers::where('user_created',$id_user)->whereDate('created_at',$currentDate)->sum('total_pay');
                                }
                            }
                            else{ 
                                if($startDate && $endDate){
                                    $bills = Billservices::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=', $endDate)
                                            ->with([ 'catelogies','bills'])
                                            ->whereHas('bills', function($qr) use($id_user){
                                                $qr->where('user_created',$id_user);
                                            })
                                            ->orderBy('id_bill','desc')
                                            ->paginate($perPage)->withQueryString();
                                    $sum_price = Billcustommers::whereDate('created_at','>=', $startDate)->whereDate('created_at','<=', $endDate)
                                                                ->where('user_created',$id_user)
                                                                ->sum('total_pay');
                                }
                                else{
                                    $bills = Billservices::whereDate('created_at', $currentDate)
                                                        ->with([ 'catelogies','bills'])
                                                        ->whereHas('bills', function($qr) use($id_user){
                                                            $qr->where('user_created',$id_user);
                                                    
                                                    })
                                                        ->orderBy('id_bill','desc')
                                                        ->paginate($perPage)->withQueryString();
                                    $sum_price = Billcustommers::where('user_created',$id_user)->whereDate('created_at',$currentDate)->sum('total_pay');
                                }
                            }
                }
            
           
        }
        $filters=[
            'perPage'=>$request->perPage,
            'startDate'=>$request->startDate,
            'endDate'=>$request->endDate,
            'buoi'=>$request->buoi,
        ];
        return Inertia::render('Report/BaoCaoThu',[
            'bills'=>$bills,
            'posts'=>$posts,
            'services'=>Catelory::select('id','name')->get(),
            'filters'=>$filters,
           'sum_price'=>$sum_price?$sum_price:'',
           
            'is_admin'=>$is_admin,
            'tong'=>$tong,
            //'sumTienMat'=>$sumTienMat,
            //'sumChuyenKhoan'=>$sumChuyenKhoan,
           // 'cosos'=>$cosos,
            
            'can' => [
                'view' => Auth::user()->checkView(config('permission.access.view_report')),
                'create' => Auth::user()->checkCreate(config('permission.access.create_report')),
                'edit' => Auth::user()->checkEdit(config('permission.access.edit_report')),
               
            ],
           // 'condition_fill'=>$condition_fill
            
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
