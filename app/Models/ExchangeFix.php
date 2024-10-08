<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeFix extends Model
{
    use HasFactory;
    protected $guarded=['id'];
    public function user(){
        return $this->belongsTo(User::class,'id_user','id')->select('id','name',);
    }
}
