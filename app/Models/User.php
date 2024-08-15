<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Role;
use App\Models\MenuRole;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'status',
        'password',
    ];
    protected $guarded=['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function roles():BelongsToMany{
        return $this->belongsToMany(Role::class,'role_user','user_id','role_id');
       
    }
   
    public function posts(){
        return $this->hasOne(Post::class,'id','id_post')->select('id','name','code','province_code');
    }
    public function cosos(){
        return $this->hasOne(CosoModel::class,'id','id_post')->select('id','code','name');
    }
    public function role(){
        return $this->hasOne(Role::class,'id','id_role')->select('id','name');
    }
    public function menuRoles(){
        //dd($this->belongsToMany(Menu::class,'menu-users','user_id','role_id'));
        return $this->belongsToMany(Menu::class,'menu_roles','id_menu','id_role');
    }
    public function checkView($permissionCheck){
        $roles=auth()->user()->roles;
        //dd($roles);
        foreach($roles as $role){
            $pm=menuRole::where('id_role',$role->id)->get();
           if($pm->contains('show_',$permissionCheck)){
               return true;
           }
        }
        return false;
    }
    public function checkCreate($permissionCheck){
        $roles=auth()->user()->roles;
       // dd($roles);
        foreach($roles as $role){
            $pm=menuRole::where('id_role',$role->id)->get();
           
           if($pm->contains('add_',$permissionCheck)){
               return true;
           }
        }
        return false;
    }
    public function checkEdit($permissionCheck){
        $roles=auth()->user()->roles;
        //dd($roles);
        foreach($roles as $role){
            $pm=menuRole::where('id_role',$role->id)->get();
           //dd($permissions);
           if($pm->contains('edit_',$permissionCheck)){
               return true;
           }
        }
        return false;
    }
    public function checkDelete($permissionCheck){
        $roles=auth()->user()->roles;
        //dd($roles);
        foreach($roles as $role){
            $pm=menuRole::where('id_role',$role->id)->get();
           //dd($permissions);
           if($pm->contains('delete_',$permissionCheck)){
               return true;
           }
        }
        return false;
    }
}
