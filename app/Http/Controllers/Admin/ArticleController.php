<?php

namespace App\Http\Controllers\Admin;

use App\Http\Common\Constant;
use App\Http\Common\FcmHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{ApiToken,
    Article as MainModel,
    ArticleFact,
    ArticleLabel,
    Speciality,
    Tags,
    ReferenceWidget,
    Disease,
    Language,
    Widget,
    SubTopic,
    Topics,
    ArticleArchive};
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;
use App\Http\Common\Helper;
use App\Http\Common\SmsHelper;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ArticleController extends Controller
{
    public function __construct(array $attributes = array())
    {
        $this->english_records_name_element = 'name';
        $this->english_records = MainModel::where(['lang_id' => Language::ENGLISH])->orderBy('name', 'asc')->get();
    }

    public $folder_name = 'article'; // For view routes and file calling and saving
    public $module_name = 'Articles'; // For toast And page header
    public $input_elements;
    private $types = [
        [
            "value" => "sub-topic",
            "name" => "Article Detail"
        ],
        [
            "value" => "cat-topic",
            "name" => "Category Topic"
        ],
        // [
        //     "value" => "disease",
        //     "name" => "Disease Detail"
        // ],
        // [
        //     "value" => "drug",
        //     "name" => "Drug Detail"
        // ],
    ];

    public function view(Request $request){
        if (Gate::denies('article-management-view')) {
            abort(403);
        }
        if($request->isMethod('post')){
            foreach($request->sequence as $key => $id){
                $sequence = $key + 1;
                MainModel::find($id)->update(['sequence' => str_pad($sequence,2,"0",STR_PAD_LEFT)]);
            }
            return back();
        }else{
            $data['folder_name'] = $this->folder_name;
            $data['module_name'] = $this->module_name;
            $data['languages'] = Language::where('status', 1)->get();
            $data['categories'] = Topics::where('status', true)->get();
            $file = "admin.".$this->folder_name.".view";
            return view($file,$data);
        }
    }

    public function wellnessView(Request $request){
        if (Gate::denies('article-management-view')) {
            abort(403);
        }
        if($request->isMethod('post')){
            foreach($request->sequence as $key => $id){
                $sequence = $key + 1;
                MainModel::find($id)->update(['sequence' => str_pad($sequence,2,"0",STR_PAD_LEFT)]);
            }
            return back();
        }else{
            $topic_arr = array();
            $data['folder_name'] = $this->folder_name;
            $data['module_name'] = $this->module_name;
            $data['languages'] = Language::where('status', 1)->get();
            $topic_slug = Topics::with('children')->where('id', 1)->where('status', true)->get();
            foreach ($topic_slug as $topic) {
                if (isset($topic->children)) {
                    $topic_slug = $topic_slug->merge($topic->children);
                }
            }
            $data['categories'] = $topic_slug;
//            $data['categories'] = Topics::where('status', true)->get();
            $file = "admin.".$this->folder_name.".wellness-view";
            return view($file,$data);
        }
    }

    public function diseaseView(Request $request){
        if (Gate::denies('article-management-view')) {
            abort(403);
        }
        if($request->isMethod('post')){
            foreach($request->sequence as $key => $id){
                $sequence = $key + 1;
                MainModel::find($id)->update(['sequence' => str_pad($sequence,2,"0",STR_PAD_LEFT)]);
            }
            return back();
        }else{
            $topic_arr = array();
            $data['folder_name'] = $this->folder_name;
            $data['module_name'] = $this->module_name;
            $data['languages'] = Language::where('status', 1)->get();
            $topic_slug = Topics::with('children')->where('id', 2)->where('status', true)->get();
            foreach ($topic_slug as $topic) {
                if (isset($topic->children)) {
                    $topic_slug = $topic_slug->merge($topic->children);
                }
            }
            $data['categories'] = $topic_slug;
//            $data['categories'] = Topics::where('status', true)->get();
            $file = "admin.".$this->folder_name.".disease-view";
            return view($file,$data);
        }
    }

    public function gotoLive(Request $request, $id){
        if (Gate::denies('article-management-general-info-goto_live')) {
            abort(403);
        }
        $id = decrypt($id);
        $constant = new Constant();
        $getArticle = MainModel::find($id);
        $getArticle->draft = 0;
        $getArticle->status = 1;
        $article_save = $getArticle->save();
        if (isset($article_save)) {
            Helper::toast('success',$getArticle->name.' successfully live.');
        } else {
            Helper::toast('failed',$this->module_name.' not going to live.');
        }
        if (Str::contains(request()->headers->get('referer'), 'disease')) {
            return redirect()->route($this->folder_name . '-disease-view');
        } elseif (Str::contains(request()->headers->get('referer'), 'wellness')) {
            return redirect()->route($this->folder_name . '-wellness-view');
        } else {
            return redirect()->route($this->folder_name . '-view');
        }
    }

    public function markDraft(Request $request, $id){
//        dd(request()->headers->get('referer'));
        if (Gate::denies('article-management-draft')) {
            abort(403);
        }
        $id = decrypt($id);
        $getArticle = MainModel::find($id);
        $getArticle->draft = 1;
        $getArticle->status = 0;
        $article_save = $getArticle->save();
        if (isset($article_save)) {
            Helper::toast('success',$getArticle->name.' successfully draft.');
        } else {
            Helper::toast('failed',$this->module_name.' not going to draft.');
        }
        if (Str::contains(request()->headers->get('referer'), 'disease')) {
            return redirect()->route($this->folder_name . '-disease-draft');
        } elseif (Str::contains(request()->headers->get('referer'), 'wellness')) {
            return redirect()->route($this->folder_name . '-wellness-draft');
        } else {
            return redirect()->route($this->folder_name . '-draft');
        }
    }

    public function archiveArticle(Request $request,$id){
        if (Gate::denies('article-management-archive')) {
            abort(403);
        }
        $id = decrypt($id);
        $constant = new Constant();
        $ArticleArchive= new ArticleArchive;
        $getArticle = MainModel::where('id',$id)->with('articleWidgets', 'articleLabel')->first();
        if($getArticle){
            $ArticleArchive->approved_by=$getArticle->approved_by;
            $ArticleArchive->written_by=$getArticle->written_by;
            $ArticleArchive->parent_id=$getArticle->parent_id;
            $ArticleArchive->lang_id=$getArticle->lang_id;
            $ArticleArchive->translation_od=$getArticle->translation_od;
            $ArticleArchive->type=$getArticle->type;
            $ArticleArchive->name=$getArticle->name;
            $ArticleArchive->meta_description=$getArticle->meta_description;
            $ArticleArchive->meta_name=$getArticle->meta_name;
            $ArticleArchive->slug=$getArticle->slug;
            $ArticleArchive->description=$getArticle->description;
            $ArticleArchive->is_description_show=$getArticle->is_description_show;
            $ArticleArchive->keyword=$getArticle->keyword;
            $ArticleArchive->image=$getArticle->image;
            $ArticleArchive->status=$getArticle->status;
            $ArticleArchive->class_name=$getArticle->class_name;
            $ArticleArchive->hide_image_in_detail=$getArticle->hide_image_in_detail;
            $ArticleArchive->is_featured=$getArticle->is_featured;
            $ArticleArchive->visit_counts=$getArticle->visit_counts;
            $ArticleArchive->home_image=$getArticle->home_image;
            $ArticleArchive->hide_home_image_in_detail=$getArticle->hide_home_image_in_detail;
            $ArticleArchive->is_featured2=$getArticle->is_featured2;
            $ArticleArchive->is_app_show=$getArticle->is_app_show;
            $ArticleArchive->is_web_show=$getArticle->is_web_show;
            $ArticleArchive->speciality_id=$getArticle->speciality_id;
            $ArticleArchive->alt=$getArticle->alt;
            $ArticleArchive->canonical_link=$getArticle->canonical_link;
            $ArticleArchive->seo_image=$getArticle->seo_image;
            $ArticleArchive->hidden_description=$getArticle->hidden_description;
            $ArticleArchive->draft=$getArticle->draft;
            $ArticleArchive->save();
            $getArticle->forceDelete();

            return redirect()->back();
        }
    }

    public function draft(Request $request){
        if (Gate::denies('article-management-draft')) {
            abort(403);
        }
        if($request->isMethod('post')){
            foreach($request->sequence as $key => $id){
                $sequence = $key + 1;
                MainModel::find($id)->where('draft', 1)->update(['sequence' => str_pad($sequence,2,"0",STR_PAD_LEFT)]);
            }
            return back();
        }else{
            $data['folder_name'] = $this->folder_name;
            $data['module_name'] = $this->module_name;
            $data['languages'] = Language::where('status', 1)->get();
            $data['categories'] = Topics::where('status', true)->get();
            if (Str::contains(request()->headers->get('referer'), 'disease')) {
                $file = "admin.".$this->folder_name.".disease-draft-view";
            } elseif (Str::contains(request()->headers->get('referer'), 'wellness')) {
                $file = "admin.".$this->folder_name.".wellness-draft-view";
            } else {
                $file = "admin.".$this->folder_name.".draft-view";
            }

            return view($file,$data);
        }
    }

    public function draftWellness(Request $request){
        if (Gate::denies('article-management-draft')) {
            abort(403);
        }
        if($request->isMethod('post')){
            foreach($request->sequence as $key => $id){
                $sequence = $key + 1;
                MainModel::find($id)->where('draft', 1)->update(['sequence' => str_pad($sequence,2,"0",STR_PAD_LEFT)]);
            }
            return back();
        }else{
            $data['folder_name'] = $this->folder_name;
            $data['module_name'] = $this->module_name;
            $data['languages'] = Language::where('status', 1)->get();
            $topic_slug = Topics::with('children')->where('id', 1)->where('status', true)->get();
            foreach ($topic_slug as $topic) {
                if (isset($topic->children)) {
                    $topic_slug = $topic_slug->merge($topic->children);
                }
            }
            $data['categories'] = $topic_slug;
            $file = "admin.".$this->folder_name.".wellness-draft-view";
            return view($file,$data);
        }
    }

    public function draftDisease(Request $request){
        if (Gate::denies('article-management-draft')) {
            abort(403);
        }
        if($request->isMethod('post')){
            foreach($request->sequence as $key => $id){
                $sequence = $key + 1;
                MainModel::find($id)->where('draft', 1)->update(['sequence' => str_pad($sequence,2,"0",STR_PAD_LEFT)]);
            }
            return back();
        }else{
            $data['folder_name'] = $this->folder_name;
            $data['module_name'] = $this->module_name;
            $data['languages'] = Language::where('status', 1)->get();
            $topic_slug = Topics::with('children')->where('id', 2)->where('status', true)->get();
            foreach ($topic_slug as $topic) {
                if (isset($topic->children)) {
                    $topic_slug = $topic_slug->merge($topic->children);
                }
            }
            $data['categories'] = $topic_slug;
            $file = "admin.".$this->folder_name.".disease-draft-view";
            return view($file,$data);
        }
    }

    public function draftFetch(Request $request){
        $query = MainModel::with(['parent','language']);
        $query = $query->where('draft', '1');
        if(isset($request->status)) {
            $query = $query->where('status', $request->status);
        }
        if(isset($request->category)) {
            $query = $query->where('parent_id', $request->category);
        }
        if(isset($request->lang_id)) {
            $query = $query->where('lang_id', $request->lang_id);
        }
//        dd($query->get());
        return Datatables::of($query)->make(true);
    }

    public function wellnessdraftFetch(Request $request){
        $topic_arr = array();
        // Targeted Wellness ID
        $topic_slug = Topics::with('children')->where('id', 1)->where('status', true)->get();
        foreach ($topic_slug as $topic) {
            array_push($topic_arr, $topic->id);
            foreach ($topic->children as $first_children) {
                array_push($topic_arr, $first_children->id);
                foreach ($first_children->children as $second_children) {
                    array_push($topic_arr, $second_children->id);
                    foreach ($second_children->children as $third_children) {
                        array_push($topic_arr, $third_children->id);
                        foreach ($third_children->children as $fourth_children) {
                            array_push($topic_arr, $fourth_children->id);
                        }
                    }
                }
            }
        }
        $query = MainModel::with(['parent','language'])->where('draft', 1);
        if ($topic_slug) {
            $query = $query->whereIn('parent_id', $topic_arr);
        }
        if(isset($request->status)) {
            $query = $query->where('status', $request->status);
        } else {
            $query = $query->whereIn('status', ['0','1']);
        }
        if(isset($request->category)) {
            $query = $query->where('parent_id', $request->category);
        }
        if(isset($request->lang_id)) {
            $query = $query->where('lang_id', $request->lang_id);
        }
        return Datatables::of($query)->make(true);
    }

    public function wellnessFetch(Request $request){
        $topic_arr = array();
        // Targeted Wellness ID
        $topic_slug = Topics::with('children')->where('id', 1)->where('status', true)->get();
        foreach ($topic_slug as $topic) {
            array_push($topic_arr, $topic->id);
            foreach ($topic->children as $first_children) {
                array_push($topic_arr, $first_children->id);
                foreach ($first_children->children as $second_children) {
                    array_push($topic_arr, $second_children->id);
                    foreach ($second_children->children as $third_children) {
                        array_push($topic_arr, $third_children->id);
                        foreach ($third_children->children as $fourth_children) {
                            array_push($topic_arr, $fourth_children->id);
                        }
                    }
                }
            }
        }
        $query = MainModel::with(['parent','language'])->where('draft', 0);
        if ($topic_slug) {
            $query = $query->whereIn('parent_id', $topic_arr);
        }
        if(isset($request->status)) {
            $query = $query->where('status', $request->status);
        } else {
            $query = $query->whereIn('status', ['0','1']);
        }
        if(isset($request->category)) {
            $query = $query->where('parent_id', $request->category);
        }
        if(isset($request->lang_id)) {
            $query = $query->where('lang_id', $request->lang_id);
        }
        return Datatables::of($query)->make(true);
    }

    public function diseasedraftFetch(Request $request){
        $topic_arr = array();
        // Targeted Disease ID
        $topic_slug = Topics::with('children')->where('id', 2)->where('status', true)->get();
        foreach ($topic_slug as $topic) {
            array_push($topic_arr, $topic->id);
            foreach ($topic->children as $first_children) {
                array_push($topic_arr, $first_children->id);
                foreach ($first_children->children as $second_children) {
                    array_push($topic_arr, $second_children->id);
                    foreach ($second_children->children as $third_children) {
                        array_push($topic_arr, $third_children->id);
                        foreach ($third_children->children as $fourth_children) {
                            array_push($topic_arr, $fourth_children->id);
                        }
                    }
                }
            }
        }
        $query = MainModel::with(['parent','language'])->where('draft', 1);
        if ($topic_slug) {
            $query = $query->whereIn('parent_id', $topic_arr);
        }
        if(isset($request->status)) {
            $query = $query->where('status', $request->status);
        } else {
            $query = $query->whereIn('status', ['0','1']);
        }
        if(isset($request->category)) {
            $query = $query->where('parent_id', $request->category);
        }
        if(isset($request->lang_id)) {
            $query = $query->where('lang_id', $request->lang_id);
        }
        return Datatables::of($query)->make(true);
    }

    public function diseaseFetch(Request $request){
        $topic_arr = array();
        // Targeted Disease ID
        $topic_slug = Topics::with('children')->where('id', 2)->where('status', true)->get();
        foreach ($topic_slug as $topic) {
            array_push($topic_arr, $topic->id);
            foreach ($topic->children as $first_children) {
                array_push($topic_arr, $first_children->id);
                foreach ($first_children->children as $second_children) {
                    array_push($topic_arr, $second_children->id);
                    foreach ($second_children->children as $third_children) {
                        array_push($topic_arr, $third_children->id);
                        foreach ($third_children->children as $fourth_children) {
                            array_push($topic_arr, $fourth_children->id);
                        }
                    }
                }
            }
        }
        $query = MainModel::with(['parent','language'])->where('draft', 0);
        if ($topic_slug) {
            $query = $query->whereIn('parent_id', $topic_arr);
        }
        if(isset($request->status)) {
            $query = $query->where('status', $request->status);
        } else {
            $query = $query->whereIn('status', ['0','1']);
        }
        if(isset($request->category)) {
            $query = $query->where('parent_id', $request->category);
        }
        if(isset($request->lang_id)) {
            $query = $query->where('lang_id', $request->lang_id);
        }
        return Datatables::of($query)->make(true);
    }

    public function fetch(Request $request){
        $query = MainModel::with(['parent','language']);
        if(isset($request->status)) {
            $query = $query->where('status', $request->status);
        } else {
            $query = $query->whereIn('status', ['0','1']);
        }
        if(isset($request->category)) {
            $query = $query->where('parent_id', $request->category);
        }
        if(isset($request->lang_id)) {
            $query = $query->where('lang_id', $request->lang_id);
        }
        return Datatables::of($query)->make(true);
    }

    public function form(Request $request, $id = null){
        $chk_category = Topics::where('id', $request->category)->first();
        if ($chk_category && $chk_category->title == 'Wellness') {
            $approved_by = isset($request->approved_by_wellness) ? $request->approved_by_wellness : null;
        } else {
            $approved_by = isset($request->approved_by_doctor) ? $request->approved_by_doctor : null;
        }
//        $request->validate(MainModel::getValidationRules($id));
        if ($request->has('lang_id')) {
            $data['lang_id'] = $request->lang_id;
        }
        if ($request->has('written_by')) {
            $data['written_by'] = $request->written_by ?? 1; // id = 1 reperesents the super admin user
        }
        if ($request->has('name')) {
            $data['name'] = $request->name;
        }
        if ($request->has('descripton')) {
            $data['descripton'] = $request->descripton;
        }
        if ($request->has('keywords')) {
            $data['keywords'] = $request->keywords;
        }
        if ($request->has('type')) {
            $data['type'] = $request->type;
        }
        if ($request->has('parent_id')) {
            $data['parent_id'] = $request->parent_id ?? ($request->category ?? 0);
        }
        if ($approved_by) {
            $data['approved_by'] = $approved_by;
        }
        if ($request->has('status')) {
            $data['status'] = $request->status ?? false;
        }
        if ($request->has('is_description_show')) {
            $data['is_description_show'] = $request->is_description_show ?? false;
        }
        if ($request->has('hide_image_in_detail')) {
            $data['hide_image_in_detail'] = $request->hide_image_in_detail ?? 0;
        }
        if ($request->has('class_name')) {
            $data['class_name'] = $request->class_name ?? '';
        }
        if ($request->has('meta_name')) {
            $data['meta_name'] = $request->meta_name ?? '';
        }
        if ($request->has('meta_description')) {
            $data['meta_description'] = $request->meta_description ?? '';
        }
        if ($request->has('alt')) {
            $data['alt'] = $request->alt ?? '';
        }
        if ($request->has('speciality_id') && $request->speciality_id) {
            $data['speciality_id'] = $request->speciality_id;
        }
        if ($request->has('canonical_title')) {
            $data['canonical_title'] = $request->canonical_title ?? '';
        }
        if ($request->has('canonical_description')) {
            $data['canonical_description'] = $request->canonical_description ?? '';
        }
        if ($request->has('canonical_link')) {
            $data['canonical_link'] = $request->canonical_link ?? '';
        }
        if ($request->has('seo_image')) {
            $data['seo_image'] = $request->seo_image ?? '';
        }
        if ($request->has('hidden_description')) {
            $data['hidden_description'] = $request->hidden_description ?? '';
        }
        if($request->translation_of != 'parent'){
            $data['translation_of'] = $request->translation_of;
        }
        if ($request->has('goto_live')) {
            $data['draft'] = 0;
            $data['status'] = true;
        } else {
            if ($request->segment(3) == 'draft') {
                $data['draft'] = 1;
            }
        }

        if(!$request->id){
            if ($request->slug != '') {
                $chk_existing = MainModel::where('slug', $request->slug)
                    ->where('lang_id', $request->lang_id)
                    ->where('status', 1)
                    ->first();
                if ($chk_existing) {
                    Helper::toast('error',$this->module_name.' slug duplicate.');
                    return back();
                }
                $data['slug'] = $request->slug;
            } else {
                $getAllArticle = MainModel::get();
                $data['slug'] = count($getAllArticle) ? $this->uniqueSlug(\Str::slug($request->name), $getAllArticle, $id) : \Str::slug($request->name);
            }
        }
        if($request->hasFile('image')){
            $folder="article";
            $key="image";
            $data['image'] =$this->S3UploaderSpecificKey($key,$request,$folder);
        }

        if($id){
            if ($request->slug != '') {
                $chk_existing = MainModel::where('slug', $request->slug)
                    ->where('id', '<>', $id)
                    ->where('lang_id', $request->lang_id)
                    ->where('status', 1)
                    ->first();
                if ($chk_existing) {
                    Helper::toast('error',$this->module_name.' slug duplicate.');
                    return back();
                }
                $data['slug'] = $request->slug;
            }
            if(MainModel::find($id)->update($data)){
                if($request->tags) {
                    $this->insertTags($id, $request->tags);
                }
                if($request->label){
                    ArticleLabel::updateOrCreate(
                    [
                        'article_id' => $id
                    ],[
                        'article_fact_id' => $request->label,
                    ]);
                }
                if($request->widgets) {
                    $this->insertWidgets($id, $request->widgets);
                }
                Helper::toast('success',$this->module_name.' Updated.');
            }
        }else{
            if($created_data = MainModel::create($data)){
            if($request->tags) {
                $this->insertTags($created_data->id, $request->tags);
            }
            if($request->label){
                ArticleLabel::updateOrCreate(
                [
                    'article_id' => $created_data->id
                ],[
                    'article_fact_id' => $request->label,
                ]);
            }
            // Sned Push Notifications to active Users upon created new Article
            $getUsers = User::where(['status' => true, 'is_blocked' => false, 'role_id' => 2])->where('id', 121)->get();
            foreach($getUsers as $getUser)
            {
                $this->sendPushNotificationsById(
                    $getUser->id,
                    "New Article Published",
                    "Article $request->name has been published.",
                    [ 'module' => 'dashboard', 'id' => 1, ]
                );
            }
            if($request->widgets) {
                $this->insertWidgets($created_data->id, $request->widgets);
            }
                Helper::toast('success',$this->module_name.' created.');
            }
            if (str_contains($request->segment(3), 'draft')) {
                if (str_contains($request->segment(4), 'wellness')) {
                    return redirect()->route($this->folder_name . '-draft-wellness-edit', ['id' => encrypt($created_data->id)]);
                } elseif (str_contains($request->segment(4), 'disease')) {
                    return redirect()->route($this->folder_name . '-draft-disease-edit', ['id' => encrypt($created_data->id)]);
                } else {
                    return redirect()->route($this->folder_name . '-draft-edit', ['id' => encrypt($created_data->id)]);
                }
            }
            return redirect()->route($this->folder_name.'-edit',['id' => encrypt($created_data->id)]);
        }
        return back();
    }

    public function add(Request $request){
        if (Gate::denies('article-management-add')) {
            abort(403);
        }
        if($request->isMethod('post')){
            return $this->form($request);
        }else{
            $constant = new Constant();
            $data['english_records'] = $this->english_records;
            $data['english_records_name_element'] = $this->english_records_name_element;
            $data['page_header'] = "Add ".$this->module_name;
            $data['result'] = null;
            $data['wellness_profiles'] = User::where(['status' => true, 'is_blocked' => false, 'role_id' => $constant->FITNESS_EXPERTS_ROLE_ID])->get();
            $data['doctors'] = User::where(['status' => true, 'is_blocked' => false, 'role_id' => $constant->DOCTOR_ROLE_ID])->get();
            $data['authors'] = User::where(['status' => true, 'is_blocked' => false, 'role_id' => $constant->AUTHOR_ROLE_ID])->get();
            $data['categories'] = Topics::where(['status' => true, 'draft' => false, 'parent_id' => 0])->get();
            $data['sub_categories'] = [];
            $data['types'] = $this->types;
            $data['labels'] = ArticleFact::where('status', true)->get();
            $data['tags'] = Tags::all();
            $data['selected_tags'] = [];
            $data['parents'] = $this->getParents(null, 'sub-topic');
            $data['widgets'] = Widget::where('status',1)->whereIn('type', [Constant::WIDGET_TYPE_ARTICLE, Constant::WIDGET_TYPE_BOTH])->get();
            $data['specialities'] = Speciality::where('status',1)->where('type', 'doctor')->get();
            $input_elements = $this->input_elements;
            $data['include_status_radio'] = 1;
            $path = '';
            if ($data['result']) {
                if ($data['result']->lang_id == 1) {
                    if (Str::contains(URL::current(), 'staging')) {
                        $path = env('APP_REACT_DEV') ? env('APP_REACT_WEB_URL') : env('APP_URL') . 'article/' . $data['result']->slug;
                    } else {
                        $path = env('APP_REACT_WEB_URL') . 'article/' . $data['result']->slug;
                    }
                } else {
                    if (Str::contains(URL::current(), 'staging')) {
                        $path = env('APP_REACT_DEV') ? env('APP_REACT_WEB_URL') : env('APP_URL') . $data['language_slug']->slug . '/article/' . $data['result']->slug;
                    } else {
                        $path = env('APP_REACT_WEB_URL') . $data['language_slug']->slug . '/article/' . $data['result']->slug;
                    }
                }
            }
            $data['path'] = $path;
            $file = "admin.".$this->folder_name.".form";
            return view($file,$data);
        }
    }

    public function addWellness(Request $request){
        if (Gate::denies('article-management-add') && Gate::denies('article-management-wellness')) {
            abort(403);
        }
        if($request->isMethod('post')){
            return $this->form($request);
        }else{
            $constant = new Constant();
            $data['english_records'] = $this->english_records;
            $data['english_records_name_element'] = $this->english_records_name_element;
            $data['page_header'] = "Add Wellness ".$this->module_name;
            $data['result'] = null;
            $data['wellness_profiles'] = User::where(['status' => true, 'is_blocked' => false, 'role_id' => $constant->FITNESS_EXPERTS_ROLE_ID])->get();
            $data['doctors'] = User::where(['status' => true, 'is_blocked' => false, 'role_id' => $constant->DOCTOR_ROLE_ID])->get();
            $data['authors'] = User::where(['status' => true, 'is_blocked' => false, 'role_id' => $constant->AUTHOR_ROLE_ID])->get();
            $data['categories'] = Topics::where(['status' => true, 'draft' => false, 'slug' => 'wellness'])->get();
            $data['sub_categories'] = [];
            $data['types'] = $this->types;
            $data['labels'] = ArticleFact::where('status', true)->get();
            $data['tags'] = Tags::all();
            $data['selected_tags'] = [];
            $data['parents'] = $this->getParents(null, 'sub-topic');
            $data['widgets'] = Widget::where('status',1)->whereIn('type', [Constant::WIDGET_TYPE_ARTICLE, Constant::WIDGET_TYPE_BOTH])->get();
            $data['specialities'] = Speciality::where('status',1)->where('type', 'doctor')->get();
            $input_elements = $this->input_elements;
            $data['include_status_radio'] = 1;

            $path = '';
            if ($data['result']) {
                if ($data['result']->lang_id == 1) {
                    if (Str::contains(URL::current(), 'staging')) {
                        $path = env('APP_REACT_DEV') ? env('APP_REACT_WEB_URL') : env('APP_URL') . 'article/' . $data['result']->slug;
                    } else {
                        $path = env('APP_REACT_WEB_URL') . 'article/' . $data['result']->slug;
                    }
                } else {
                    if (Str::contains(URL::current(), 'staging')) {
                        $path = env('APP_REACT_DEV') ? env('APP_REACT_WEB_URL') : env('APP_URL') . $data['language_slug']->slug . '/article/' . $data['result']->slug;
                    } else {
                        $path = env('APP_REACT_WEB_URL') . $data['language_slug']->slug . '/article/' . $data['result']->slug;
                    }
                }
            }
            $data['path'] = $path;
            $file = "admin.".$this->folder_name.".wellness-form";
            return view($file,$data);
        }
    }

    public function editWellness(Request $request, $id){
        if (Gate::denies('article-management-update') && Gate::denies('article-management-wellness')) {
            abort(403);
        }
        $id = decrypt($id);
        $constant = new Constant();
        $getArticle = MainModel::where('id',$id)->with('articleWidgets', 'articleLabel')->first();
        if (Auth::user()->role_id != $constant->SUPER_ADMIN_ROLE_ID
            &&  Auth::user()->role_id != $constant->ADMIN_ROLE_ID
            &&  Auth::user()->role_id != $constant->CONTENT_LEAD_ROLE_ID
            &&  Auth::user()->role_id != $constant->BRANDING_LEAD_ROLE_ID
            &&  Auth::user()->role_id != $constant->SEO_LEAD_ROLE_ID
            &&  Auth::user()->role_id != $constant->EDITOR_ROLE_ID
            &&  Auth::user()->role_id != $constant->UPLOADERS_ROLE_ID
            &&  Auth::user()->role_id != $constant->SEO_ROLE_ID
            &&  Auth::user()->role_id != $constant->CONTENT_TEAM_ROLE_ID
            &&  $getArticle->status == true
        ){
            Helper::toast('error', 'You are not allowed to edit this Article');
            if (str_contains($request->segment(3), 'draft')) {
                return redirect()->route($this->folder_name . '-wellness-draft');
            } else {
                return redirect()->route($this->folder_name . '-wellness-view');
            }
        }
        if($request->isMethod('post')){
            return $this->form($request,$id);
        }else{
            $language = Language::find($getArticle->lang_id);
            $data['english_records'] = $this->english_records;
            $data['english_records_name_element'] = $this->english_records_name_element;
            $data['page_header'] = "Edit Wellness ".$this->module_name;
            $data['result'] = $getArticle;
            $data['language_slug'] = $language->slug;
            $this->setLangSession($data['result']->lang_id);
            $data['wellness_profiles'] = User::where(['status' => true, 'is_blocked' => false, 'role_id' => $constant->FITNESS_EXPERTS_ROLE_ID])->get();
            $data['doctors'] = User::where(['status' => true, 'is_blocked' => false, 'role_id' => $constant->DOCTOR_ROLE_ID])->get();
            $data['authors'] = User::where(['status' => true, 'is_blocked' => false, 'role_id' => $constant->AUTHOR_ROLE_ID])->get();
            $data['categories'] = Topics::where(['status' => true, 'draft' => false, 'slug' => 'wellness'])->get();
            if($data['result']->parent && $data['result']->parent->parent_id != 0){
                $data['sub_categories'] = Topics::where(['status' => true, 'draft' => false, 'parent_id' =>  $data['result']->parent->parent_id])->get();
            }else{
                $data['sub_categories'] = [];
            }
            $data['types'] = $this->types;
            $data['labels'] = ArticleFact::where('status', true) ->get();
            // $data['labels'] = ArticleFact::where('status', true)->where('lang_id', $language->id)->get();
            $data['tags'] = Tags::all();
            $data['selected_tags'] = $this->getSelectedTags($data['result']);
            $data['parents'] = $this->getParents(null, 'sub-topic');
            $data['widgets'] = Widget::where('status',1)->whereIn('type', [Constant::WIDGET_TYPE_ARTICLE, Constant::WIDGET_TYPE_BOTH])->get();
            $data['specialities'] = Speciality::where('status',1)->where('type', 'doctor')->get();
            $data['include_status_radio'] = 1;

            $path = '';
            if ($data['result']) {
                if ($data['result']->lang_id == 1) {
                    if (Str::contains(URL::current(), 'staging')) {
                        $path = env('APP_REACT_DEV') ? env('APP_REACT_WEB_URL') : env('APP_URL') . 'article/' . $data['result']->slug;
                    } else {
                        $path = env('APP_REACT_WEB_URL') . 'article/' . $data['result']->slug;
                    }
                } else {
                    if (Str::contains(URL::current(), 'staging')) {
                        $path = env('APP_REACT_DEV') ? env('APP_REACT_WEB_URL') : (env('APP_URL') . isset($data['language_slug']->slug) ? $data['language_slug']->slug : $data['language_slug'] . '/article/' . $data['result']->slug);
                    } else {
                        $slug = isset($data['language_slug']->slug) ? $data['language_slug']->slug : $data['language_slug'];
                        $path = env('APP_REACT_WEB_URL') . $slug . '/article/' . $data['result']->slug;
                    }
                }
            }
            $data['path'] = $path;

            $file = "admin.".$this->folder_name.".wellness-form";
            return view($file,$data);
        }
    }

    public function addDisease(Request $request){
        if (Gate::denies('article-management-add') && Gate::denies('article-management-disease')) {
            abort(403);
        }
        if($request->isMethod('post')){
            return $this->form($request);
        }else{
            $constant = new Constant();
            $data['english_records'] = $this->english_records;
            $data['english_records_name_element'] = $this->english_records_name_element;
            $data['page_header'] = "Add Disease ".$this->module_name;
            $data['result'] = null;
            $data['wellness_profiles'] = User::where(['status' => true, 'is_blocked' => false, 'role_id' => $constant->FITNESS_EXPERTS_ROLE_ID])->get();
            $data['doctors'] = User::where(['status' => true, 'is_blocked' => false, 'role_id' => $constant->DOCTOR_ROLE_ID])->get();
            $data['authors'] = User::where(['status' => true, 'is_blocked' => false, 'role_id' => $constant->AUTHOR_ROLE_ID])->get();
            $data['categories'] = Topics::where(['status' => true, 'draft' => false, 'slug' => 'sehat-a-z'])->get();
            $data['sub_categories'] = [];
            $data['types'] = $this->types;
            $data['labels'] = ArticleFact::where('status', true)->get();
            $data['tags'] = Tags::all();
            $data['selected_tags'] = [];
            $data['parents'] = $this->getParents(null, 'sub-topic');
            $data['widgets'] = Widget::where('status',1)->whereIn('type', [Constant::WIDGET_TYPE_ARTICLE, Constant::WIDGET_TYPE_BOTH])->get();
            $data['specialities'] = Speciality::where('status',1)->where('type', 'doctor')->get();
            $input_elements = $this->input_elements;
            $data['include_status_radio'] = 1;

            $path = '';
            if ($data['result']) {
                if ($data['result']->lang_id == 1) {
                    if (Str::contains(URL::current(), 'staging')) {
                        $path = env('APP_REACT_DEV') ? env('APP_REACT_WEB_URL') : env('APP_URL') . 'article/' . $data['result']->slug;
                    } else {
                        $path = env('APP_REACT_WEB_URL') . 'article/' . $data['result']->slug;
                    }
                } else {
                    if (Str::contains(URL::current(), 'staging')) {
                        $path = env('APP_REACT_DEV') ? env('APP_REACT_WEB_URL') : env('APP_URL') . $data['language_slug']->slug . '/article/' . $data['result']->slug;
                    } else {
                        $path = env('APP_REACT_WEB_URL') . $data['language_slug']->slug . '/article/' . $data['result']->slug;
                    }
                }
            }
            $data['path'] = $path;
            $file = "admin.".$this->folder_name.".disease-form";
            return view($file,$data);
        }
    }

    public function editDisease(Request $request, $id){
        if (Gate::denies('article-management-update') && Gate::denies('article-management-disease')) {
            abort(403);
        }
        $id = decrypt($id);
        $constant = new Constant();
        $getArticle = MainModel::where('id',$id)->with('articleWidgets', 'articleLabel')->first();
        if (Auth::user()->role_id != $constant->SUPER_ADMIN_ROLE_ID
            &&  Auth::user()->role_id != $constant->ADMIN_ROLE_ID
            &&  Auth::user()->role_id != $constant->CONTENT_LEAD_ROLE_ID
            &&  Auth::user()->role_id != $constant->BRANDING_LEAD_ROLE_ID
            &&  Auth::user()->role_id != $constant->SEO_LEAD_ROLE_ID
            &&  Auth::user()->role_id != $constant->EDITOR_ROLE_ID
            &&  Auth::user()->role_id != $constant->UPLOADERS_ROLE_ID
            &&  Auth::user()->role_id != $constant->SEO_ROLE_ID
            &&  Auth::user()->role_id != $constant->CONTENT_TEAM_ROLE_ID
            &&  $getArticle->status == true
        ){
            Helper::toast('error', 'You are not allowed to edit this Article');
            if (str_contains($request->segment(3), 'draft')) {
                return redirect()->route($this->folder_name . '-disease-draft');
            } else {
                return redirect()->route($this->folder_name . '-disease-view');
            }
        }
        if($request->isMethod('post')){
            return $this->form($request,$id);
        }else{
            $language = Language::find($getArticle->lang_id);
            $data['english_records'] = $this->english_records;
            $data['english_records_name_element'] = $this->english_records_name_element;
            $data['page_header'] = "Edit Disease ".$this->module_name;
            $data['result'] = $getArticle;
            $data['language_slug'] = $language->slug;
            $this->setLangSession($data['result']->lang_id);
            $data['wellness_profiles'] = User::where(['status' => true, 'is_blocked' => false, 'role_id' => $constant->FITNESS_EXPERTS_ROLE_ID])->get();
            $data['doctors'] = User::where(['status' => true, 'is_blocked' => false, 'role_id' => $constant->DOCTOR_ROLE_ID])->get();
            $data['authors'] = User::where(['status' => true, 'is_blocked' => false, 'role_id' => $constant->AUTHOR_ROLE_ID])->get();
            $data['categories'] = Topics::where(['status' => true, 'draft' => false, 'slug' => 'sehat-a-z'])->get();
            if($data['result']->parent && $data['result']->parent->parent_id != 0){
                $data['sub_categories'] = Topics::where(['status' => true, 'draft' => false, 'parent_id' =>  $data['result']->parent->parent_id])->get();
            }else{
                $data['sub_categories'] = [];
            }
            $data['types'] = $this->types;
            $data['labels'] = ArticleFact::where('status', true) ->get();
            // $data['labels'] = ArticleFact::where('status', true)->where('lang_id', $language->id)->get();
            $data['tags'] = Tags::all();
            $data['selected_tags'] = $this->getSelectedTags($data['result']);
            $data['parents'] = $this->getParents(null, 'sub-topic');
            $data['widgets'] = Widget::where('status',1)->whereIn('type', [Constant::WIDGET_TYPE_ARTICLE, Constant::WIDGET_TYPE_BOTH])->get();
            $data['specialities'] = Speciality::where('status',1)->where('type', 'doctor')->get();
            $data['include_status_radio'] = 1;

            $path = '';
            if ($data['result']) {
                if ($data['result']->lang_id == 1) {
                    if (Str::contains(URL::current(), 'staging')) {
                        $path = env('APP_REACT_DEV') ? env('APP_REACT_WEB_URL') : env('APP_URL') . 'article/' . $data['result']->slug;
                    } else {
                        $path = env('APP_REACT_WEB_URL') . 'article/' . $data['result']->slug;
                    }
                } else {
                    if (Str::contains(URL::current(), 'staging')) {
                        $path = env('APP_REACT_DEV') ? env('APP_REACT_WEB_URL') : (env('APP_URL') . isset($data['language_slug']->slug) ? $data['language_slug']->slug : $data['language_slug'] . '/article/' . $data['result']->slug);
                    } else {
                        // $path = env('APP_REACT_WEB_URL') . is_object($data['language_slug']) && property_exists($data['language_slug'], 'slug') && isset($data['language_slug']->slug) ? $data['language_slug']->slug : (isset($data['result']->slug) ? $data['language_slug'] . '/article/' . $data['result']->slug : null);
                        $slug = isset($data['language_slug']->slug) ? $data['language_slug']->slug : $data['language_slug'];
                        $path = env('APP_REACT_WEB_URL') . $slug . '/article/' . $data['result']->slug;
                    }
                }
            }
          $data['path'] = $path;
            $file = "admin.".$this->folder_name.".disease-form";
            return view($file,$data);
        }
    }

    public function edit(Request $request, $id){
        if (Gate::denies('article-management-update')) {
            abort(403);
        }
        $id = decrypt($id);
        $constant = new Constant();
        $getArticle = MainModel::where('id',$id)->with('articleWidgets', 'articleLabel')->first();
        if (Auth::user()->role_id != $constant->SUPER_ADMIN_ROLE_ID
            &&  Auth::user()->role_id != $constant->EDITOR_ROLE_ID
            &&  Auth::user()->role_id != $constant->ADMIN_ROLE_ID
            &&  Auth::user()->role_id != $constant->UPLOADERS_ROLE_ID
            &&  Auth::user()->role_id != $constant->CONTENT_LEAD_ROLE_ID
            &&  Auth::user()->role_id != $constant->BRANDING_LEAD_ROLE_ID
            &&  Auth::user()->role_id != $constant->SEO_ROLE_ID
            &&  $getArticle->status == true
        ){
            Helper::toast('error', 'You are not allowed to edit this Article');
            return redirect()->route($this->folder_name.'-view');
        }
        if($request->isMethod('post')){
            return $this->form($request,$id);
        }else{
            $language = Language::find($getArticle->lang_id);
            $data['english_records'] = $this->english_records;
            $data['english_records_name_element'] = $this->english_records_name_element;
            $data['page_header'] = "Edit ".$this->module_name;
            $data['result'] = $getArticle;
            $data['language_slug'] = $language->slug;
            $this->setLangSession($data['result']->lang_id);
            $data['wellness_profiles'] = User::where(['status' => true, 'is_blocked' => false, 'role_id' => $constant->FITNESS_EXPERTS_ROLE_ID])->get();
            $data['doctors'] = User::where(['status' => true, 'is_blocked' => false, 'role_id' => $constant->DOCTOR_ROLE_ID])->get();
            $data['authors'] = User::where(['status' => true, 'is_blocked' => false, 'role_id' => $constant->AUTHOR_ROLE_ID])->get();
            $data['categories'] = Topics::where(['status' => true, 'draft' => false, 'parent_id' => 0])->get();
            if($data['result']->parent && $data['result']->parent->parent_id != 0){
                $data['sub_categories'] = Topics::where(['status' => true, 'draft' => false, 'parent_id' =>  $data['result']->parent->parent_id])->get();
            }else{
                $data['sub_categories'] = [];
            }
            $data['types'] = $this->types;
            $data['labels'] = ArticleFact::where('status', true) ->get();
            // $data['labels'] = ArticleFact::where('status', true)->where('lang_id', $language->id)->get();
            $data['tags'] = Tags::all();
            $data['selected_tags'] = $this->getSelectedTags($data['result']);
            $data['parents'] = $this->getParents(null, 'sub-topic');
            $data['widgets'] = Widget::where('status',1)->whereIn('type', [Constant::WIDGET_TYPE_ARTICLE, Constant::WIDGET_TYPE_BOTH])->get();
            $data['specialities'] = Speciality::where('status',1)->where('type', 'doctor')->get();
            $data['include_status_radio'] = 1;
            $path = '';
            if ($data['result']) {
                if ($data['result']->lang_id == 1) {
                    if (Str::contains(URL::current(), 'staging')) {
                        $path = env('APP_REACT_DEV') ? env('APP_REACT_WEB_URL') : env('APP_URL') . 'article/' . $data['result']->slug;
                    } else {
                        $path = env('APP_REACT_WEB_URL') . 'article/' . $data['result']->slug;
                    }
                } else {
                    if (Str::contains(URL::current(), 'staging')) {
                        $path = env('APP_REACT_DEV') ? env('APP_REACT_WEB_URL') : env('APP_URL') . $data['language_slug']->slug . '/article/' . $data['result']->slug;
                    } else {
                        $path = env('APP_REACT_WEB_URL') . $data['language_slug']->slug . '/article/' . $data['result']->slug;
                    }
                }
            }
            $data['path'] = $path;
            $file = "admin.".$this->folder_name.".form";
            return view($file,$data);
        }
    }

    public function update_sequence(Request $request){
        foreach($request->sequence as $key => $sequence){
            ReferenceWidget::where('id' , $sequence)->update([
                'sequence' => ($key +1) *10
            ]);
        }
        return back();
    }

    public function insertWidgets($article_id, $widgets){
        if(gettype($widgets) != "string"){
            if(count($widgets) > 0){
                $sequence = ReferenceWidget::where(['reference_id' => $article_id, 'reference_type' => 'article'])->count();
                foreach($widgets as $key => $widget){
                    ReferenceWidget::create([
                        'reference_id' => $article_id,
                        'reference_type' => 'article',
                        'widget_id' => $widget,
                        'sequence' => $sequence > 0 ? ($sequence +1) *10 : ($key +1) *10,
                    ]);
                }
            }
        }
        else{
            $widgets = json_decode($widgets);
            if(count((array)$widgets) > 0){
                foreach($widgets as $key => $widget){
                    $sequence = ReferenceWidget::where(['reference_id' => $article_id, 'reference_type' => 'article'])->count();
                    $index = 0;
                    if((int)$widget > 0){
                        for ($i=0; $i < $widget; $i++) {
                            $sequence = ReferenceWidget::where(['reference_id' => $article_id, 'reference_type' => 'article'])->count();
                            ReferenceWidget::create([
                                'reference_id' => $article_id,
                                'reference_type' => 'article',
                                'widget_id' => $key,
                                'sequence' => $sequence > 0 ? ($sequence +1) *10 : ($index + $i +1) *10,
                            ]);
                        }
                    }
                    else{
                        ReferenceWidget::create([
                            'reference_id' => $article_id,
                            'reference_type' => 'article',
                            'widget_id' => $key,
                            'sequence' => $sequence > 0 ? ($sequence +1) *10 : ($index +1) *10,
                        ]);
                    }
                    $index++;
                }
            }
        }
    }
    public function insertTags($article_id, $tags){
        if(count($tags) > 0){
            $article = MainModel::find($article_id);
            foreach($tags as $tag){
                $firstOrCreateTag = Tags::firstOrCreate(['name' => $tag])->id;
                $create_article_tags = [
                    'article_id' => $article_id,
                    'tag_id' => $firstOrCreateTag,
                ];
                $article_tags[] = $create_article_tags;
            }
            $article->articleTags()->delete();
            $article->articleTags()->createMany($article_tags);
        }
    }

    public function getParents($article_id = null, $parent){
        if($article_id == null){
            return Topics::where('status',1)->where('draft',false)->where('parent_id','!=',0)->get();
        }else if($parent == "drug"){
            return Disease::where('status',1)->where('draft',false)->whereHas('page')->get();
        }else if($parent == "disease"){
            return Disease::where('status',1)->where('draft',false)->whereHas('page')->get();
        }else if($parent == "sub-topic"){
            return SubTopic::where('status',1)->where('draft',false)->get();
        }
    }

    public function getSelectedTags($article){
        $selectedTags = [];
        $articleTags = $article->articleTags;
        foreach($articleTags as $article_tag){
            $selectedTags[] = $article_tag->tag->name;
        }
        return $selectedTags;

    }

    public function getArticleParentByType(Request $request){
        $general_response = ['status' => true];
        try{
            if($request->type == 'disease'){
              $data['parents'] =  Disease::where('status',1)->whereHas('page')->get();
            }else if($request->type == 'drug'){
                $data['parents'] =  Disease::where('status',1)->whereHas('page')->get();
            }else{
                $data['parents'] =  Topics::where('status',1)->where('parent_id','!=',0)->get();
            }
            $file = "admin.".$this->folder_name.".parents_dropdown";
            $html = view($file,$data)->render();
            $general_response['html'] = $html;
            return $general_response;
        }catch(\Exception $e){
            return [
                'status' => false,
                'message' => 'Something went wrong'
            ];
        }
    }

    public function reArrangeReferenceWidgets($reference_id,$reference_type){
        try{
            // $ReferenceWidget = ReferenceWidget::find($reference_widget_id);
            // $reference_id = $ReferenceWidget->reference_id;
            // $reference_type = $ReferenceWidget->reference_type;
            $unseqWidgets = ReferenceWidget::where(['reference_id'=> $reference_id, 'reference_type'=> $reference_type])->orderBy('sequence','ASC')->get();
            if($unseqWidgets != null && count($unseqWidgets) > 0){
                foreach($unseqWidgets as $key => $unseqWidget){
                    ReferenceWidget::where('id',$unseqWidget->id)->update([
                        'sequence' => ($key +1) * 10,
                    ]);
                }
            }
            return true;
        }catch(\Exception $e){
            return false;
        }
    }

    public function repositionWidget(Request $request){
        $ReferenceWidget = ReferenceWidget::find($request->reference_widget_id);
        if($ReferenceWidget != null){
            if($request->position == "up"){
                $ReferenceWidget->sequence = $ReferenceWidget->sequence -15;
            }else{
                $ReferenceWidget->sequence = $ReferenceWidget->sequence +15;
            }
            $ReferenceWidget->save();
            return $this->reArrangeReferenceWidgets($ReferenceWidget->reference_id, $ReferenceWidget->reference_type);
        }

        return false;
    }


    public function addWidget(Request $request){
        if($request->reference_widget_id > 0){
            $ReferenceWidget = ReferenceWidget::find($request->reference_widget_id);
            $this->insertWidgets($ReferenceWidget->reference_id, $request->res);
        }else{
            $this->insertWidgets($request->article_id, $request->res);
        }
        return true;
    }

    public function deleteWidget(Request $request){
        $ReferenceWidget = ReferenceWidget::find($request->reference_widget_id);
        if($ReferenceWidget != null){
            $ReferenceWidget->delete();
            return true;
        }
        return false;
    }

    public function deleteWidgetByReference(Request $request, $reference_id)
    {
        $referenceWidget = ReferenceWidget::where('id', $reference_id)->first();
        if($referenceWidget){
            $referenceWidget->delete();
        }
        return redirect()->back();
    }

}
