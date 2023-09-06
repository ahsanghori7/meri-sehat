<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Disease as MainModel;
use App\Models\Language;
use App\Models\Page;
use Auth;
use App\Models\DiseaseArchive;
use Storage;
use Carbon\Carbon;
use Str;
use Yajra\Datatables\Datatables;
use App\Http\Common\Helper;
use DB;
use Session;
use Illuminate\Support\Facades\Gate;

class DiseaseController extends Controller
{
    public $folder_name = 'disease'; // For view routes and file calling and saving
    public $module_name = 'Diseases'; // For toast And page header
    public $include_translation_section = 1; // Language and translation section
    public $input_elements;

    public function input_elements_creator()
    {
        $input_elements = array();

        if (Gate::check('disease-page-add-disease-name')) {
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

        if (Gate::check('disease-page-add-disease-description')) {
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

        if (Gate::check('disease-page-goto_live')) {
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

    public function markDraft(Request $request, $id){
        if (Gate::denies('disease-page-draft')) {
            abort(403);
        }
        $id = decrypt($id);
        $getDisease = MainModel::find($id);
        $getDisease->draft = 1;
        $getDisease->status = 0;
        $disease_save = $getDisease->save();
        if (isset($disease_save)) {
            Helper::toast('success',$getDisease->name.' successfully draft.');
        } else {
            Helper::toast('failed',$this->module_name.' not going to draft.');
        }
        return redirect()->route($this->folder_name.'-draft');
    }

    public function draft(Request $request){
        $this->input_elements_creator();
        if (Gate::denies('disease-page-draft')) {
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

    public function view(Request $request){
        $this->input_elements_creator();
        if (Gate::denies('disease-page-view')) {
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

    public function gotoLive(Request $request, $id){
        if (Gate::denies('disease-page-goto_live')) {
            abort(403);
        }
        $id = decrypt($id);
        $getDisease = MainModel::find($id);
        $getDisease->draft = 0;
        $getDisease->status = 1;
        $disease_save = $getDisease->save();
        if (isset($disease_save)) {
            Helper::toast('success',$getDisease->name.' successfully live.');
        } else {
            Helper::toast('failed',$this->module_name.' not going to live.');
        }
        return redirect()->route($this->folder_name.'-draft');
    }

    public function diseaseArchive(Request $request,$id){
        if (Gate::denies('disease-page-archive')) {
            abort(403);
        }
        $id = decrypt($id);
        $diseaseArchive= new DiseaseArchive;
        $getDisease = MainModel::where('id',$id)->first();
        if($getDisease){
          $diseaseArchive->translation_of =  $getDisease->translation_of;
          $diseaseArchive->lang_id =  $getDisease->lang_id;
          $diseaseArchive->name =  $getDisease->name;
          $diseaseArchive->slug =  $getDisease->slug;
          $diseaseArchive->description =  $getDisease->description;
          $diseaseArchive->status =  $getDisease->status;
          $diseaseArchive->visit_counts =  $getDisease->visit_counts;
          $diseaseArchive->approved_by =  $getDisease->approved_by;
          $diseaseArchive->written_by =  $getDisease->written_by;
          $diseaseArchive->speciality_id =  $getDisease->speciality_id;
          $diseaseArchive->disease_id =  $getDisease->id;
          $diseaseArchive->save();
          $getDisease->forceDelete();
          return redirect()->back();
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
        if($request->translation_of != 'parent'){
            $data['translation_of'] = $request->translation_of;
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
            $getAllDiseases = Page::where('reference_type', 'disease')->where('lang_id',$request->lang_id)->select('slug')->get();
            if($request->lang_id == 1){
                $data['slug'] = count($getAllDiseases) ? $this->uniqueSlug(\Str::slug($request->name),$getAllDiseases,$id) : \Str::slug($request->name);
            }else{
                $parent = MainModel::find($request->translation_of);
                if (isset($parent->slug)) {
                    $slug_random = mt_rand(1,10000);
                    $data['slug']=$parent->slug.'-'.$slug_random;
                    //$data['slug'] = count($getAllDiseases) ? $this->uniqueSlug(\Str::slug($parent->slug), $getAllDiseases, $id) : \Str::slug($parent->slug);
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
//        Gate::authorize('disease-page-add');
        if (Gate::denies('disease-page-add')) {
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
            if (Gate::check('disease-page-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check('disease-page-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check('disease-page-web_show')) {
                $data['include_is_web_show_radio'] = 1;
            }
            return view('general_crud.general_view',$data)->with(compact('input_elements'));
        }
    }

    public function edit(Request $request, $id){
        $this->input_elements_creator();
//        Gate::authorize('disease-page-update');
        if (Gate::denies('disease-page-update')) {
            abort(403);
        }
        $id = decrypt($id);
        if($request->isMethod('post')){
            return $this->form($request,$id);
        }else{
            Gate::authorize('disease-page-view');
            $data['page_header'] = "Edit ".$this->module_name;
            $data['result'] = MainModel::find($id);
            $data['include_translation_section'] = $this->include_translation_section;
            $data['english_records'] = $this->english_records;
            $data['english_records_name_element'] = $this->english_records_name_element;
            $data['include_status_radio'] = 0;
            $data['include_is_mobile_show_radio'] = 0;
            $data['include_is_web_show_radio'] = 0;
            if (Gate::check('disease-page-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check('disease-page-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check('disease-page-web_show')) {
                $data['include_is_web_show_radio'] = 1;
            }
            $input_elements = $this->input_elements;
            return view('general_crud.general_view',$data)->with(compact('input_elements'));
        }
    }

    public function linkWithPage(Request $request, $id)
    {
        Gate::authorize('disease-page-page-layout');
        $id = decrypt($id);
        $getPageDetail = Page::where(['reference_id'=> $id, 'reference_type'=> 'disease'])->first();
        if(!$getPageDetail){
            $getDiseaseDetail = MainModel::find($id);
            $getPageDetail = Page::create([
                'lang_id' => $getDiseaseDetail->lang_id,
                'reference_type' => 'disease',
                'reference_id' => $id,
                'name' => $getDiseaseDetail->name,
                'slug' => $getDiseaseDetail->slug,
                'descripton' => $getDiseaseDetail->description,
            ]);
        }
        return redirect('admin/page/edit/' . encrypt($getPageDetail->id));
    }

}
