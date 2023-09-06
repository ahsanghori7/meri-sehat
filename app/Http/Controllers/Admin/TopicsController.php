<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Topics as MainModel,
    Page,
    Language,
    TopicArchive,
    };
use App\Http\Common\Helper;
use DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Gate;

class TopicsController extends Controller
{
    public $folder_name = 'topics'; // For view routes and file calling and saving
    public $module_name = 'Category Listing'; // For toast And page header
    public $input_elements;
    public $selected_value;
    public $include_translation_section = 1; // Language and translation section
    public $english_records = [];



    public function __construct(array $attributes = array())
    {
        $this->english_records_name_element = 'title';
        foreach (MainModel::where('lang_id',Language::ENGLISH)->orderBy('title')->get() as $key => $english_record){
            $this->english_records[$key]['id'] = $english_record->id;
            $this->english_records[$key]['title'] = $english_record->parent ? $english_record->parent->title . ' > ' . $english_record->title : $english_record->title;
        }
        // $this->english_records = MainModel::where('lang_id',Language::ENGLISH)->get();
    }

    public function draftView(Request $request){
        if (Gate::denies('category-management-draft')) {
            abort(403);
        }
        if($request->isMethod('post')){
            foreach($request->sequence as $key => $id){
                $sequence = $key + 1;
                MainModel::find($id)->where('draft', true)->update(['sequence' => str_pad($sequence,2,"0",STR_PAD_LEFT)]);
            }
            return back();
        }else{
            $data['folder_name'] = $this->folder_name;
            $data['module_name'] = $this->module_name;
            $data['categories'] = MainModel::whereHas('children')->get();
            $data['languages'] = Language::where('status', 1)->get();
            $file = "admin.".$this->folder_name.".draft-view";
            return view($file,$data);
        }
    }

    public function view(Request $request){
        if (Gate::denies('category-management-view')) {
            abort(403);
        }
        if($request->isMethod('post')){
            foreach($request->sequence as $key => $id){
                $sequence = $key + 1;
                MainModel::find($id)->where('draft', false)->update(['sequence' => str_pad($sequence,2,"0",STR_PAD_LEFT)]);
            }
            return back();
        }else{
            $data['folder_name'] = $this->folder_name;
            $data['module_name'] = $this->module_name;
            $data['categories'] = MainModel::whereHas('children')->get();
            $data['languages'] = Language::where('status', 1)->get();
            $file = "admin.".$this->folder_name.".view";
            return view($file,$data);
        }
    }

    public function draftFetch(Request $request){
        $query = MainModel::where('draft', true)->with(['parent', 'parent.parent', 'language']);
        if(isset($request->status)) {
            $query = $query->where('status', $request->status);
        }
        if(isset($request->language)) {
            $query = $query->where('lang_id', $request->language);
        }
        if(isset($request->category)) {
            $query = $query->where('parent_id', $request->category);
        }
        return DataTables::of($query)->make(true);
    }

    public function fetch(Request $request){
        $query = MainModel::where('draft', false)->with(['parent', 'parent.parent', 'language']);
        if(isset($request->status)) {
            $query = $query->where('status', $request->status);
        }
        if(isset($request->language)) {
            $query = $query->where('lang_id', $request->language);
        }
        if(isset($request->category)) {
            $query = $query->where('parent_id', $request->category);
        }
        return DataTables::of($query)->make(true);

    }

