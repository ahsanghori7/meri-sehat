<?php

namespace App\Http\Controllers\Admin\Widget;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Article, ReferenceWidget, WidgetArticle as MainModel};
use App\Http\Common\Helper;
use Illuminate\Support\Facades\Gate;

class TopArticleController extends Controller
{
    public $folder_name = 'article'; // For view routes and file calling and saving
    public $module_name = 'Article'; // For toast And page header
    public $module_slug; // check module slug
    public $input_elements;
    private $types = [
        [
            "value" => "featured",
            "name" => "Featured",
            'count' => 4
        ],
        [
            "value" => "single_column",
            "name" => "Single Column",
            'count' => 3
        ],
        [
            "value" => "double_column",
            "name" => "Double Column",
            'count' => 6
        ],
        [
            "value" => "single_article",
            "name" => "Single Article",
            'count' => 1
        ],
    ];

    public function __construct(array $attributes = array())
    {
        $this->module_slug = Helper::module_chk();
    }

    public function index(Request $request,$reference_id){
        if($request->isMethod('post')){
            return $this->form($request,$reference_id);
        }else{
            $getMainModel = MainModel::where(['reference_id' => $reference_id])->orderBy('sequence', 'asc')->get();
            $getReferenceWidget = ReferenceWidget::find($reference_id);
            $selectedLanguage = $getReferenceWidget->page ? $getReferenceWidget->page->lang_id : $getReferenceWidget->article->lang_id;
            $data['reference_id'] = $reference_id;
            $this->module_slug = Helper::module_chk($reference_id);
            $data['module_name'] = "Edit ".$this->module_name;
            $data['folder_name'] = $this->folder_name;
            $data['result'] = MainModel::where(['reference_id' => $reference_id])->first() ?? null;
            $data['reference'] = $getReferenceWidget;
            $data['articles'] = Article::where(['status' => true, 'lang_id' => $selectedLanguage])->get();
            $data['selected_language'] = $selectedLanguage;
            $data['selected_articles'] = $getMainModel;
            $data['selected_parents'] = $getMainModel ? $getMainModel->pluck('article_id')->toArray() : [];
            $data['types'] = $this->types;
            $data['module_slug'] = $this->module_slug;
            $data['include_status_radio'] = 0;
            $data['include_is_mobile_show_radio'] = 0;
            $data['include_is_web_show_radio'] = 0;
            if (Gate::check($this->module_slug.'-widgets-article-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check($this->module_slug.'-widgets-article-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check($this->module_slug.'-widgets-article-web_show')) {
                $data['include_is_web_show_radio'] = 1;
            }
            $file = "admin.widget.".$this->folder_name.".form";
            return view($file, $data);
        }
    }

    public function form(Request $request,$reference_id){
//        dd($request->all());
        $this->updateGeneralWidgetData($request, $reference_id);
        if(isset($request->article)){
            foreach($request->article as $key => $article_id){
                $data = null;
                if ($reference_id) {
                    $data['reference_id'] = $reference_id;
                }
                if ($request->has('type')) {
                    $data['type'] = $request->type;
                }
                if ($request->has('status')) {
                    $data['status'] = $request->status ?? 1;
                }
                if ($request->has('is_mobile_show')) {
                    $data['is_mobile_show'] = $request->is_mobile_show ?? 1;
                }
                if ($request->has('is_web_show')) {
                    $data['is_web_show'] = $request->is_web_show ?? 1;
                }
                $data['sequence'] = $key + 1;
                if ($article_id) {
                    $data['article_id'] = $article_id;
                }
                if ($data) {
                    MainModel::create($data);
                }
            }
            $data = null;
            if ($request->has('type')) {
                $data['type'] = $request->type;
            }
            if ($request->has('status')) {
                $data['status'] = $request->status ?? 1;
            }
            if ($request->has('is_mobile_show')) {
                $data['is_mobile_show'] = $request->is_mobile_show ?? 1;
            }
            if ($request->has('is_web_show')) {
                $data['is_web_show'] = $request->is_web_show ?? 1;
            }
            if ($data) {
                MainModel::where(['reference_id' => $reference_id])->update($data);
            }
        }else{
            $data = null;
            if ($request->has('type')) {
                $data['type'] = $request->type;
            }
            if ($request->has('status')) {
                $data['status'] = $request->status ?? 1;
            }
            if ($request->has('is_mobile_show')) {
                $data['is_mobile_show'] = $request->is_mobile_show ?? 1;
            }
            if ($request->has('is_web_show')) {
                $data['is_web_show'] = $request->is_web_show ?? 1;
            }
            if ($data) {
                MainModel::where(['reference_id' => $reference_id])->update($data);
            }
        }
        Helper::toast('success',$this->module_name.' created.');
        return redirect()->route('widget-'.$this->folder_name.'-index', ['reference_id' => $reference_id]);
    }

    public function editSequence(Request $request){
        foreach($request->sequence as $key => $id){
            MainModel::where(['id' => $id])->update(['sequence' => $key + 1]);
        }
        return back();
    }

    public function deleteRecord($id){
        if (Gate::denies($this->module_slug.'-widgets-article-delete')) {
            abort(403);
        }
        MainModel::where(['id' => $id])->delete();
        return back();
    }

}
