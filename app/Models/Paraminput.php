<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paraminput extends Model
{
    use HasFactory;
    public function childs()
    {
        //return $this->belongsTo(Infobase::class,'id_children')->get();
        return $this->hasOne(Infobase::class,'id', 'id_children');
    }
}
