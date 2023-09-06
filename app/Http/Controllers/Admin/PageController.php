<?php

namespace App\Http\Controllers\Admin;

use App\Http\Common\Constant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Common\Helper;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use App\Models\{Page as MainModel,
    ReferenceWidget,
    Disease,
    Speciality,
    Widget,
    Topics,
    SubTopic,
    Tags,
    User,
    Language,
    PageArchive};

class PageController extends Controller
{
    public $folder_name = 'page'; // For view routes and file calling and saving
    public $module_name = 'Default Page'; // For toast And page header
    public $input_elements;
    private $types = [
        [
            "value" => "page",
            "name" => "Page"
        ],
        [
            "value" => "topic",
            "name" => "Topic"
        ],
        [
            "value" => "disease",
            "name" => "Disease"
        ],
    ];

    public function draftView(Request $request){
        if (Gate::denies('default-page-draft')) {
            abort(403);
        }
        if($request->isMethod('post')){
            foreach($request->sequence as $key => $id){
                $sequence = $key + 1;
                MainModel::find($id)->where('status', true)->update(['sequence' => str_pad($sequence,2,"0",STR_PAD_LEFT)]);
            }
            return back();
        }else{
            $data['folder_name'] = $this->folder_name;
            $data['module_name'] = $this->module_name;
            // $data['result'] = MainModel::where('reference_type', 'page')->get();
            $file = "admin.".$this->folder_name.".draft-view";
            $data['languages'] = Language::where('status', 1)->get();
            return view($file,$data);
        }
    }

    public function view(Request $request){
        if (Gate::denies('default-page-view')) {
            abort(403);
        }
        if($request->isMethod('post')){
            foreach($request->sequence as $key => $id){
                $sequence = $key + 1;
                MainModel::find($id)->where('status', false)->update(['sequence' => str_pad($sequence,2,"0",STR_PAD_LEFT)]);
            }
            return back();
        }else{
            $data['folder_name'] = $this->folder_name;
            $data['module_name'] = $this->module_name;
            // $data['result'] = MainModel::where('reference_type', 'page')->get();
            $file = "admin.".$this->folder_name.".view";
            $data['languages'] = Language::where('status', 1)->get();
            return view($file,$data);
        }
    }

    public function gotoLive(Request $request, $id){
        if (Gate::denies('default-page-general-info-goto_live')) {
            abort(403);
        }
        $id = decrypt($id);
        $getPage = MainModel::find($id);
        $getPage->draft = 0;
        $getPage->status = 1;
        $page_save = $getPage->save();
        if (isset($page_save)) {
            Helper::toast('success',$getPage->name.' successfully live.');
        } else {
            Helper::toast('failed',$this->module_name.' not going to live.');
        }
        return redirect()->route($this->folder_name.'-draft');
    }

    public function draftFetch(Request $request){
        $query = MainModel::where('reference_type', 'page')->where('draft', true)->with('language');
        return DataTables::of($query)->make(true);
    }

    public function fetch(Request $request){
        $query = MainModel::where('reference_type', 'page')->where('draft', false)->where('status', 1)->with('language');
        return DataTables::of($query)->make(true);
    }

    public function removeImage(Request $request){
        if ($request->page_id > 0) {
            $query = MainModel::where('id', $request->page_id)->first();
            if ($query) {
                $query->image = null;
                $query->save();
            } else {
                return $this->returnResponse(400, 'Image has not removed');
            }
        } else {
            return $this->returnResponse(404, 'Page not found!');
        }
        return $this->returnResponse(200, 'Image has been removed');
    }

