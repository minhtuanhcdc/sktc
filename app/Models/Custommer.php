<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Custommer extends Model
{
    use HasFactory;
    protected $guarded=['id'];
    public function billcustommer(){
        return $this->hasOne(Billcustommers::class);
     }
     public function province()
     {
      return $this->hasOne(Province::class,'code','id_province');
     }
     public function district()
     {
       return $this->hasOne(District::class,'code','id_district');
     }
     public function ward(){
       return $this->hasOne(Ward::class,'code','id_ward');
     }
}
