<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ads as MainModel;
use App\Models\AdsWindow;
use App\Http\Common\Helper;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class AdController extends Controller
{
    public function input_elements_creator()
    {
        $options = [];
        $ad_windowws = AdsWindow::where('status', 1)->get();
        if(isset($ad_windowws) && count($ad_windowws) > 0){
            foreach ($ad_windowws as $key => $ad_windoww) {
                $options[] = [
                    "id" => $ad_windoww->id,
                    "name" => $ad_windoww->name ." > ". $ad_windoww->dimensions,
                ];
            }
        }

        $input_elements = array();

        if (Gate::check('ads-management-add-ad_window_id')) {
            $input_element = array_push($input_elements, [
                "label" => "Select Ad Window",
                "element_type" => "dropdown",
                "name" => "ad_window_id",
                "options" => $options,
                "value_element" => "id",
                "select_element" => "ad_window_id",
                "label_element" => "name",
                "additional_ids" => ["id_1", "id_2"],
                "additional_classes" => ["class_1", "class_2"],
                "html_params" => ["required" => "required"],
            ]);
        }
        if (Gate::check('ads-management-add-name')) {
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
        if (Gate::check('ads-management-add-redirect_url')) {
            $input_element = array_push($input_elements, [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Redirect URL",
                "name" => "redirect_url",
                "placeholder" => "Enter Redirect URL",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }
        if (Gate::check('ads-management-add-image')) {
            $input_element = array_push($input_elements, [
                "element_type" => "image",
                "label" => "Image",
                "name" => "source",
                "include_asset_function" => 0,
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ['accept' => '.png,.jpg,.gif'],
            ]);
        }

        $this->input_elements = $input_elements;
    }

    public $folder_name = 'ads'; // For view routes and file calling and saving
    public $module_name = 'Advertisement'; // For toast And page header
    public $input_elements;

    public function view(Request $request){
        if (Gate::denies('ads-management-view')) {
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
        $validator = Validator::make($request->all(), [
            'source' => ['required', 'mimes:png,jpg,PNG,JPG,image/gif,gif'],
        ]);
        if ($validator->fails()) {
            Helper::toast('error', 'Invalid image / source type.');
            return redirect()->back();
        }
        $request->validate(MainModel::getValidationRules($id));
        $sequence = MainModel::count();
        $sequence = $sequence + 1;
        if($request->has('name')){
            $data['name'] = $request->name;
        }
        if($request->has('ad_window_id')){
            $data['ad_window_id'] = $request->ad_window_id;
        }
        if($request->has('redirect_url')){
            $data['redirect_url'] = $request->redirect_url;
        }
        $data['source_type'] = "image";
        if($request->hasFile('source')){
            $folder="ads";
            $key='source';
            $data['source'] = $this->S3UploaderSpecificKey($key,$request,$folder);
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
        if (Gate::denies('ads-management-add')) {
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
            if (Gate::check('ads-management-add-status')) {
                $data['include_status_radio'] = 1;
            }
            return view('general_crud.general_view',$data)->with(compact('input_elements'));
        }
    }

    public function edit(Request $request, $id){
        $this->input_elements_creator();
        $id = decrypt($id);
        if (Gate::denies('ads-management-update')) {
            abort(403);
        }
        if($request->isMethod('post')){
            return $this->form($request,$id);
        }else{
            $data['page_header'] = "Edit ".$this->module_name;
            $data['result'] = MainModel::find($id);
            $input_elements = $this->input_elements;
            $data['include_status_radio'] = 0;
            $data['include_is_mobile_show_radio'] = 0;
            $data['include_is_web_show_radio'] = 0;
            if (Gate::check('ads-management-add-status')) {
                $data['include_status_radio'] = 1;
            }
            return view('general_crud.general_view',$data)->with(compact('input_elements'));
        }
    }

}