    public function form(Request $request, $id = null){
        $chk_category = Topics::where('id', $request->category)->first();
        if ($chk_category && $chk_category->title == 'Wellness') {
            $reviewed_by = isset($request->reviewed_by_wellness) ? $request->reviewed_by_wellness : null;
        } else {
            $reviewed_by = isset($request->reviewed_by_doctor) ? $request->reviewed_by_doctor : null;
        }
//        $data = [
//            'name' => $request->name,
//            'descripton' => $request->descripton,
//            'keywords' => $request->keywords,
//            'reviewed_by' => $reviewed_by,
//            'written_by' => isset($request->written_by) ? $request->written_by : 1,
//            'is_description_show' => isset($request->is_description_show) ? $request->is_description_show : false,
//            'parent_id' => $request->parent_id ?? ($request->category ?? 0),
//            'hide_image_in_detail' => $request->hide_image_in_detail ?? 0,
//            'status' => $request->status ?? 1,
//            'class_name' => $request->class_name ?? '',
//            'meta_name' => $request->meta_name ?? '',
//            'meta_description' => $request->meta_description ?? '',
//            'is_featured' => $request->is_featured ?? 0,
//            'alt' => $request->alt ?? null,
//        ];
        if ($request->has('name')) {
            $data['name'] = $request->name;
        }
        if ($request->has('descripton')) {
            $data['descripton'] = $request->descripton;
        }
        if ($request->has('keywords')) {
            $data['keywords'] = $request->keywords;
        }
        if ($reviewed_by) {
            $data['reviewed_by'] = $reviewed_by;
        }
        if ($request->has('written_by')) {
            $data['written_by'] = isset($request->written_by) ? $request->written_by : 1;
        }
        if ($request->has('is_description_show')) {
            $data['is_description_show'] = isset($request->is_description_show) ? $request->is_description_show : false;
        }
        if ($request->has('parent_id')) {
            $data['parent_id'] = $request->parent_id ?? ($request->category ?? 0);
        }
        if ($request->has('hide_image_in_detail')) {
            $data['hide_image_in_detail'] = $request->hide_image_in_detail ?? 0;
        }
        if ($request->has('status')) {
            $data['status'] = $request->status ?? 1;
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
        if ($request->has('alt')) {
            $data['alt'] = $request->alt ?? '';
        }
        if ($request->has('speciality_id') && $request->speciality_id) {
            $data['speciality_id'] = $request->speciality_id;
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
            $data['reference_id'] = $request->reference_id;
            $data['reference_type'] = "page";
            $data['lang_id'] = 1;
            if ($request->slug != '') {
                $chk_existing = MainModel::where('slug', $request->slug)
                    ->where('lang_id', $request->lang_id)
                    ->where('status',  1)
                    ->first();
                if ($chk_existing) {
                    Helper::toast('error',$this->module_name.' slug duplicate.');
                    return back();
                }
                $data['slug'] = $request->slug;
            } else {
                $getAllPage = MainModel::where('reference_type', 'page')->select('slug')->get();
                $data['slug'] = count($getAllPage) ? $this->uniqueSlug(\Str::slug($request->name),$getAllPage,$id) : \Str::slug($request->name);
            }
        }
        if($request->hasFile('image')){
            $folder="page";
            $key="image";
            $data['image'] = $this->S3UploaderSpecificKey($key,$request,$folder);
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
                if(isset($request->tags)) {
                    $this->insertTags($id, $request->tags);
                }
                if($request->widgets) {
                    $this->insertWidgets($id, $request->widgets);
                }
                Helper::toast('success',$this->module_name.' Updated.');
            }
        }else{
            if(isset($request->tags)) {
                $this->insertTags($id, $request->tags);
            }
            if($created_data = MainModel::create($data)){
                Helper::toast('success',$this->module_name.' created.');
                return redirect()->route($this->folder_name.'-edit',['id' => encrypt($created_data->id)]);
            }
        }
        // return redirect()->route($this->folder_name.'-view');
        return back();

    }

    public function markDraft(Request $request, $id){
        if (Gate::denies('default-page-draft')) {
            abort(403);
        }
        $id = decrypt($id);
        $getPage = MainModel::find($id);
        $getPage->draft = 1;
        $getPage->status = 0;
        $page_save = $getPage->save();
        if (isset($page_save)) {
            Helper::toast('success',$getPage->name.' successfully draft.');
        } else {
            Helper::toast('failed',$this->module_name.' not going to draft.');
        }
        return redirect()->route($this->folder_name.'-draft');
    }

