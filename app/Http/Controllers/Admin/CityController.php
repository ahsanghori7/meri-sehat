<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{City as MainModel, Language};
use App\Http\Common\Helper;
use Illuminate\Support\Facades\Gate;
use Str;
class CityController extends Controller
{
    public function input_elements_creator()
    {
        $input_elements = [

            [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Name",
                "name" => "name",
                "placeholder" => "Enter Title",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ],
            [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Slug",
                "name" => "slug",
                "placeholder" => "Slug",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required", "readonly"=>"true"],
            ],

        ];

        $this->input_elements = $input_elements;
        $this->english_records_name_element = 'name';
        $this->english_records = MainModel::where('lang_id',Language::ENGLISH)->get();
    }

    public $folder_name = 'city'; // For view routes and file calling and saving
    public $module_name = 'City'; // For toast And page header
    public $include_translation_section = 1; // Language and translation section
    public $english_records = [];
    public $input_elements;

    public function view(Request $request){
        $this->input_elements_creator();
        if (Gate::denies('cities-management-view')) {
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
            $data['result'] = MainModel::get();
            $file = "admin.".$this->folder_name.".view";
            // return $data;
            return view($file,$data);
        }
    }

    public function form(Request $request, $id = null){
//        $data = [
//            'lang_id' => $request->lang_id,
//            'name' => $request->name,
//            // 'slug' => Str::slug($request->name,'-'),
//            'status' => $request->status ?? 1,
//        ];
        if($request->has('lang_id')) {
            $data['lang_id'] = $request->lang_id;
        }
        if($request->has('name')) {
            $data['name'] = $request->name;
        }
        if($request->has('status')) {
            $data['status'] = $request->status ?? 1;
        }
        if($request->translation_of != 'parent'){
            $data['translation_of'] = $request->translation_of;
        }
        if($id){
            if(MainModel::find($id)->update($data)){
                Helper::toast('success',$this->module_name.' Updated.');
            }
        }else{
            if($request->lang_id == 1){
                $data['slug'] = Str::slug($request->name,'-');
            }else{
                $parent = MainModel::find($request->translation_of);
                $data['slug'] = $parent->slug;
            }
            if(MainModel::create($data)){
                Helper::toast('success',$this->module_name.' created.');
            }
        }
        return redirect()->route($this->folder_name.'-view');
    }

    public function add(Request $request){
        $this->input_elements_creator();
        if (Gate::denies('cities-management-add')) {
            abort(403);
        }
        if($request->isMethod('post')){
            return $this->form($request);
        }else{
            $data['page_header'] = "Add ".$this->module_name;
            $data['result'] = null;
            $input_elements = $this->input_elements;
            $data['include_translation_section'] = $this->include_translation_section;
            $data['english_records'] = $this->english_records;
            $data['include_status_radio'] = 0;
            $data['include_is_mobile_show_radio'] = 0;
            $data['include_is_web_show_radio'] = 0;
            if (Gate::check('cities-management-add-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check('cities-management-add-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check('cities-management-add-web_show')) {
                $data['include_is_web_show_radio'] = 1;
            }
            $data['english_records_name_element'] = $this->english_records_name_element;
            return view('general_crud.general_view',$data)->with(compact('input_elements'));
        }
    }

    public function edit(Request $request, $id){
        $this->input_elements_creator();
        if (Gate::denies('cities-management-update')) {
            abort(403);
        }
        $id = decrypt($id);
        if($request->isMethod('post')){
            return $this->form($request,$id);
        }else{
            $data['page_header'] = "Edit ".$this->module_name;
            $data['result'] = MainModel::find($id);
            $data['include_status_radio'] = 0;
            $data['include_is_mobile_show_radio'] = 0;
            $data['include_is_web_show_radio'] = 0;
            if (Gate::check('cities-management-add-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check('cities-management-add-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check('cities-management-add-web_show')) {
                $data['include_is_web_show_radio'] = 1;
            }
            $input_elements = $this->input_elements;
            $data['include_translation_section'] = $this->include_translation_section;
            $data['english_records'] = $this->english_records;
            $data['english_records_name_element'] = $this->english_records_name_element;
            return view('general_crud.general_view',$data)->with(compact('input_elements'));
        }
    }
}
