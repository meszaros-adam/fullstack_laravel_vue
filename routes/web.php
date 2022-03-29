<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminCheck;

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

Route::prefix('app')->middleware([AdminCheck::class])->group(function(){
    Route::post('/create_tag',[App\Http\Controllers\AdminController::class, 'addTag']);
    Route::get('/get_tags',[App\Http\Controllers\AdminController::class, 'getTags']);
    Route::post('/edit_tag',[App\Http\Controllers\AdminController::class, 'editTag']);
    Route::post('/delete_tag',[App\Http\Controllers\AdminController::class, 'deleteTag']);
    Route::post('/upload',[App\Http\Controllers\AdminController::class, 'upload']);
    Route::post('/upload_editor_pic',[App\Http\Controllers\AdminController::class, 'uploadEditorPic']);
    Route::post('/delete_image',[App\Http\Controllers\AdminController::class, 'deleteImage']);
    Route::post('/create_category',[App\Http\Controllers\AdminController::class, 'addCategory']);
    Route::get('/get_category',[App\Http\Controllers\AdminController::class, 'getCategory']);
    Route::post('/edit_category',[App\Http\Controllers\AdminController::class, 'editCategory']);
    Route::post('/delete_category',[App\Http\Controllers\AdminController::class, 'deleteCategory']);
    Route::post('/create_user',[App\Http\Controllers\AdminController::class, 'addUser']);
    Route::post('/admin_login',[App\Http\Controllers\AdminController::class, 'adminLogin']);
    Route::get('/get_users',[App\Http\Controllers\AdminController::class, 'getUser']);
    Route::post('/edit_user',[App\Http\Controllers\AdminController::class, 'editUser']);
    Route::post('/create_blog',[App\Http\Controllers\AdminController::class, 'addBlog']);

    //role routes
    Route::post('/create_role',[App\Http\Controllers\AdminController::class, 'addRole']);
    Route::get('/get_roles',[App\Http\Controllers\AdminController::class, 'getRoles']);
    Route::post('/edit_role',[App\Http\Controllers\AdminController::class, 'editRole']);
    Route::post('/assign_role',[App\Http\Controllers\AdminController::class, 'assignRole']);
});

Route::get('/logout',[App\Http\Controllers\AdminController::class, 'logout']);
Route::get('/',[App\Http\Controllers\AdminController::class, 'index']);
Route::any('{slug}',[App\Http\Controllers\AdminController::class, 'index']);