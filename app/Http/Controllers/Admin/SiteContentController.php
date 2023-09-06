<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteContent;
use Auth;
use Illuminate\Support\Facades\Gate;

class SiteContentController extends Controller
{
    //
    public function list(){
        if (Gate::denies('drug-page-view')) {
            abort(403);
        }
        $site_content = SiteContent::get();
        // return $site_content;
        return view('admin.site_content.list',['topics'=>$site_content]);
    }

    public function show($slug,$language){
        $site_content = SiteContent::where('slug','like',$slug)->where('language',$language)->first();
        // return $site_content;
        if(!$site_content){
            return redirect('/admin/site-content');
        }
        return view('admin.site_content.detail',['detail'=>$site_content]);
    }

    public function edit(Request $request,$slug,$language){
        if (Gate::denies('drug-page-update')) {
            abort(403);
        }
        $content = SiteContent::where('slug','like',$slug)->where('language',$request->language)->first();
        if(!$content){
            return redirect('/admin/site-content');
        }
        $content->title = $request->title;
        $content->heading = $request->heading;
        $content->keyword = $request->keyword;
        $content->meta_description = $request->meta_description;
        $content->content = $request->content;
        $content->status = $request->status;
        $content->edit_by = Auth::user()->id;
        $content->timestamps = true;
        $content->save();
        return back()->with('success','message');
    }

    public function new_language($slug){
        $languages = ['en','ur','sd'];
        $select_language = [];
        $content = SiteContent::where('slug','like',$slug)->get();
        if(!$content){
            return redirect('/admin/site-content');
        }
        $article_languages = array_column($content->toArray(), 'language');

        $remainingLanguages = [];

        foreach($languages as $language) {
            if (!in_array($language, $article_languages)) {
                array_push($remainingLanguages, $language);
            }
        }


        // if(!$remainingLanguages){
        //     return 'language not available';
        // }
        // return $remainingLanguages;

        // return $content;
        return view('admin.site_content.add',['languages'=>$remainingLanguages]);
    }

    public function post_new_language(Request $request,$slug){
        $content =new SiteContent;
        $content->title = $request->title;
        $content->slug = $slug;
        $content->heading = $request->heading;
        $content->keyword = $request->keyword;
        $content->meta_description = $request->meta_description;
        $content->content = $request->content;
        $content->status = $request->status;
        $content->language = $request->language;
        $content->edit_by = Auth::user()->id;
        $content->timestamps = true;
        $content->save();

        // $site_content = SiteContent::where('slug','like',$slug)->where('language',$request->language)->first();
        return redirect('admin/site-content/'.$slug.'/'.$request->language)->with('success','Content Saved..');
    }

    public function new_content(){
        return view('admin.site_content.new');
//        return back();
    }
    public function post_new_content(Request $request){
        if (Gate::denies('drug-page-add')) {
            abort(403);
        }
        $site_content = SiteContent::where('slug','like',$request->slug)->where('language',$request->language)->first();
        if($site_content){
            return back()->with('error','This slug already exist try new one...');
        }
        $content =new SiteContent;
        $content->title = $request->title;
        $content->slug = $request->slug;
        $content->heading = $request->heading;
        $content->keyword = $request->keyword;
        $content->meta_description = $request->meta_description;
        $content->content = $request->content;
        $content->status = $request->status;
        $content->language = $request->language;
        $content->edit_by = Auth::user()->id;
        $content->timestamps = true;
        $content->save();

        $site_content = SiteContent::where('slug','like',$request->slug)->where('language',$request->language)->first();
        // return $site_content;

        return redirect('admin/site-content/'.$request->slug.'/'.$request->language)->with('success','Content Saved..');
    }
}