    public function add(Request $request){
        if (Gate::denies('default-page-add')) {
            abort(403);
        }
        if($request->isMethod('post')){
            return $this->form($request);
        }else{
            $constant = new Constant();
            $data['page_header'] = "Add ".$this->module_name;
            $data['result'] = null;
            $data['types'] = $this->types;
            $data['tags'] = Tags::all();
            $data['selected_tags'] = [];
            $data['doctors'] = User::where(['status' => true, 'is_blocked' => false, 'role_id' => $constant->DOCTOR_ROLE_ID])->get();
            $data['wellness_profiles'] = User::where(['status' => true, 'is_blocked' => false, 'role_id' => $constant->FITNESS_EXPERTS_ROLE_ID])->get();
            $data['authors'] = User::where(['status' => true, 'is_blocked' => false, 'role_id' => $constant->AUTHOR_ROLE_ID])->get();
            $data['parents'] = $this->getParents(null, 'sub-topic');
            $data['widgets'] = Widget::where('status',1)->whereIn('type', [Constant::WIDGET_TYPE_PAGE, Constant::WIDGET_TYPE_BOTH])->get();
            $data['categories'] = Topics::where(['status' => true, 'parent_id' => 0])->get();
            $data['specialities'] = Speciality::where('status',1)->where('type', 'doctor')->get();
            $data['sub_categories'] = [];
            $input_elements = $this->input_elements;
            $data['include_status_radio'] = 1;

            $path = '';
            if ($data['result']) {
                if ($data['result']->lang_id == 1 && $data['result']->slug == 'home') {
                    if (Str::contains(URL::current(), 'staging')) {
                        $path = env('APP_REACT_DEV') ? env('APP_REACT_WEB_URL') : env('APP_URL') . '?admin=true';
                    } else {
                        $path = env('APP_REACT_WEB_URL') . '?admin=true';
                    }
                } elseif ($data['result']->slug == 'home') {
                    if (Str::contains(URL::current(), 'staging')) {
                        $path = env('APP_REACT_DEV') ? env('APP_REACT_WEB_URL') : env('APP_URL') . $language_slug . '?admin=true';
                    } else {
                        $path = env('APP_REACT_WEB_URL') . $language_slug . '?admin=true';
                    }
                } elseif ($data['result']->reference_type == 'topic') {
                    if ($data['result']->lang_id == 1) {
                        if (Str::contains(URL::current(), 'staging')) {
                            $path = env('APP_REACT_DEV') ? env('APP_REACT_WEB_URL') : env('APP_URL') . $data['result']->slug . '?admin=true';
                        } else {
                            $path = env('APP_REACT_WEB_URL') . $data['result']->slug . '?admin=true';
                        }
                    } else {
                        if (Str::contains(URL::current(), 'staging')) {
                            $path = env('APP_REACT_DEV') ? env('APP_REACT_WEB_URL') : env('APP_URL') . $data['language_slug'] . '/' . $data['result']->slug . '?admin=true';
                        } else {
                            $path = env('APP_REACT_WEB_URL') . $data['language_slug'] . '/' . $data['result']->slug . '?admin=true';
                        }
                    }
                } else {
                    if ($data['result']->lang_id == 1) {
                        if (Str::contains(URL::current(), 'staging')) {
                            $path = env('APP_REACT_DEV') ? env('APP_REACT_WEB_URL') : env('APP_URL') . $data['result']->reference_type . '/' . $data['result']->slug . '?admin=true';
                        } else {
                            $path = env('APP_REACT_WEB_URL') . $data['result']->reference_type . '/' . $data['result']->slug . '?admin=true';
                        }
                    } else {
                        if (Str::contains(URL::current(), 'staging')) {
                            $path = env('APP_REACT_DEV') ? env('APP_REACT_WEB_URL') : env('APP_URL') . $data['language_slug'] . '/' . $data['result']->reference_type . '/' . $data['result']->slug . '?admin=true';
                        } else {
                            $path = env('APP_REACT_WEB_URL') . $data['language_slug'] . '/' . $data['result']->reference_type . '/' . $data['result']->slug . '?admin=true';
                        }
                    }
                }
            }
            $data['path'] = $path;

            $file = "admin.".$this->folder_name.".form";
            return view($file,$data);
        }
    }

