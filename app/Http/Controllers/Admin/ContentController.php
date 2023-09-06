<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\topic;
use App\Models\topic_categories;
class ContentController extends Controller
{
    //
    public function topic(){
        $topic = topic_categories::get();
        // return $topic;
        return view('content.topics',['topics'=>$topic]);
    }

    public function content($id){
        $topic = topic_categories::where('id',$id)->with('content')->with('topic')->get();
        // return $topic;
        return view('content.content',['topics'=>$topic]);
    }
    public function content_update(Request $request){
        return $request->all();
    }
}
