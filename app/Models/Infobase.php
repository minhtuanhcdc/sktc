<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Infobase extends Model
{
    use HasFactory;
    protected $guarded=['id'];
    protected $table = "infobases";
    public function paraminput():HasMany{
        return $this->HasMany(Paraminput::class,'id_children','id');
    }
   
   
    public function khamdinhkis():HasMany
    {
        return $this->hasMany(Khamdinhky::class,'id_children','id')->select('id_children','ngay_kham')->orderBy('ngay_kham','asc');
    }
    public function vitamins():HasMany
    {
        return $this->hasMany(Vitamin::class,'id_children','id')->select('id_children','ngay_uong')->orderBy('ngay_uong','asc');
    }
    
}