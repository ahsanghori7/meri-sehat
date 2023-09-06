<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Footer;
use App\Models\Article;
use App\Models\Disease;
use App\Models\Drug;

class SitemapController extends Controller
{
    public function index(){
        // $default_pages = Page::where('status', 1)->where('reference_type', 'page')->with('language')->select(['slug','lang_id'])->get()->toArray();     
        $footers = Footer::where('status', 1)->select(['link','lang_id'])->get()->toArray();     
        $articles = Article::where('status', 1)->with('language')->select(['slug','lang_id'])->get()->toArray();     
        $diseases = Disease::where('status', 1)->whereHas('page')->with('language')->select(['slug','lang_id'])->get()->toArray();     
        $drugs = Drug::where('status', 1)->whereHas('page')->with('language')->select(['slug','lang_id'])->get()->toArray();     

        // dd($footers);


        return response()->view('sitemap.index', [
            // 'default_pages' => $default_pages,        
            'footers' => $footers,   
            'articles' => $articles,   
            'drugs' => $drugs,   
            'diseases' => $diseases,   
        ])->header('Content-Type', 'text/xml');
    }
}
