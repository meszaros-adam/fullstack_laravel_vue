<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class AdminController extends Controller
{
    public function addTag(Request $request){
        //validate
        return Tag::create([
            'tagName' => $request->tagName
        ]);
    }

    public function getTag(){
        return Tag::orderBy('id', 'desc')->get();
    }
}
