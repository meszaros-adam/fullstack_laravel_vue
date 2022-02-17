<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(Request $request){

        //first check if you are logged in and you are admin user...
        if(!Auth::check() && $request->path()!='login'){
            return redirect('/login');
        }
        if(!Auth::check() && $request->path() =='login'){
            return view('welcome');
        }

        //you are alredy logged in so check for if you are an admin user...
        $user = Auth::user();

        if($user->userType == 'User'){
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
        $this->valiate($request,[
            'tagName' => 'required',
        ]);

        return Tag::create([
            'tagName' => $request->tagName
        ]);
    }
    public function editTag(Request $request){
        //validate request
        $this->valiate($request,[
            'tagName' => 'required',
        ]);

        return Tag::where('id', $request->id)->update([
            'tagName' => $request->tagName
        ]);
    }
    public function deleteTag(Request $request){
        //validate request
        $this->valiate($request,[
            'id' => 'required',
        ]);

        return Tag::where('id', $request->id)->delete();
    }

    public function getTag(){
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
        $this->valiate($request,[
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
        $this->valiate($request,[
            'categoryName' => 'required',
            'iconImage' => 'required',
        ]);

        return Category::where('id', $request->id)->update([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage,

        ]);
    }
    public function deleteCategory(Request $request){
        //validate request
        $this->valiate($request,[
            'id' => 'required',
            'iconImage' => 'required',
        ]);

        $this->deleteFileFromServer($request->iconImage, true);

        return Category::where('id', $request->id)->delete();
    }
    public function addUser(Request $request){
        //validate request
        $this->valiate($request,[
            'fullName' => 'required',
            //email should be unique in users table
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|min:6',
            'userType' => 'required',
        ]);
        
        $password = bcrypt($request->password);
        $user = User::create([
            'fullName' => $request->fullName,
            'email' => $request->email,
            'password' => $password,
            'userType' => $request->userType,
        ]);
        return  $user;
    }
    public function getUser(){
        //where user type not equal user
        return User::where('userType', '!=', 'User')->get();
    }
    public function editUser(Request $request){
        //validate request
        $this->validate($request,[
            'fullName' => 'required',
            //email should be unique in users table
            'email' => 'bail|required|email|unique:users,email,'.$request->id,
            'password' => 'min:6',
            'userType' => 'required',
        ]);

        $data = [
            'fullName' => $request->fullName,
            'email' => $request->email,
            'userType' => $request->userType,
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
            if($user->userType == 'User'){
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
}
