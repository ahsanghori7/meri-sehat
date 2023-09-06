<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FaqCategory;
use Illuminate\Http\Request;
use App\Models\FaqCategory as MainModel;
use Auth;
use Storage;
use Carbon\Carbon;
use Str;
use Yajra\Datatables\Datatables;
use App\Http\Common\Helper;
use App\Models\Language;
use DB;
use Illuminate\Support\Facades\Gate;

class FaqCategoryController extends Controller
{
    public function input_elements_creator()
    {
        $input_elements = array();

        if (Gate::check('faq-category-category_name')) {
            $input_element = array_push($input_elements, [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Category Name",
                "name" => "name",
                "placeholder" => "Enter Category Name",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }

        $this->input_elements = $input_elements;
        $this->english_records_name_element = 'name';
        $this->english_records = MainModel::where('lang_id', Language::ENGLISH)->get();
    }

    public $folder_name = 'faq-category'; // For view routes and file calling and saving
    public $module_name = 'Faq Categories'; // For toast And page header
    public $include_translation_section = 1; // Language and translation section
    public $input_elements;
    public $english_records_name_element;
    public $english_records;

    public function view(Request $request){
        $this->input_elements_creator();
        if (Gate::denies('faq-category-view')) {
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
            $data['result'] = MainModel::all();
            $file = "admin.".$this->folder_name.".view";
            return view($file,$data);
        }
    }

    public function form(Request $request, $id = null){
        $request->validate(MainModel::getValidationRules($id));
        $sequence = MainModel::count();
        $sequence = $sequence + 1;
//        $data = [
//            'name' => $request->name,
//            'status' => $request->status ?? 1,
//            'lang_id' => $request->lang_id,
//        ];
        if ($request->has('name')) {
            $data['name'] = $request->name;
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
        if($request->hasFile('image')){
            $folder=$this->folder_name;
            $key="image";
            $data['image'] = $this->S3UploaderSpecificKey($key,$request,$folder);
        }
        if($id){
            if(MainModel::find($id)->update($data)){
                Helper::toast('success',$this->module_name.' Updated.');
            }
        }else{
            if(MainModel::create($data)){
                Helper::toast('success',$this->module_name.' created.');
            }
        }
        return redirect()->route($this->folder_name.'-view');
    }

    public function add(Request $request){
        $this->input_elements_creator();
        if (Gate::denies('faq-category-add')) {
            abort(403);
        }
//        Gate::authorize('drug-page-add');
        if($request->isMethod('post')){
            return $this->form($request);
        }else{
            $data['include_translation_section'] = $this->include_translation_section;
            $data['english_records'] = $this->english_records;
            $data['english_records_name_element'] = $this->english_records_name_element;
            $data['page_header'] = "Add ".$this->module_name;
            $data['result'] = null;
            $input_elements = $this->input_elements;
            $data['include_status_radio'] = 0;
            $data['include_is_mobile_show_radio'] = 0;
            $data['include_is_web_show_radio'] = 0;
            if (Gate::check('faq-category-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check('faq-category-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check('faq-category-web_show')) {
                $data['include_is_web_show_radio'] = 1;
            }
            return view('general_crud.general_view',$data)->with(compact('input_elements'));
        }
    }

    public function edit(Request $request, $id){
        $this->input_elements_creator();
        if (Gate::denies('faq-category-update')) {
            abort(403);
        }
        $id = decrypt($id);
        if($request->isMethod('post')){
//            Gate::authorize('faq-category-update');
            return $this->form($request,$id);
        }else{
//            Gate::authorize('faq-category-view');
            $data['include_translation_section'] = $this->include_translation_section;
            $data['english_records'] = $this->english_records;
            $data['english_records_name_element'] = $this->english_records_name_element;
            $data['page_header'] = "Edit ".$this->module_name;
            $data['result'] = MainModel::find($id);
            $data['include_status_radio'] = 0;
            $data['include_is_mobile_show_radio'] = 0;
            $data['include_is_web_show_radio'] = 0;
            if (Gate::check('faq-category-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check('faq-category-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check('faq-category-web_show')) {
                $data['include_is_web_show_radio'] = 1;
            }
            $input_elements = $this->input_elements;
            return view('general_crud.general_view',$data)->with(compact('input_elements'));
        }
    }

}
