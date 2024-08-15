<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Billcustommers extends Model
{
    use HasFactory;
    public function custommer(){
        return $this->hasOne(Custommer::class,'id','id_custommer')->with(['province','district','ward']);
    }
    public function user(){
        return $this->hasOne(User::class,'id','user_created')->select(['id','name']);
    }
    public function posts(){
        return $this->belongsTo(Post::class,'id_post','id')->select('id','name','code');
    }
    public function cosos(){
        return $this->belongsTo(CosoModel::class,'id_post','id')->select('id','name','code');
    }
    function services(){
        return $this->hasMany(Billservices::class, 'id_bill','id')->with('catelogies');
    }
    public function catelogies(){
        //dd($this->belongsTomany(Testname::class,'billnames','billtest_id','testname_id'));
            return $this->belongsTomany(Catelory::class,'billservices','id_bill','id_service')->withPivot(['sl']);
    }
    
}
