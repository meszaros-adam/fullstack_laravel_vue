<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

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
}