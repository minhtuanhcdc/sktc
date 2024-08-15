<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Catelory extends Model
{
    use HasFactory;
    protected $guarded=['id'];

    public function group():BelongsTo{
        return $this->BelongsTo(CatelogyGroup::class, 'id_group')->select('id','name');
    }
}
