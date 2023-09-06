<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InfoModal as MainModel;
use App\Models\Language;
use App\Http\Common\Helper;
use Illuminate\Support\Facades\Gate;

class InfoModalController extends Controller
{
    public $folder_name = 'info-model'; // For view routes and file calling and saving
    public $module_name = 'Info Model'; // For toast And page header
    public $include_translation_section = 1; // Language and translation section
    public $english_records = [];
    public $input_elements;

    public function input_elements_creator()
    {
        $input_elements = array();

        if (Gate::check('info-modal-management-add-key')) {
            $input_element = array_push($input_elements, [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Key",
                "name" => "key",
                "placeholder" => "Enter Key",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }

        if (Gate::check('info-modal-management-add-title')) {
            $input_element = array_push($input_elements, [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Title",
                "name" => "title",
                "placeholder" => "title",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }

        if (Gate::check('info-modal-management-add-content')) {
            $input_element = array_push($input_elements, [
                "label" => "Content",
                "element_type" => "textarea",
                "name" => "content",
                "editor" => 1,
                "rows" => 5,
                "placeholder" => "Please Enter Content",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }

        $this->input_elements = $input_elements;
        $this->english_records_name_element = 'title';
        $this->english_records = MainModel::where('lang_id',Language::ENGLISH)->get();
    }

    public function view(Request $request){
        $this->input_elements_creator();
        if (Gate::denies('info-modal-management-view')) {
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
//            'title' => $request->title,
//            'translation_of' => $request->lang_id == 1 ? null : $request->translation_of,
//            'lang_id' => $request->lang_id,
//            'key' => \Str::slug($request->key),
//            'content' => $request->content,
//            'status' => $request->status ?? 1,
//        ];
        // return $data;
        if($request->has('title')){
            $data['title'] = $request->title;
        }
        if($request->has('translation_of')){
            $data['translation_of'] = $request->lang_id == 1 ? null : $request->translation_of;
        }
        if($request->has('lang_id')){
            $data['lang_id'] = $request->lang_id;
        }
        if($request->has('key')){
//            $data['key'] = \Str::slug($request->key);
            $data['key'] = $request->key;
        }
        if($request->has('content')){
            $data['content'] = $request->content;
        }
        if($request->has('status')){
            $data['status'] = $request->status ?? 1;
        }
        if($request->hasFile('image')){
            $folder=$this->folder_name;
            $key='image';
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
        if (Gate::denies('info-modal-management-add')) {
            abort(403);
        }
        if($request->isMethod('post')){
            return $this->form($request);
        }else{
            $data['page_header'] = "Add ".$this->module_name;
            $data['result'] = null;
            $input_elements = $this->input_elements;
            $data['include_status_radio'] = 0;
            $data['include_is_mobile_show_radio'] = 0;
            $data['include_is_web_show_radio'] = 0;
            if (Gate::check('info-modal-management-add-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check('info-modal-management-add-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check('info-modal-management-add-web_show')) {
                $data['include_is_web_show_radio'] = 1;
            }
            $data['include_translation_section'] = $this->include_translation_section;
            $data['english_records'] = $this->english_records;
            $data['english_records_name_element'] = $this->english_records_name_element;
            return view('general_crud.general_view',$data)->with(compact('input_elements'));
        }
    }

    public function edit(Request $request, $id){
        $this->input_elements_creator();
        if (Gate::denies('info-modal-management-update')) {
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
            if (Gate::check('info-modal-management-add-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check('info-modal-management-add-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check('info-modal-management-add-web_show')) {
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
