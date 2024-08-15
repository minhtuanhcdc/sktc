<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billservices extends Model
{
    use HasFactory;
    protected $table = "billservices";
    public function catelogies(){
        return $this->belongsTo(Catelory::class,'id_service','id');
    }

    public function user(){
       $this->primaryKey = 'user_created';
       return $this->belongsTomany(User::class,'billcustommers','id','user_created');

    }
   
    public function bills(){
        return $this->belongsTo(Billcustommers::class,'id_bill','id')->with([ 'custommer','user']);
    }
}
