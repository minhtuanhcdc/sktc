<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use App\Models\Menu;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Inertia::share('flash', function(){

            return [
                'success'=>Session::get('success'),
                'failure'=>Session::get('failure'),
                'duplicates'=>Session::get('duplicates'),

            ];

        });
        Inertia::share('nav', function(){

            return [
                
                'menues'=>Menu::where('status',1)->where('menu_group','!=',2)->select('id','name','url','icon','menu_group','order')
                        ->orderby('order','asc')->get(),
                'menu_admin'=>Menu::where('status',1)->where('menu_group','=',2)->select('id','name','url','icon','menu_group','order')
                        ->orderby('order','asc')->get(),
               
            ];

        });
    }
}
