<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language as MainModel;
use Auth;
use Illuminate\Support\Facades\Gate;
use Storage;
use Carbon\Carbon;
use Str;
use Yajra\Datatables\Datatables;
use App\Http\Common\Helper;
use DB;

class LanguageController extends Controller
{
    public $folder_name = 'language'; // For view routes and file calling and saving
    public $module_name = 'Language';
    public $input_elements;

    public function input_elements_creator()
    {
        $input_elements = array();
        if(Gate::check('language-management-add-language'))
        {
            $input_element = array_push($input_elements,
            [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Language",
                "name" => "name",
                "placeholder" => "Enter Language",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }
        if(Gate::check('language-management-add-short-name'))
        {
            $input_element = array_push($input_elements,
            [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Short Name",
                "name" => "slug",
                "placeholder" => "Eg: (en/ur/es)",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }

        if(Gate::check('language-management-add-direction-dropdown'))
            {
            $input_element = array_push($input_elements,
            [
                "label" => "Direction",
                "element_type" => "dropdown",
                "name" => "direction",
                "options" => [
                    [
                        "direction" => "ltr",
                        "name" => "Left to right",
                    ],
                    [
                        "direction" => "rtl",
                        "name" => "Right to left",
                    ],

                ],
                "value_element" => "direction",
                "label_element" => "name",
                "additional_ids" => ["id_1", "id_2"],
                "additional_classes" => ["class_1", "class_2"],
                "html_params" => ["required" => "required"],
            ]);
        }


        $this->input_elements = $input_elements;
    }


    public function view(Request $request){
        $this->input_elements_creator();
        if (Gate::denies('language-management-view')) {
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
        if (Gate::denies('language-management-add')) {
            abort(403);
        }
        $request->validate(MainModel::getValidationRules($id));
        $sequence = MainModel::count();
        $sequence = $sequence + 1;

        if ($request->has('name')) {
            $data['name']=$request->name;
        }if ($request->has('slug')) {
            $data['slug']=$request->slug;
        }if ($request->has('direction')) {
            $data['direction']=$request->direction;
        }if ($request->has('status')) {
            $data['status']=$request->status;
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
        if (Gate::denies('language-management-add')) {
            abort(403);
        }
        if($request->isMethod('post')){
            return $this->form($request);
        }else{
            $data['page_header'] = "Add ".$this->module_name;
            $data['result'] = null;
            $input_elements = $this->input_elements;
            if (Gate::denies('language-management-add-status')==false) {
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
        if (Gate::denies('language-management-update')) {
            abort(403);
        }
        $id = decrypt($id);
        if($request->isMethod('post')){
            return $this->form($request,$id);
        }else{
            $data['page_header'] = "Edit ".$this->module_name;
            $data['result'] = MainModel::find($id);

            if (Gate::denies('language-management-add-status')==false) {
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
