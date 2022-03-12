<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\Category;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(Request $request){

        //first check if you are logged in...
        if(!Auth::check() && $request->path()!='login'){
            return redirect('/login');
        }
        if(!Auth::check() && $request->path() =='login'){
            return view('welcome');
        }

        //you are alredy logged in so check for if you are an admin user...
        $user = Auth::user();

        if($user->role->isAdmin == 0){
            return redirect('/login');
        }
        if($request->path() == 'login'){
            return redirect('/');
        }
        return view('welcome');
    }
    public function logout(){
        Auth::logout();
        return redirect('/login');
    }
    public function addTag(Request $request){
        //validate request
        $this->validate($request,[
            'tagName' => 'required',
        ]);

        return Tag::create([
            'tagName' => $request->tagName
        ]);
    }
    public function editTag(Request $request){
        //validate request
        $this->validate($request,[
            'tagName' => 'required',
            'id' => 'required',
        ]);

        return Tag::where('id', $request->id)->update([
            'tagName' => $request->tagName
        ]);
    }
    public function deleteTag(Request $request){
        //validate request
        $this->validate($request,[
            'id' => 'required',
        ]);

        return Tag::where('id', $request->id)->delete();
    }

    public function getTags(){
        return Tag::orderBy('id', 'desc')->get();
    }
    public function upload(Request $request){
        $this->validate($request,[
            'file' => 'required|mimes:jpg,jpeg,png'
        ]);
        $picName = time().'.'.$request->file->extension();
        $request->file->move(public_path('uploads'), $picName);
        return $picName;
    }
    public function deleteImage(Request $request){
        $fileName = $request->imageName;
        $this->deleteFileFromServer($fileName);
        return 'done';
    }
    public function deleteFileFromServer($fileName, $hasFullPath = false){
        if(!$hasFullPath){
            $filePath = public_path().'/uploads/'.$fileName;
        }
        $filePath = public_path().$fileName;
        if(file_exists($filePath)){
            @unlink($filePath);
        }    
        return;
    }
    public function addCategory(Request $request){
        //validate request
        $this->validate($request,[
            'categoryName' => 'required',
            'iconImage' => 'required',
        ]);

        return Category::create([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage,
        ]);
    }
    public function getCategory(){
        return Category::orderBy('id', 'desc')->get();
    }
    public function editCategory(Request $request){
        $this->validate($request,[
            'categoryName' => 'required',
            'iconImage' => 'required',
            'id' => 'required',
        ]);

        return Category::where('id', $request->id)->update([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage,

        ]);
    }
    public function deleteCategory(Request $request){
        //validate request
        $this->validate($request,[
            'id' => 'required',
            'iconImage' => 'required',
        ]);

        $this->deleteFileFromServer($request->iconImage, true);

        return Category::where('id', $request->id)->delete();
    }
    public function addUser(Request $request){
        //validate request
        $this->validate($request,[
            'fullName' => 'required',
            //email should be unique in users table
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|min:6',
            'role_id' => 'required',
        ]);
        
        $password = bcrypt($request->password);
        $user = User::create([
            'fullName' => $request->fullName,
            'email' => $request->email,
            'password' => $password,
            'role_id' => $request->role_id,
        ]);
        return  $user;
    }
    public function getUser(){
        //where user type not equal user
        return User::orderBy('id', 'desc')->get();
    }
    public function editUser(Request $request){
        //validate request
        $this->validate($request,[
            'fullName' => 'required',
            //email should be unique in users table
            'email' => 'bail|required|email|unique:users,email,'.$request->id,
            'password' => 'min:6',
            'role_id' => 'required',
            'id' => 'required',
        ]);

        $data = [
            'fullName' => $request->fullName,
            'email' => $request->email,
            'role_id' => $request->role_id,
        ];
        if($request->password){
            $password = bcrypt($request->password);
            $data['password'] = $password;
        }
        $user = User::where('id', $request->id)->update($data);
        return  $user;
    }
    public function adminLogin(Request $request){
        $this->validate($request,[
            'email' => 'bail|required|email',
            'password' => 'bail|required|min:6',
        ]);
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password ])){
            $user = Auth::user();

            if($user->role->isAdmin == 0){
                Auth::logout();
                return response()->json([
                    'msg' => 'Incorrect login details',
                    'User' => $user,
                    ],401);
            }
            return response()->json([
                'msg' => 'You are logged in',
                
            ]);
        }else{
            return response()->json([
            'msg' => 'Incorrect login details',
            ],401);
        }
    }
    public function addRole(Request $request){
        //validate request
        $this->validate($request,[
            'roleName' => 'required',
        ]);

        return Role::create([
            'roleName' => $request->roleName,
        ]);
    }
    public function getRoles(){
        return Role::orderby('id', 'desc')->get();
    }
    public function editRole(Request $request){
        $this->validate($request,[
            'roleName' => 'required',
            'id' => 'required',
        ]);

        return Role::where('id', $request->id)->update([
            'roleName' => $request->roleName
        ]);
    }
    public function assignRole(Request $request){
        $this->validate($request, [
            'permission' => 'required',
            'id' => 'required',
        ]);
        return Role::where('id', $request->id)->update([
            'permission' => $request->permission
        ]);
    }
}
