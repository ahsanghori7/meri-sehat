<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service as MainModel;
use App\Models\Speciality;
use App\Models\Language;
use Auth;
use Illuminate\Support\Facades\Gate;
use Storage;
use Carbon\Carbon;
use Str;
use Yajra\Datatables\Datatables;
use App\Http\Common\Helper;
use DB;

class ServiceController extends Controller
{
    public $folder_name = 'service'; // For view routes and file calling and saving
    public $module_name = 'Service'; // For toast And page header
    public $include_translation_section = 1; // Language and translation section
    public $english_records = [];
    public $input_elements;
    public function input_elements_creator()
    {

        $input_elements = array();
        if(Gate::check('services-management-add-select-language'))
        {
            $input_element = array_push($input_elements, [
                "label" => " Speciality",
                "element_type" => "dropdown",
                "name" => "speciality_id",
                "options" => Speciality::where(['status' => true])->orderBy('name', 'asc')->get(),
                "value_element" => "id",
                "select_element" => "speciality_id",
                "label_element" => "name",
                "additional_ids" => ["id_1", "id_2"],
                "additional_classes" => ["class_1", "class_2"],
                "html_params" => ["required" => "required"],
            ]);
        }
        if(Gate::check('services-management-add-name'))
        {
            $input_element = array_push($input_elements,
            [
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


        $this->input_elements = $input_elements;
        $this->english_records = MainModel::where(['status' => true, 'lang_id' => Language::ENGLISH])->orderBy('name', 'asc')->get();
        $this->english_records_name_element = 'name';
    }


    public function view(Request $request){
        $this->input_elements_creator();
        if (Gate::denies('services-management-view')) {
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
            $data['languages'] = Language::where('status', 1)->get();
            $file = "admin.".$this->folder_name.".view";
            return view($file,$data);
        }
    }

    public function fetch(Request $request){
        $query = MainModel::orderBy('name', 'asc')->with(['language','translationOf']);
        if(isset($request->status)) {
            $query = $query->where('status', $request->status);
        }
        if(isset($request->language)) {
            $query = $query->where('lang_id', $request->language);
        }
        return DataTables::of($query)->make(true);
    }

    public function form(Request $request, $id = null){
        if (Gate::denies('services-management-add')) {
            abort(403);
        }
        $request->validate(MainModel::getValidationRules($id));
        $sequence = MainModel::count();
        $sequence = $sequence + 1;
        if ($request->has('name')) {
            $data['name']=$request->name;
        }
        if ($request->has('lang_id')) {
            $data['lang_id']=$request->lang_id;
        }
        if ($request->has('status')) {
            $data['status']=$request->status ? $request->status : 1;
        }
//        if ($request->has('action_by')) {
            $data['action_by']=Auth::user()->id;
//        }
        if($request->translation_of != 'parent'){
            if($request->translation_of){
                $data['speciality_id'] = MainModel::find($request->translation_of)->speciality_id;
                $data['translation_of'] = $request->translation_of;
            }else{
                $data['speciality_id'] = $request->speciality_id;
            }
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
        if (Gate::denies('services-management-add')) {
            abort(403);
        }
        if($request->isMethod('post')){
            return $this->form($request);
        }else{
            $data['page_header'] = "Add ".$this->module_name;
            $data['result'] = null;
            $input_elements = $this->input_elements;
            if (Gate::denies('services-management-add-status')==false) {
                $data['include_status_radio'] = 1;
            }
            else{
                $data['include_status_radio'] = 0;
            }
            $data['include_is_mobile_show_radio'] = 1;
            $data['include_is_web_show_radio'] = 1;
            $data['include_translation_section'] = $this->include_translation_section;
            $data['english_records'] = $this->english_records;
            $data['english_records_name_element'] = $this->english_records_name_element;
            return view('general_crud.general_view',$data)->with(compact('input_elements'));
        }
    }

    public function edit(Request $request, $id){
        $this->input_elements_creator();
        if (Gate::denies('services-management-update')) {
            abort(403);
        }
        $id = decrypt($id);
        if($request->isMethod('post')){
            return $this->form($request,$id);
        }else{
            $data['page_header'] = "Edit ".$this->module_name;
            $data['result'] = MainModel::find($id);
            if (Gate::denies('services-management-add-status')==false) {
                $data['include_status_radio'] = 1;
            }
            else{
                $data['include_status_radio'] = 0;
            }
            $data['include_is_mobile_show_radio'] = 1;
            $data['include_is_web_show_radio'] = 1;
            $data['include_translation_section'] = $this->include_translation_section;
            $data['english_records'] = $this->english_records;
            $data['english_records_name_element'] = $this->english_records_name_element;
            $input_elements = $this->input_elements;
            return view('general_crud.general_view',$data)->with(compact('input_elements'));
        }
    }

}
