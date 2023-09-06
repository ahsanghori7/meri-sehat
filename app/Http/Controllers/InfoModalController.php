<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\InfoModal;
class InfoModalController extends Controller
{
    //
    public function list(){
        $info_modals = InfoModal::get();
        return view('info_modal_content.list',['info_modals'=>$info_modals]);
    }

    public function add_view(){
        return view('info_modal_content.add');
    }

    public function add_post(Request $request){
        // return $request->all();
        $chack_exist_key = InfoModal::where('key','like',$request->key)->where('language','like',$request->language)->first();
        // return $chack_exist_key;
        if(!$chack_exist_key){
            $info_modal  =new InfoModal;
            $info_modal->key= $request->key;
            $info_modal->title= $request->title;
            $info_modal->content= $request->content;
            $info_modal->language= $request->language;
            $info_modal->timestamps=false;
            $info_modal->save();
            return redirect('admin/info-modal')->with('success','Your record saved..');
              
        }else{
            return back()->with('warning','already exist with '.$chack_exist_key->language.' language, try new.');
        }
    }

    public function edit_view($id){
        $info_modal = InfoModal::where('id',$id)->first();
        return view('info_modal_content.edit',['info_modal'=>$info_modal]);
    }

    public function edit_post(Request $request,$id){
        $chack_exist_key = InfoModal::where('key','like',$request->key)->where('language','like',$request->language)->first();
        // return $chack_exist_key;
        if($chack_exist_key){
            if($chack_exist_key->id != $id){
                // return 'Already exist same key';
                return back()->with('warning','Already key exist, try new.');
            }else{
                // return 'Ready for update';
                $info_modal  = InfoModal::where('id',$id)->first();
                $info_modal->key= $request->key;
                $info_modal->title= $request->title;
                $info_modal->content= $request->content;
                $info_modal->language= $request->language;
                $info_modal->timestamps=false;
                $info_modal->save();
                return back()->with('success','Your record saved..');
            }   
        }else{
            // return 'not exist';
            $info_modal  = InfoModal::where('id',$id)->first();
            $info_modal->key= $request->key;
            $info_modal->title= $request->title;
            $info_modal->content= $request->content;
            $info_modal->language= $request->language;
            $info_modal->timestamps=false;
            $info_modal->save();
            return back()->with('success','Your record saved..');
        }
    }
}