    public function form(Request $request, $id = null){
//        dd($request->all());
//        $request->validate(MainModel::getValidationRules($id));
        if($request->lang_id != 1 && $request->translation_of == 'parent'){
            return back()->withErrors([
                'lang_id' => 'Cannot create parent in this language.'
            ]);
        }
//        $data = [
//            'lang_id' => $request->lang_id,
//            'title' => $request->title,
//            'color_code' => $request->color_code,
//            'outer_text' => $request->outer_text,
//            'banner_text' => $request->banner_text,
//            'parent_id' => $request->parent_id ?? ($request->category ?? 0),
//            'status' => $request->status ?? 1,
//            'meta_description' => $request->meta_description,
//        ];
        if ($request->has('lang_id')) {
            $data['lang_id'] = $request->lang_id;
        }
        if ($request->has('title')) {
            $data['title'] = $request->title;
        }
        if ($request->has('color_code')) {
            $data['color_code'] = $request->color_code;
        }
        if ($request->has('outer_text')) {
            $data['outer_text'] = $request->outer_text;
        }
        if ($request->has('banner_text')) {
            $data['banner_text'] = $request->banner_text;
        }
        if ($request->has('parent_id') && $request->parent_id > 0) {
            $data['parent_id'] = $request->parent_id;
        } else {
            $data['parent_id'] = $request->category ?? 0;
        }
        if ($request->has('status')) {
            $data['status'] = $request->status ?? 1;
        }
        if ($request->has('meta_description')) {
            $data['meta_description'] = $request->meta_description;
        }
        if($request->translation_of != 'parent'){
            $data['translation_of'] = $request->translation_of;
        }
        if($request->hasFile('outer_image')){
            $folder="topics";
            $key="outer_image";
            $data['outer_image'] = $this->S3UploaderSpecificKey($key,$request,$folder);
        }
        if($request->hasFile('outer_home_image')){
            $folder="topics";
            $key="outer_home_image";
            $data['outer_home_image'] = $this->S3UploaderSpecificKey($key,$request,$folder);
        }
        if ($request->has('goto_live')) {
            $data['draft'] = 0;
            $data['status'] = true;
        } else {
            if ($request->segment(3) == 'draft') {
                $data['draft'] = 1;
            }
        }
        if($id){
            $getPageDetail = Page::where(['reference_id'=> $id, 'reference_type'=> 'topic'])->first();
            if($getPageDetail && $data['parent_id'] != 0){
                $getTopicDetail = MainModel::find($data['parent_id']);
                $getPageDetail->update(['slug' => $getTopicDetail->slug]);
            }
            if(MainModel::find($id)->update($data)){
                Helper::toast('success',$this->module_name.' Updated.');
            }
        }else{
            $getAllTopic = Page::where('reference_type', 'topic')->where('lang_id',$request->lang_id)->select('slug')->get();
            if($request->lang_id == 1){
                $data['slug'] = count($getAllTopic) ? $this->uniqueSlug(\Str::slug($request->title),$getAllTopic,$id) : \Str::slug($request->title);
            }else{
                $parent = MainModel::find($request->translation_of);
                if(isset($parent)){
                    $data['slug'] = count($getAllTopic) ? $this->uniqueSlug($parent->slug,$getAllTopic,$id) : \Str::slug($parent->slug);
                    $data['parent_id'] = $parent->parent_id;
                }
            }
            if(MainModel::create($data)){
                Helper::toast('success',$this->module_name.' created.');
            }
        }
        if (str_contains($request->segment(3), 'draft')) {
            return redirect()->route($this->folder_name . '-draft');
        }
        return redirect()->route($this->folder_name.'-view');
    }

    public function markDraft(Request $request, $id){
        if (Gate::denies('category-management-draft')) {
            abort(403);
        }
        $id = decrypt($id);
        $getTopic = MainModel::find($id);
        $getTopic->draft = 1;
        $getTopic->status = 0;
        $topic_save = $getTopic->save();
        if (isset($topic_save)) {
            Helper::toast('success',$getTopic->name.' successfully draft.');
        } else {
            Helper::toast('failed',$this->module_name.' not going to draft.');
        }
        return redirect()->route($this->folder_name.'-draft');
    }

    public function gotoLive(Request $request, $id){
        if (Gate::denies('category-management-goto_live')) {
            abort(403);
        }
        $id = decrypt($id);
        $getTopic = MainModel::find($id);
        $getTopic->draft = 0;
        $getTopic->status = 1;
        $topic_save = $getTopic->save();
        if (isset($topic_save)) {
            Helper::toast('success',$getTopic->name.' successfully live.');
        } else {
            Helper::toast('failed',$this->module_name.' not going to live.');
        }
        return redirect()->route($this->folder_name.'-draft');
    }

    public function add(Request $request){
//        Gate::authorize('category-management-add');
        if (Gate::denies('category-management-add')) {
            abort(403);
        }
        if($request->isMethod('post')){
            return $this->form($request);
        }else{
            $data['page_header'] = "Add ".$this->module_name;
            $data['categories'] = MainModel::where(['status' => true, 'parent_id' => 0])->get();
            $data['result'] = null;
            $data['sub_categories'] = [];
            $data['include_translation_section'] = $this->include_translation_section;
            $data['english_records'] = $this->english_records;
            $data['english_records_name_element'] = $this->english_records_name_element;
            $input_elements = $this->input_elements;
            $data['include_status_radio'] = 1;
            $data['include_is_mobile_show_radio'] = 1;
            $data['include_is_web_show_radio'] = 1;
            // return view('general_crud.general_view',$data)->with(compact('input_elements'));
            $file = "admin.".$this->folder_name.".form";
            return view($file,$data);
        }
    }

