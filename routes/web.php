<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admins\HomeController;
use App\Http\Controllers\Admins\MenuController;
use App\Http\Controllers\Admins\RoleController;
use App\Http\Controllers\Admins\UserController;
use App\Http\Controllers\Admins\CatelogyGroupController;
use App\Http\Controllers\Admins\QuarantineDirectoryController;
use App\Http\Controllers\Admins\CatelogyController;
use App\Http\Controllers\Admins\CustommerController;
use App\Http\Controllers\Admins\InputInfoController;
use App\Http\Controllers\Admins\PostController;
use App\Http\Controllers\Admins\ImportController;
use App\Http\Controllers\Admins\ReportController;
use App\Http\Controllers\Admins\CosoController;
use App\Http\Controllers\Admins\ExportController;
use App\Http\Controllers\Admins\ExchangeNoChange;

use App\Http\Controllers\Admins\WeightForAgeBoyController;
use App\Http\Controllers\Admins\WeightForAgeGirlController;
use App\Http\Controllers\Admins\LenghtForAgeBoyController;
use App\Http\Controllers\Admins\LengthForAgeGirlController;
use App\Http\Controllers\Admins\WeightForHightBoyController;
use App\Http\Controllers\Admins\WeighForHeightGirlController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
     Route::get('/dashboard', function () {
         return Inertia::render('Dashboard');
     })->name('dashboard');
    

});

Route::prefix('/')
        ->middleware([
        'auth:sanctum',
        'verified',
        ])
    ->group(function(){
            Route::get('home',[HomeController::class,'Index'])->name('home');

            Route::resource('infoinputs',InputInfoController::class);
            Route::resource('custommers',CustommerController::class);

            Route::get('indexExchange',[HomeController::class,'indexExchange'])->name('indexExchange');
            Route::resource('menus',MenuController::class);
            Route::resource('roles',RoleController::class);
            Route::resource('users',UserController::class);
            Route::resource('catelogy_groups',CatelogyGroupController::class);
            Route::resource('quaties',QuarantineDirectoryController::class);
            Route::resource('catelogies',CatelogyController::class);

            Route::resource('weightageboy',WeightForAgeBoyController::class);
            Route::resource('weightforagegirl',WeightForAgeGirlController::class);

            Route::resource('weightforheightboy',WeightForHightBoyController::class);
            Route::resource('weightforheightgirl',WeighForHeightGirlController::class);
            
            Route::resource('lenghtageboy',LenghtForAgeBoyController::class);
            Route::resource('lenghtagegirl',LengthForAgeGirlController::class);
           
            Route::get('/banggia',[CatelogyController::class,'banggia'])->name('banggia');

            Route::post('/storeLocal',[CustommerController::class,'storeLocal'])->name('storeLocal');
           Route::post('/updateBill',[CustommerController::class,'updateBill'])->name('updateBill');
            Route::resource('posts',PostController::class);

            Route::post('/importPost',[ImportController::class,'importPost'])->name('importPost');
            Route::post('/importProvince',[ImportController::class,'importProvince'])->name('importProvince');
            Route::post('/importDistrict',[ImportController::class,'importDistrict'])->name('importDistrict');
            Route::post('/importWard',[ImportController::class,'importWard'])->name('importWard');
            Route::post('/importDanhmuc',[ImportController::class,'importDanhmuc'])->name('importDanhmuc');
            Route::post('/provincePosts',[ImportController::class,'provincePosts'])->name('provincePosts');
            Route::post('/importLenghtForAgeBoy',[ImportController::class,'importLenghtForAgeBoy'])->name('importLenghtForAgeBoy');
            Route::post('/importLenghtForAgeGirl',[ImportController::class,'importLenghtForAgeGirl'])->name('importLenghtForAgeGirl');

            Route::post('/importWeghttForAgeBoy',[ImportController::class,'importWeghttForAgeBoy'])->name('importWeghttForAgeBoy');
            Route::post('/importWeghttForAgeGirl',[ImportController::class,'importWeghttForAgeGirl'])->name('importWeghttForAgeGirl');

            Route::post('/importWeghttForHightBoy',[ImportController::class,'importWeghttForHightBoy'])->name('importWeghttForHightBoy');
            Route::post('/importWeghttForHightGirl',[ImportController::class,'importWeghttForHightGirl'])->name('importWeghttForHightGirl');
          
            Route::resource('exchanges',ExchangeNoChange::class);
            Route::get('/clockExchange',[ExchangeNoChange::class,'clockExchange'])->name('clockExchange');
            Route::resource('reports',ReportController::class);
            Route::get('indexVaccine',[ReportController::class,'indexVaccine'])->name('indexVaccine');
            Route::get('generalReport',[ReportController::class,'generalReport'])->name('generalReport');
            Route::get('/DoanhthuGiavon',[ReportController::class,'DoanhthuGiavon'])->name('DoanhthuGiavon');
            Route::put('confirmPay/{id}',[ReportController::class,'confirmPay'])->name('confirmPay');

            Route::resource('cosos',CosoController::class);
            Route::get('/exportBills',[ExportController::class,'exportBills'])->name('exportBills');
           // Route::put('payTransfer/{id}',[CustommerController::class,'payTransfer'])->name('payTransfer');
            //Route::put('payCash/{id}',[CustommerController::class,'payCash'])->name('payCash');
            Route::get('/exportReport',[ExportController::class,'exportReport'])->name('exportReport');
            Route::get('/vaccineReport',[ExportController::class,'vaccineReport'])->name('vaccineReport');
            Route::get('/generalExport',[ExportController::class,'generalExport'])->name('generalExport');
            Route::get('/BaoCaoThuExport',[ExportController::class,'BaoCaoThuExport'])->name('BaoCaoThuExport');
    });
