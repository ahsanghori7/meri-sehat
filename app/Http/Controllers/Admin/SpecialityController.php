<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\{Speciality as MainModel, Language};
use Yajra\Datatables\Datatables;
use App\Http\Common\Helper;

class SpecialityController extends Controller
{

    public $folder_name = 'speciality'; // For view routes and file calling and saving
    public $module_name = 'Speciality'; // For toast And page header
    public $include_translation_section = 1; // Language and translation section
    public $input_elements;
    public function input_elements_creator()
    {
        $input_elements = array();
        if(Gate::check('speciality-management-add-name'))
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
        if(Gate::check('speciality-management-add-type'))
        {
            $input_element = array_push($input_elements,
            [
                "label" => "Type",
                "element_type" => "dropdown",
                "name" => "type",
                "options" => [
                    [
                        "type" => "doctor",
                        "name" => "Doctor",
                    ],
                    [
                        "type" => "wellness-experts",
                        "name" => "Wellness Experts",
                    ],
                ],
                "value_element" => "type",
                "select_element" => "type",
                "label_element" => "name",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }
        if(Gate::check('speciality-management-add-image'))
        {
            $input_element = array_push($input_elements,
            [
                "element_type" => "image",
                "label" => "Image",
                "name" => "image",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => [],
            ]);
        }


        $this->input_elements = $input_elements;
        $this->english_records_name_element = 'name';
        $this->english_records = MainModel::where('lang_id', Language::ENGLISH)->get();
    }

    public function view(Request $request){
        $this->input_elements_creator();
        if (Gate::denies('speciality-management-view')) {
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
        $query = MainModel::with(['language','translationOf']);
        if(isset($request->status)) {
            $query = $query->where('status', $request->status);
        }
        if(isset($request->language)) {
            $query = $query->where('lang_id', $request->language);
        }
        return DataTables::of($query)->make(true);
    }

    public function form(Request $request, $id = null){
        if (Gate::denies('speciality-management-add')) {
            abort(403);
        }
        $request->validate(MainModel::getValidationRules($id));
        $sequence = MainModel::count();
        $sequence = $sequence + 1;

        if ($request->has('name')) {
            $data['name']=$request->name;
        }if ($request->has('slug')) {
            $data['slug']=$request->lang_id == Language::ENGLISH ? \Str::slug($request->name) : MainModel::find($request->translation_of)->slug;
        }if ($request->has('lang_id')) {
            $data['lang_id']=$request->lang_id;
        }if ($request->has('type')) {
            $data['type']=$request->type;
        }if ($request->has('status')) {
            $data['status']=$request->status ? $request->status : 0;
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

    public function toggleStatus(Request $request){
        $result = MainModel::find($request->id);
        $result->status = $request->val;
        // $result->update(['status' => $status]);
        $result->save();
    }

    public function add(Request $request){
        $this->input_elements_creator();
        if (Gate::denies('speciality-management-add')) {
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
            if (Gate::denies('speciality-management-add-status')==false) {
                $data['include_status_radio'] = 1;
            }
            else{
                $data['include_status_radio'] = 0;
            }
            $data['include_is_mobile_show_radio'] = 1;
            $data['include_is_web_show_radio'] = 1;
            return view('general_crud.general_view',$data)->with(compact('input_elements'));
        }
    }

    public function edit(Request $request, $id){
        $this->input_elements_creator();
        if (Gate::denies('speciality-management-update')) {
            abort(403);
        }
        $id = decrypt($id);
        if($request->isMethod('post')){
            return $this->form($request,$id);
        }else{
            $data['include_translation_section'] = $this->include_translation_section;
            $data['english_records'] = $this->english_records;
            $data['english_records_name_element'] = $this->english_records_name_element;
            $data['page_header'] = "Edit ".$this->module_name;
            $data['result'] = MainModel::find($id);
            if (Gate::denies('speciality-management-add-status')==false) {
                $data['include_status_radio'] = 1;
            }
            else{
                $data['include_status_radio'] = 0;
            }
            $data['include_is_mobile_show_radio'] = 1;
            $data['include_is_web_show_radio'] = 1;
            $input_elements = $this->input_elements;
            return view('general_crud.general_view',$data)->with(compact('input_elements'));
        }
    }

}
