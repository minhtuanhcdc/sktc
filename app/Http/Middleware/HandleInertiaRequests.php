<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Models\Menu;
use App\Models\MenuRole;
use App\Models\User;
use App\Models\RoleUser;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        // return array_merge(parent::share($request), [
        //     //
        // ]);

        return array_merge(parent::share($request), [
            'menuPermission' => function() use($request){
                //$user = $request->user();
                //dd($user);
                if($request->user()){
                    $menuAccess=RoleUser::where('user_id',auth()->id())
                    ->join('roles','roles.id','role_user.role_id')
                    ->join('menu_roles','menu_roles.id_role','roles.id')
                    ->join('menus','menus.id','menu_roles.id_menu')
                    ->where('menus.status',1)     
                    ->select('menus.id as menuId','menus.order as order','menus.name as menuName','menus.url as Url','menus.id_parent as parent_id','menus.icon as icon','menus.menu_group as menu_group')
                    
                    ->where('menu_group','!=',2)
                    //->groupBy('menuId')
                    ->orderBy('order','asc')
                    ->get();
                    //dd($menuAccess);
                    return [
                        'menuAccess' =>$menuAccess,
                        //'pemissions' =>$pemissions,
                    ];
                }
            },
            'menuAdmin' => function() use($request){
                if($request->user()){
                    $menuAccess=RoleUser::where('user_id',auth()->id())
                    ->join('roles','roles.id','role_user.role_id')
                    ->join('menu_roles','menu_roles.id_role','roles.id')
                    ->join('menus','menus.id','menu_roles.id_menu')
                    ->select('menus.id as menuId','menus.order as order','menus.name as menuName','menus.url as Url','menus.id_parent as parent_id','menus.icon as icon','menus.menu_group as menu_group')
                    ->where('menu_group',2)
                    ->where('menus.status',1)
                    //->groupBy('menuId')
                    ->orderBy('order','asc')
                    ->get();
                  
                    return [
                        'menuAccess' =>$menuAccess,
                       
                    ];
                }

            }

     ]);
    }
}