    public function edit(Request $request, $id){
        $id = decrypt($id);
        if (Gate::denies('category-management-update')) {
            abort(403);
        }
        if($request->isMethod('post')){
            Gate::authorize('category-management-update');
            return $this->form($request,$id);
        }else{
            Gate::authorize('category-management-view');
            $data['page_header'] = "Edit ".$this->module_name;
            $data['categories'] = MainModel::where(['status' => true, 'parent_id' => 0])->get();
            $data['result'] = MainModel::find($id);
            $data['include_translation_section'] = $this->include_translation_section;
            $data['english_records'] = $this->english_records;
            $data['english_records_name_element'] = $this->english_records_name_element;
            if($data['result']->parent && $data['result']->parent->parent_id != 0){
                $data['sub_categories'] = MainModel::where(['status' => true, 'parent_id' =>  $data['result']->parent->parent_id])->get();
            }else{
                $data['sub_categories'] = [];
            }
            $data['include_status_radio'] = 1;
            $data['include_is_mobile_show_radio'] = 1;
            $data['include_is_web_show_radio'] = 1;
            $input_elements = $this->input_elements;
            // return view('general_crud.general_view',$data)->with(compact('input_elements'));
            $file = "admin.".$this->folder_name.".form";
            return view($file,$data);
        }
    }

    public function getParentByType(Request $request){
        $general_response = ['status' => true];
        $getParent = MainModel::find($request->type);
        try{
            if($request->type != 0){
                $data['parents'] = MainModel::where(['parent_id' => $request->type, 'lang_id' => $getParent->lang_id])->get();
            }else{
                $data['parents'] = [];
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

    public function linkWithPage(Request $request, $id)
    {
        $id = decrypt($id);
        $getPageDetail = Page::where(['reference_id'=> $id, 'reference_type'=> 'topic'])->first();
        if(!$getPageDetail){
            $getTopicDetail = MainModel::find($id);
            $getPageDetail = Page::create([
                'lang_id' => $getTopicDetail->lang_id,
                'reference_type' => 'topic',
                'reference_id' => $id,
                'name' => $getTopicDetail->title,
                'slug' => $getTopicDetail->slug,
                'parent_id' => $getTopicDetail->parent_id,
            ]);
        }
        return redirect('admin/page/edit/'.encrypt($getPageDetail->id));
    }

    public function topicArchive(Request $request,$id){
        if (Gate::denies('category-management-archive')) {
            abort(403);
        }
        $id = decrypt($id);
        $topicArchive= new TopicArchive;
        $getTopic = MainModel::where('id',$id)->first();
        if($getTopic){
          $topicArchive->translation_of =  $getTopic->translation_of;
          $topicArchive->lang_id =  $getTopic->lang_id;
          $topicArchive->parent_id =  $getTopic->parent_id;
          $topicArchive->sequence =  $getTopic->sequence;
          $topicArchive->meta_keyword =  $getTopic->meta_keyword;
          $topicArchive->meta_description =  $getTopic->meta_description;
          $topicArchive->title =  $getTopic->title;
          $topicArchive->slug =  $getTopic->slug;
          $topicArchive->color_code =  $getTopic->color_code;
          $topicArchive->outer_image =  $getTopic->outer_image;
          $topicArchive->outer_text =  $getTopic->outer_text;
          $topicArchive->banner_text =  $getTopic->banner_text;
          $topicArchive->status =  $getTopic->status;
          $topicArchive->created_by =  $getTopic->created_by;
          $topicArchive->timestamps=$getTopic->timestamps;
          $topicArchive->visit_counts =  $getTopic->visit_counts;
          $topicArchive->outer_home_image =  $getTopic->outer_home_image;
          $topicArchive->draft =  $getTopic->draft;

          $topicArchive->save();
          $getTopic->forceDelete();
          return redirect()->back();
        }
    }

}
