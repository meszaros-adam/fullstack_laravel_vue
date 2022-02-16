<?php

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

Route::post('app/create_tag',[App\Http\Controllers\AdminController::class, 'addTag']);
Route::get('app/get_tags',[App\Http\Controllers\AdminController::class, 'getTag']);
Route::post('app/edit_tag',[App\Http\Controllers\AdminController::class, 'editTag']);
Route::post('app/delete_tag',[App\Http\Controllers\AdminController::class, 'deleteTag']);
Route::post('app/upload',[App\Http\Controllers\AdminController::class, 'upload']);
Route::post('app/delete_image',[App\Http\Controllers\AdminController::class, 'deleteImage']);
Route::post('app/create_category',[App\Http\Controllers\AdminController::class, 'addCategory']);
Route::get('app/get_category',[App\Http\Controllers\AdminController::class, 'getCategory']);
Route::post('app/edit_category',[App\Http\Controllers\AdminController::class, 'editCategory']);
Route::post('app/delete_category',[App\Http\Controllers\AdminController::class, 'deleteCategory']);
Route::post('app/create_user',[App\Http\Controllers\AdminController::class, 'addUser']);
Route::get('app/get_users',[App\Http\Controllers\AdminController::class, 'getUser']);
Route::post('app/edit_user',[App\Http\Controllers\AdminController::class, 'editUser']);

Route::get('/', function () {
    return view('welcome');
});

//Auth::routes();
//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::any('{slug}', function(){
    return view('welcome'); 
});
