<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;
use App\Models\Menu;
use App\Models\MenuRole;

class Role extends Model
{
    use HasFactory;
    protected $fillable=[];
    protected $guarded=['id'];

    public function users():BelongsToMany{
        return $this->BelongsToMany(User::class);
    }
    public function permissions():BelongsToMany{
        return $this->belongsToMany(Menu::class,'menu_roles', 'id_role','id_menu')->select('menu_roles.id_menu','menu_roles.show_','menu_roles.add_','menu_roles.edit_','menu_roles.delete_');
    }
    public function menus():BelongsToMany{
        return $this->belongsToMany(Menu::class,'menu_roles', 'id_role','id_menu');
    }
   
    public function roleuser(){
        return $this->belongsToMany(User::class,'user_role', 'user_id','role_id');
    }
}