    public function edit(Request $request, $id){
        if (Gate::denies('default-page-update')) {
            abort(403);
        }
        if (!$request->has('hide_image_in_detail')) {
            $request->merge(['hide_image_in_detail' => 0]);
        }

        $id = decrypt($id);
        if($request->isMethod('post')){
            return $this->form($request,$id);
        }else{
            $constant = new Constant();
            $data['page_header'] = "Edit ".$this->module_name;
            $data['result'] = MainModel::where('id',$id)->with('pageWidgets')->first();
            $data['language_slug'] = Language::find($data['result']->lang_id)->slug;
            $this->setLangSession($data['result']->lang_id);
            $data['types'] = $this->types;
            $data['tags'] = Tags::all();
            $data['selected_tags'] = $this->getSelectedTags($data['result']);
            $data['doctors'] = User::where(['status' => true, 'is_blocked' => false, 'role_id' => $constant->DOCTOR_ROLE_ID])->get();
            $data['wellness_profiles'] = User::where(['status' => true, 'is_blocked' => false, 'role_id' => $constant->FITNESS_EXPERTS_ROLE_ID])->get();
            $data['authors'] = User::where(['status' => true, 'is_blocked' => false, 'role_id' => $constant->AUTHOR_ROLE_ID])->get();
            $data['parents'] = $this->getParents($id, $data['result']->reference_type);
            $data['widgets'] = Widget::where('status',1)->whereIn('type', [Constant::WIDGET_TYPE_PAGE, Constant::WIDGET_TYPE_BOTH])->get();
            $data['categories'] = Topics::where(['status' => true, 'parent_id' => 0, 'lang_id' => $data['result']->lang_id])->get();
            if($data['result']->parent && $data['result']->parent->parent_id != 0){
                $data['sub_categories'] = Topics::where(['status' => true, 'parent_id' =>  $data['result']->parent->parent_id])->get();
            }else{
                $data['sub_categories'] = [];
            }
            $data['include_status_radio'] = 1;
            $data['specialities'] = Speciality::where('status',1)->where('type', 'doctor')->get();
            $input_elements = $this->input_elements;

            $path = '';
            if ($data['result']) {
                if ($data['result']->lang_id == 1 && $data['result']->slug == 'home') {
                    if (Str::contains(URL::current(), 'staging')) {
                        $path = env('APP_REACT_DEV') ? env('APP_REACT_WEB_URL') : env('APP_URL') . '?admin=true';
                    } else {
                        $path = env('APP_REACT_WEB_URL') . '?admin=true';
                    }
                } elseif ($data['result']->slug == 'home') {
                    if (Str::contains(URL::current(), 'staging')) {
                        $path = env('APP_REACT_DEV') ? env('APP_REACT_WEB_URL') : env('APP_URL') . $language_slug . '?admin=true';
                    } else {
                        $path = env('APP_REACT_WEB_URL') . $language_slug . '?admin=true';
                    }
                } elseif ($data['result']->reference_type == 'topic') {
                    if ($data['result']->lang_id == 1) {
                        if (Str::contains(URL::current(), 'staging')) {
                            $path = env('APP_REACT_DEV') ? env('APP_REACT_WEB_URL') : env('APP_URL') . $data['result']->slug . '?admin=true';
                        } else {
                            $path = env('APP_REACT_WEB_URL') . $data['result']->slug . '?admin=true';
                        }
                    } else {
                        if (Str::contains(URL::current(), 'staging')) {
                            $path = env('APP_REACT_DEV') ? env('APP_REACT_WEB_URL') : env('APP_URL') . $data['language_slug'] . '/' . $data['result']->slug . '?admin=true';
                        } else {
                            $path = env('APP_REACT_WEB_URL') . $data['language_slug'] . '/' . $data['result']->slug . '?admin=true';
                        }
                    }
                } else {
                    if ($data['result']->lang_id == 1) {
                        if (Str::contains(URL::current(), 'staging')) {
                            $path = env('APP_REACT_DEV') ? env('APP_REACT_WEB_URL') : env('APP_URL') . $data['result']->reference_type . '/' . $data['result']->slug . '?admin=true';
                        } else {
                            $path = env('APP_REACT_WEB_URL') . $data['result']->reference_type . '/' . $data['result']->slug . '?admin=true';
                        }
                    } else {
                        if (Str::contains(URL::current(), 'staging')) {
                            $path = env('APP_REACT_DEV') ? env('APP_REACT_WEB_URL') : env('APP_URL') . $data['language_slug'] . '/' . $data['result']->reference_type . '/' . $data['result']->slug . '?admin=true';
                        } else {
                            $path = env('APP_REACT_WEB_URL') . $data['language_slug'] . '/' . $data['result']->reference_type . '/' . $data['result']->slug . '?admin=true';
                        }
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
                $sequence = ReferenceWidget::where(['reference_id' => $article_id, 'reference_type' => 'page'])->count();
                foreach($widgets as $key => $widget){
                    ReferenceWidget::create([
                        'reference_id' => $article_id,
                        'reference_type' => 'page',
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
                    $sequence = ReferenceWidget::where(['reference_id' => $article_id, 'reference_type' => 'page'])->count();
                    $index = 0;
                    if((int)$widget > 0){
                        for ($i=0; $i < $widget; $i++) { 
                            $sequence = ReferenceWidget::where(['reference_id' => $article_id, 'reference_type' => 'page'])->count();
                            ReferenceWidget::create([
                                'reference_id' => $article_id,
                                'reference_type' => 'page',
                                'widget_id' => $key,
                                'sequence' => $sequence > 0 ? ($sequence +1) *10 : ($index + $i +1) *10,
                            ]);
                        }
                    }
                    else{
                        ReferenceWidget::create([
                            'reference_id' => $article_id,
                            'reference_type' => 'page',
                            'widget_id' => $key,
                            'sequence' => $sequence > 0 ? ($sequence +1) *10 : ($index +1) *10,
                        ]);
                    }
                    $index++;
                }
            }
        }
    }

    public function getParents($article_id = null, $parent){
        if($article_id == null){
            return SubTopic::where('status',1)->get();
        }else if($parent == "page"){
            return Topics::where('status',1)->get();
        }else if($parent == "topic"){
            return Topics::where('status',1)->get();
        }
    }

    public function getPageParentByType(Request $request){
        $general_response = ['status' => true];
        try{
            if($request->reference_type == 'topic'){
              $data['parents'] = Topics::where('status',1)->get();
            }else{
                $data['parents'] =  [];
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

    public function insertTags($page_id, $tags){
        if(count($tags) > 0){
            $article = MainModel::find($page_id);
            foreach($tags as $tag){
                $firstOrCreateTag = Tags::firstOrCreate(['name' => $tag])->id;
                $create_page_tags = [
                    'page_id' => $page_id,
                    'tag_id' => $firstOrCreateTag,
                ];
                $page_tags[] = $create_page_tags;
            }
            $article->pageTags()->delete();
            $article->pageTags()->createMany($page_tags);
        }
    }

    public function getSelectedTags($page){
        $selectedTags = [];
        $pageTags = $page->pageTags;
        foreach($pageTags as $page_tag){
            $selectedTags[] = $page_tag->tag->name;
        }
        return $selectedTags;

    }

    public function addWidget(Request $request){
        $widgets = isset($request->selected_widgets) ? $request->selected_widgets : $request->res;
        if($request->reference_widget_id > 0){
            $ReferenceWidget = ReferenceWidget::find($request->reference_widget_id);
            $this->insertWidgets($ReferenceWidget->reference_id, $widgets);
        }else{
            $this->insertWidgets($request->page_id, $widgets);
        }
        return true;
    }

    public function linkWithPage(Request $request, $id, $lang_id)
    {
        $id = decrypt($id);
        if($lang_id == 1){
            return redirect('admin/page/edit/' . encrypt($id));
        }
        $parentRecord = MainModel::find($id);
        $alreadyCreatedRecord = MainModel::where(['reference_id'=> $id, 'reference_type'=> 'page', 'lang_id' => $lang_id])->first();
        if($alreadyCreatedRecord){
            return redirect('admin/page/edit/' . encrypt($alreadyCreatedRecord->id));
        }else{
            $getAllPage = MainModel::where(['reference_type' => 'page', 'lang_id' => $lang_id])->get(['slug']);
            $createdRecord = MainModel::create([
                'lang_id' => $lang_id,
                'reference_type' => 'page',
                'reference_id' => $id,
                'name' => $parentRecord->name,
                'slug' => count($getAllPage) ? $this->uniqueSlug(\Str::slug($parentRecord->name), $getAllPage, $lang_id) : \Str::slug($parentRecord->name),
                'descripton' => $parentRecord->description,
                'status' => 0,
            ]);
            if($parentRecord->pageWidgets){
                $widget_ids_array = $parentRecord->pageWidgets->where('status', '1')->pluck('widget_id');
                $this->insertWidgets($createdRecord->id, $widget_ids_array);
            }
            return redirect('admin/page/edit/' . encrypt($createdRecord->id));
        }


        // if(!$getPageDetail){
        //     $getDiseaseDetail = MainModel::find($id);
        //     $getDiseaseDetailAll = MainModel::get('slug');
        //     $getPageDetail = Page::create([
        //         'lang_id' => $lang_id,
        //         'reference_type' => 'page',
        //         'reference_id' => $id,
        //         'name' => $getDiseaseDetail->name,
        //         'slug' => $this->uniqueSlug($getDiseaseDetail->slug,$getDiseaseDetailAll,$id),
        //         'descripton' => $getDiseaseDetail->description,
        //     ]);
        // }
        // return redirect('admin/page/edit/' . encrypt($getPageDetail->id));
    }

    public function deleteWidgetByReference(Request $request, $reference_id)
    {
        $referenceWidget = ReferenceWidget::where('id', $reference_id)->first();
        if($referenceWidget){
            $referenceWidget->delete();
        }
        return redirect()->back();
    }

    public function pageArchive(Request $request,$id){
        if (Gate::denies('default-page-general-info-archive')) {
            abort(403);
        }
        $id = decrypt($id);
        $constant = new Constant();
        $pageArchive= new PageArchive;
        $getPage = MainModel::where('id',$id)->first();
        if($getPage){
            $pageArchive->parent_id=$getPage->parent_id;
            $pageArchive->lang_id=$getPage->lang_id;
            $pageArchive->reference_id=$getPage->reference_id;
            $pageArchive->reference_type=$getPage->reference_type;
            $pageArchive->reviewed_by=$getPage->reviewed_by;
            $pageArchive->written_by=$getPage->written_by;
            $pageArchive->name=$getPage->name;
            $pageArchive->meta_description=$getPage->meta_description;
            $pageArchive->meta_name=$getPage->meta_name;
            $pageArchive->slug=$getPage->slug;
            $pageArchive->descripton=$getPage->descripton;
            $pageArchive->is_description_show=$getPage->is_description_show;
            $pageArchive->keywords=$getPage->keywords;
            $pageArchive->image=$getPage->image;
            $pageArchive->status=$getPage->status;
            $pageArchive->class_name=$getPage->class_name;
            $pageArchive->is_web_show=$getPage->is_web_show;
            $pageArchive->is_app_show=$getPage->is_app_show;
            $pageArchive->hide_image_in_detail=$getPage->hide_image_in_detail;
            $pageArchive->visit_counts=$getPage->visit_counts;
            $pageArchive->home_image=$getPage->home_image;
            $pageArchive->hide_home_image_in_detail=$getPage->hide_home_image_in_detail;
            $pageArchive->speciality_id=$getPage->speciality_id;
            $pageArchive->is_featured=$getPage->is_featured;
            $pageArchive->alt=$getPage->alt;
            $pageArchive->canonical_title=$getPage->canonical_title;
            $pageArchive->canonical_description=$getPage->canonical_description;
            $pageArchive->canonical_link=$getPage->canonical_link;
            $pageArchive->seo_image=$getPage->seo_image;
            $pageArchive->hidden_description=$getPage->hidden_description;
            $pageArchive->page_id=$getPage->id;
            $pageArchive->save();
            $getPage->forceDelete();
            return redirect()->back();
        }
    }

}
