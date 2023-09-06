<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Drug as MainModel, Language, Page, DrugArchive};
use Yajra\Datatables\Datatables;
use App\Http\Common\Helper;
use Illuminate\Support\Facades\Gate;

class DrugController extends Controller
{
    public $folder_name = 'drug'; // For view routes and file calling and saving
    public $module_name = 'Drugs'; // For toast And page header
    public $include_translation_section = 1; // Language and translation section
    public $input_elements;

    public function input_elements_creator()
    {
        $input_elements = array();

        if (Gate::check('drug-page-add-drug-name')) {
            $input_element = array_push($input_elements, [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Name",
                "name" => "name",
                "placeholder" => "Enter Name",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }

        if (Gate::check('drug-page-add-drug-description')) {
            $input_element = array_push($input_elements, [
                "label" => "Description",
                "element_type" => "textarea",
                "name" => "description",
                "editor" => 0,
                "rows" => 5,
                "placeholder" => "Please Enter Description",
                "additional_ids" => ["id_1", "id_2"],
                "additional_classes" => ["class_1", "class_2"],
                "html_params" => ["required" => "required"],
            ]);
        }

        if (Gate::check('drug-page-goto_live')) {
            $input_element = array_push($input_elements, [
                "element_type" => "input",
                "input_type" => "checkbox",
                "label" => "Goto Live",
                "name" => "goto_live",
                "placeholder" => "Goto Live",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => [],
            ]);
        }

        $this->input_elements = $input_elements;
        $this->english_records_name_element = 'name';
        $this->english_records = MainModel::where('lang_id',Language::ENGLISH)->get();
    }

    public function draft(Request $request){
        $this->input_elements_creator();
        if (Gate::denies('drug-page-draft')) {
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
            $data['languages'] = Language::where('status', 1)->get();
            $file = "admin.".$this->folder_name.".draft-view";
            return view($file,$data);
        }
    }

    public function markDraft(Request $request, $id){
        if (Gate::denies('drug-page-draft')) {
            abort(403);
        }
        $id = decrypt($id);
        $getDrug = MainModel::find($id);
        $getDrug->draft = 1;
        $getDrug->status = 0;
        $drug_save = $getDrug->save();
        if (isset($drug_save)) {
            Helper::toast('success',$getDrug->name.' successfully draft.');
        } else {
            Helper::toast('failed',$this->module_name.' not going to draft.');
        }
        return redirect()->route($this->folder_name.'-draft');
    }

    public function view(Request $request){
        $this->input_elements_creator();
        if (Gate::denies('drug-page-view')) {
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
            $data['languages'] = Language::where('status', 1)->get();
            $file = "admin.".$this->folder_name.".view";
            return view($file,$data);
        }
    }

    public function draftFetch(Request $request){
        $query = MainModel::where('draft', true)->with(['language','translationOf']);
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
        $query = MainModel::where('draft', false)->with(['language','translationOf']);
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

    public function gotoLive(Request $request, $id){
        if (Gate::denies('drug-page-goto_live')) {
            abort(403);
        }
        $id = decrypt($id);
        $getDrug = MainModel::find($id);
        $getDrug->draft = 0;
        $getDrug->status = 1;
        $drug_save = $getDrug->save();
        if (isset($drug_save)) {
            Helper::toast('success',$getDrug->name.' successfully live.');
        } else {
            Helper::toast('failed',$this->module_name.' not going to live.');
        }
        return redirect()->route($this->folder_name.'-draft');
    }

    public function DrugArchive(Request $request,$id){
        if (Gate::denies('drug-page-archive')) {
            abort(403);
        }
        $id = decrypt($id);
        $drugArchive= new DrugArchive;
        $getDrug = MainModel::where('id',$id)->first();
        if($getDrug){
            $drugArchive->translation_of=$getDrug->translation_of;
            $drugArchive->lang_id=$getDrug->lang_id;
            $drugArchive->name=$getDrug->name;
            $drugArchive->slug=$getDrug->slug;
            $drugArchive->description=$getDrug->description;
            $drugArchive->status=$getDrug->status;
            $drugArchive->drug_id=$getDrug->id;
            $drugArchive->save();
            $getDrug->forceDelete();
            return redirect()->back();
        }
    }

    public function form(Request $request, $id = null){
//        $request->validate(MainModel::getValidationRules($id));
//        $data = [
//            'name' => $request->name,
//            'description' => $request->description,
//            'status' => $request->status ?? 1,
//            'lang_id' => $request->lang_id,
//        ];
        if ($request->has('name')) {
            $data['name'] = $request->name;
        }
        if ($request->has('description')) {
            $data['description'] = $request->description;
        }
        if ($request->has('status')) {
            $data['status'] = $request->status ?? 1;
        }
        if ($request->has('lang_id')) {
            $data['lang_id'] = $request->lang_id;
        }
        if ($request->translation_of == null) {
            $request->merge(['translation_of' => 'parent']);
        }
        if($request->translation_of != 'parent'){
            $data['translation_of'] = $request->translation_of;
            if(!$id){
                $getParent = MainModel::find($request->translation_of);
                $data['slug'] = $getParent->slug;
            }
        }
        if($request->hasFile('outer_image')){
            $folder=$this->folder_name;
            $key="outer_image";
            $data['outer_image'] = $this->S3UploaderSpecificKey($key,$request,$folder);
        }
        if($request->hasFile('banner_image')){
            $folder=$this->folder_name;
            $key="banner_image";
            $data['banner_image'] = $this->S3UploaderSpecificKey($key,$request,$folder);
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
            if(MainModel::find($id)->update($data)){
                Helper::toast('success',$this->module_name.' Updated.');
            }
        }else{
            $getAllDrugs = Page::where('reference_type', 'drug')->where('lang_id',$request->lang_id)->select('slug')->get();
            if($request->lang_id == 1){
                $data['slug'] = count($getAllDrugs) ? $this->uniqueSlug(\Str::slug($request->name),$getAllDrugs,$id) : \Str::slug($request->name);
            }else{
                $parent = MainModel::find($request->translation_of);
                if (isset($parent->slug)) {
                    $data['slug'] = count($getAllDrugs) ? $this->uniqueSlug(\Str::slug($parent->slug), $getAllDrugs, $id) : \Str::slug($parent->slug);
//                    $data['parent_id'] = $parent->parent_id;
                } else {
                    $data['slug'] = \Str::slug($request->name);
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

    public function add(Request $request){
        $this->input_elements_creator();
//        Gate::authorize('drug-page-add');
        if (Gate::denies('drug-page-add')) {
            abort(403);
        }
        if($request->isMethod('post')){
            return $this->form($request);
        }else{
            $data['page_header'] = "Add ".$this->module_name;
            $data['result'] = null;
            $data['include_translation_section'] = $this->include_translation_section;
            $data['english_records'] = $this->english_records;
            $data['english_records_name_element'] = $this->english_records_name_element;
            $input_elements = $this->input_elements;
            $data['include_status_radio'] = 0;
            $data['include_is_mobile_show_radio'] = 0;
            $data['include_is_web_show_radio'] = 0;
            if (Gate::check('drug-page-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check('drug-page-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check('drug-page-web_show')) {
                $data['include_is_web_show_radio'] = 1;
            }
            return view('general_crud.general_view',$data)->with(compact('input_elements'));
        }
    }

    public function edit(Request $request, $id){
        $this->input_elements_creator();
        if (Gate::denies('drug-page-update')) {
            abort(403);
        }
        $id = decrypt($id);
        if($request->isMethod('post')){
            Gate::authorize('drug-page-update');
            return $this->form($request,$id);
        }else{
            Gate::authorize('drug-page-view');
            $data['page_header'] = "Edit ".$this->module_name;
            $data['result'] = MainModel::find($id);
            $data['include_translation_section'] = $this->include_translation_section;
            $data['english_records'] = $this->english_records;
            $data['english_records_name_element'] = $this->english_records_name_element;
            $data['include_status_radio'] = 0;
            $data['include_is_mobile_show_radio'] = 0;
            $data['include_is_web_show_radio'] = 0;
            if (Gate::check('drug-page-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check('drug-page-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check('drug-page-web_show')) {
                $data['include_is_web_show_radio'] = 1;
            }
            $input_elements = $this->input_elements;
            return view('general_crud.general_view',$data)->with(compact('input_elements'));
        }
    }

    public function linkWithPage(Request $request, $id)
    {
        Gate::authorize('drug-page-page-layout');
        $id = decrypt($id);
        $getPageDetail = Page::where(['reference_id'=> $id, 'reference_type'=> 'drug'])->first();
        if(!$getPageDetail){
            $getDrugDetail = MainModel::find($id);
            $getPageDetail = Page::create([
                'lang_id' => $getDrugDetail->lang_id,
                'reference_type' => 'drug',
                'reference_id' => $id,
                'name' => $getDrugDetail->name,
                'slug' => $getDrugDetail->slug,
                'descripton' => $getDrugDetail->description,
            ]);
        }
        return redirect('admin/page/edit/' . encrypt($getPageDetail->id));
    }

}
