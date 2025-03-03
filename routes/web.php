<?php

use App\Http\Controllers\RouteController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/', function(){
    return redirect()->route('login');
});



Route::get('usuario-no-autorizado', function(){
    return view('admin.errores.usuario-inactivo');
})->name('no-autorizado');

Route::get('migrate', function(){
    Artisan::call('migrate');
});
Route::get('storage_link', function(){
    Artisan::call('storage:link');
});
Route::get('cache-clear', function(){
    Artisan::call('cache:clear');
});
Route::get('config-clear', function(){
    Artisan::call('config:clear');
});
