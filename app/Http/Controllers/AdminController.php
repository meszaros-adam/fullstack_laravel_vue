<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\Category;
use App\Models\User;

class AdminController extends Controller
{
    public function addTag(Request $request){
        //validate request
        $validated = $request->validate([
            'tagName' => 'required',
        ]);

        return Tag::create([
            'tagName' => $request->tagName
        ]);
    }
    public function editTag(Request $request){
        //validate request
        $validated = $request->validate([
            'tagName' => 'required',
        ]);

        return Tag::where('id', $request->id)->update([
            'tagName' => $request->tagName
        ]);
    }
    public function deleteTag(Request $request){
        //validate request
        $validated = $request->validate([
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
        $validated = $request->validate([
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
        $validated = $request->validate([
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
        $validated = $request->validate([
            'id' => 'required',
            'iconImage' => 'required',
        ]);

        $this->deleteFileFromServer($request->iconImage, true);

        return Category::where('id', $request->id)->delete();
    }
    public function addUser(Request $request){
        //validate request
        $validated = $request->validate([
            'fullName' => 'required',
            'email' => 'bail|required|email',
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
}
