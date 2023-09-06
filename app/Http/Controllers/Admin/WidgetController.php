<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Widget as MainModel;
use Auth;
use Illuminate\Support\Facades\Gate;
use Storage;
use Carbon\Carbon;
use Str;
use Yajra\Datatables\Datatables;
use App\Http\Common\Helper;
use DB;

class WidgetController extends Controller
{
    public $folder_name = 'widget'; // For view routes and file calling and saving
    public $module_name = 'Widgets'; // For toast And page header
    public $input_elements;

    public function input_elements_creator()
    {       $input_elements = array();
            if(Gate::check('widgets-management-add-name'))
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
            if(Gate::check('widgets-management-add-widget-type'))
            {
                $input_element = array_push($input_elements,
                [
                    "label" => "Widget Type",
                    "element_type" => "dropdown",
                    "name" => "type",
                    "options" => [
                        [
                            "id" => "1",
                            "name" => "Page",
                        ],
                        [
                            "id" => "2",
                            "name" => "Article",
                        ],
                        [
                            "id" => "3",
                            "name" => "Both",
                        ],

                    ],
                    "value_element" => "id",
                    "label_element" => "name",
                    "select_element" => "type",
                    "additional_ids" => ["id_1", "id_2"],
                    "additional_classes" => ["class_1", "class_2"],
                    "html_params" => ["required" => "required"],
                ]);
            }
            // [
            //     "element_type" => "input",
            //     "input_type" => "text",
            //     "label" => "Key",
            //     "name" => "key",
            //     "placeholder" => "Enter Key",
            //     "additional_ids" => [],
            //     "additional_classes" => [],
            //     "html_params" => ["required" => "required"],
            // ],
            if(Gate::check('widgets-management-add-web-key'))
            {
                $input_element = array_push($input_elements,
                [
                    "element_type" => "input",
                    "input_type" => "text",
                    "label" => "Web Key",
                    "name" => "web_key",
                    "placeholder" => "Enter Web Key",
                    "additional_ids" => [],
                    "additional_classes" => [],
                    "html_params" => ["required" => "required"],
                ]);
            }
            if(Gate::check('widgets-management-add-mobile-key'))
            {
                    $input_element = array_push($input_elements,
                [
                    "element_type" => "input",
                    "input_type" => "text",
                    "label" => "Mobile Key",
                    "name" => "mobile_key",
                    "placeholder" => "Enter Mobile Key",
                    "additional_ids" => [],
                    "additional_classes" => [],
                    "html_params" => ["required" => "required"],
                ]);
            }
            if(Gate::check('widgets-management-add-web-image'))
            {
                $input_element = array_push($input_elements,
                [
                    "element_type" => "image",
                    "label" => "Web Image",
                    "name" => "web_image",
                    "additional_ids" => [],
                    "additional_classes" => [],
                    "html_params" => [],
                ]);
            }
            if(Gate::check('widgets-management-add-mobile-image'))
            {
                $input_element = array_push($input_elements,
                [
                    "element_type" => "image",
                    "label" => "Mobile Image",
                    "name" => "mobile_image",
                    "additional_ids" => [],
                    "additional_classes" => [],
                    "html_params" => [],
                ]);
        }


        $this->input_elements = $input_elements;
    }



    public function view(Request $request){
        $this->input_elements_creator();
        if (Gate::denies('widgets-management-view')) {
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
            return view($file,$data);
        }
    }

    public function form(Request $request, $id = null){
        $request->validate(MainModel::getValidationRules($id));
        $sequence = MainModel::count();
        $sequence = $sequence + 1;
        $data = [];
        if($request->hasFile('mobile_image')){
            $folder=$this->folder_name;
            $key="mobile_image";
            $data['mobile_image'] = $this->S3UploaderSpecificKey($key,$request,$folder);
        }
        if($request->hasFile('web_image')){
            $folder=$this->folder_name;
            $key="web_image";
            $data['web_image'] = $this->S3UploaderSpecificKey($key,$request,$folder);
        }
        if($id){

            if ($request->has('name')) {
                $data['name']=$request->name;
                // $data=array_merge($data,$data['name']);
            }if ($request->has('web_key')) {
                $data['web_key']=$request->web_key;
            }if ($request->has('mobile_key')) {
                $data['mobile_key']=$request->mobile_key;
            }if ($request->has('type')) {
                $data['type']=$request->type;
            }
            if(MainModel::find($id)->update($data)){
                Helper::toast('success',$this->module_name.' Updated.');
            }
        }else{
            if ($request->has('name')) {
                $data['name']=$request->name;
            }if ($request->has('key')) {
                $data['key']=\Str::slug($request->name);
            }if ($request->has('web_key')) {
                $data['web_key']=$request->web_key;
            }if ($request->has('mobile_key')) {
                $data['mobile_key']=$request->mobile_key;
            }if ($request->has('type')) {
                $data['type']=$request->type;
            }

            if(MainModel::create($data)){
                Helper::toast('success',$this->module_name.' Created.');
            }
        }
        return redirect()->route($this->folder_name.'-view');
    }

    public function add(Request $request){
        $this->input_elements_creator();
        if (Gate::denies('widgets-management-add')) {
            abort(403);
        }
        if($request->isMethod('post')){
            return $this->form($request);
        }else{
            $data['page_header'] = "Add ".$this->module_name;
            $data['result'] = null;
            $input_elements = $this->input_elements;

            if (Gate::denies('widgets-management-status')==false) {
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
        $id = decrypt($id);
        if (Gate::denies('widgets-management-update')) {
            abort(403);
        }
        if($request->isMethod('post')){
            return $this->form($request,$id);
        }else{
            $data['page_header'] = "Edit ".$this->module_name;
            $data['result'] = MainModel::find($id);
            if (Gate::denies('widgets-management-status')==false) {
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
