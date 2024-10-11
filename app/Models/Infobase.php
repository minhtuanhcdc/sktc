<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Infobase extends Model
{
    use HasFactory;
    protected $guarded=['id'];
    protected $table = "infobases";
    public function paraminput():BelongsTo{
        return $this->BelongsTo(Paraminput::class,'id','id_children')->select('id_children','input_date','month','length','weigth','BMI','lengthForAge','weigthForAge','weigthForLength');
    }
}