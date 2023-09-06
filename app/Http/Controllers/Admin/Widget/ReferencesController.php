<?php

namespace App\Http\Controllers\Admin\Widget;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\{ReferenceWidget, WidgetReference as MainModel};
use App\Http\Common\Helper;

class ReferencesController extends Controller
{
    public $folder_name = 'references'; // For view routes and file calling and saving
    public $module_name = 'Reference'; // For toast And page header
    public $module_slug; // check module slug
    public $input_elements;

    public function __construct(array $attributes = array())
    {
        $this->module_slug = Helper::module_chk();
    }

    public function input_elements_creator($reference = null) {
        $input_elements = array();
        $reference_id = null;
        $current_uri = request()->segments();

        if (isset($current_uri[4]) && is_numeric($current_uri[4])) {
            $reference_id = $current_uri[4];
        }

        if ($reference) {
            $this->module_slug = Helper::module_chk($reference);
        }

        if (Gate::check($this->module_slug.'-widgets-references-name') || Gate::check($this->module_slug.'-widgets-references-custom-name')) {
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

        if (Gate::check($this->module_slug.'-widgets-references-url') || Gate::check($this->module_slug.'-widgets-references-custom-url')) {
            $input_element = array_push($input_elements, [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "URL",
                "name" => "url",
                "placeholder" => "URL",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => [],
            ]);
        }

        $this->input_elements = $input_elements;
        return;
    }

    public function index(Request $request,$reference_id){
        if($request->isMethod('post')){
//            dd($request->sequence);
            if($request->sequence){
                foreach($request->sequence as $key => $id){
                    $sequence = $key + 1;
                    MainModel::find($id)->update(['sequence' => str_pad($sequence,2,"0",STR_PAD_LEFT)]);
                }
            }
            $this->updateGeneralWidgetData($request, $reference_id);
            return back();
        }else{
            $data['reference_id'] = $reference_id;
            $data['reference_widget']= ReferenceWidget::where('id',$reference_id)->first();
            $this->module_slug = Helper::module_chk($reference_id);
            $data['folder_name'] = $this->folder_name;
            $data['module_slug'] = $this->module_slug;
            $data['module_name'] = $this->module_name;
            $data['include_status_radio'] = 0;
            $data['include_is_mobile_show_radio'] = 0;
            $data['include_is_web_show_radio'] = 0;
            if (Gate::check($this->module_slug.'-widgets-references-status') || Gate::check($this->module_slug.'-widgets-references-custom-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check($this->module_slug.'-widgets-references-mobile_show') || Gate::check($this->module_slug.'-widgets-references-custom-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check($this->module_slug.'-widgets-references-web_show') || Gate::check($this->module_slug.'-widgets-references-custom-web_show')) {
                $data['include_is_web_show_radio'] = 1;
            }
            $data['result'] = MainModel::where('reference_id',$reference_id)
            ->orderByRaw("CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(name, ']', 1), '[', -1) AS UNSIGNED) ASC")
            ->get();
            $file = "admin.widget.".$this->folder_name.".view";
            return view($file,$data);
        }
    }

    public function form(Request $request,$reference_id, $id = null){

        $sequence = MainModel::where('reference_id',$reference_id)->count();
        $sequence++;
        if ($reference_id) {
            $data['reference_id'] = $reference_id;
        }
        if ($request->has('name')) {
            $data['name'] = $request->name;
        }
        if ($request->has('url')) {
            $data['url'] = $request->url;
        }
        if ($sequence) {
            $data['sequence'] = $sequence;
        }
        if ($request->has('status')) {
            $data['status'] = $request->status ?? 1;
        }
        if ($request->has('is_mobile_show')) {
            $data['is_mobile_show'] = $request->is_mobile_show ?? 1;
        }
        if ($request->has('is_web_show')) {
            $data['is_web_show'] = $request->is_web_show ?? 1;
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
        return redirect()->route('widget-'.$this->folder_name.'-index', ['reference_id' => $reference_id]);
    }

    public function delete(Request $request,$reference_id, $id)
    {
        $this->input_elements_creator($reference_id);
        $id = decrypt($id);
        if ($id) {
            if (MainModel::find($id)->delete()) {
                Helper::toast('success', $this->module_name . ' Deleted.');
            }
        }
        return redirect()->route('widget-'.$this->folder_name.'-index', ['reference_id' => $reference_id]);
    }

    public function add(Request $request,$reference_id){
        $this->input_elements_creator($reference_id);
        if($request->isMethod('post')){
            return $this->form($request,$reference_id);
        }else{
            $data['page_header'] = "Add ".$this->module_name;
            $data['result'] = null;
            $data['reference_id'] = $reference_id;
            $this->module_slug = Helper::module_chk($reference_id);
            $data['module_slug'] = $this->module_slug;
            $data['folder_name'] = $this->folder_name;
            $input_elements = $this->input_elements;
            $data['include_status_radio'] = 0;
            $data['include_is_mobile_show_radio'] = 0;
            $data['include_is_web_show_radio'] = 0;
            if (Gate::check($this->module_slug.'-widgets-references-status') || Gate::check($this->module_slug.'-widgets-references-custom-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check($this->module_slug.'-widgets-references-mobile_show') || Gate::check($this->module_slug.'-widgets-references-custom-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check($this->module_slug.'-widgets-references-web_show') || Gate::check($this->module_slug.'-widgets-references-custom-web_show')) {
                $data['include_is_web_show_radio'] = 1;
            }
            return view('general_crud.general_widget_view',$data)->with(compact('input_elements'));
        }
    }

    public function edit(Request $request,$reference_id, $id){
        $this->input_elements_creator($reference_id);
        $id = decrypt($id);
        if($request->isMethod('post')){
            return $this->form($request, $reference_id, $id);
        }else{
            $data['page_header'] = "Edit ".$this->module_name;
            $data['result'] = MainModel::find($id);
            $this->module_slug = Helper::module_chk($reference_id);
            $data['module_slug'] = $this->module_slug;
            $data['folder_name'] = $this->folder_name;
            $data['include_status_radio'] = 0;
            $data['include_is_mobile_show_radio'] = 0;
            $data['include_is_web_show_radio'] = 0;
            if (Gate::check($this->module_slug.'-widgets-references-status') || Gate::check($this->module_slug.'-widgets-references-custom-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check($this->module_slug.'-widgets-references-mobile_show') || Gate::check($this->module_slug.'-widgets-references-custom-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check($this->module_slug.'-widgets-references-web_show') || Gate::check($this->module_slug.'-widgets-references-custom-web_show')) {
                $data['include_is_web_show_radio'] = 1;
            }
            $input_elements = $this->input_elements;
            return view('general_crud.general_widget_view',$data)->with(compact('input_elements'));
        }
    }

}

