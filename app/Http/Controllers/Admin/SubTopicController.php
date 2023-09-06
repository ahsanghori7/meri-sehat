<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubTopic as MainModel;
use App\Models\Topics;
use Auth;
use Illuminate\Support\Facades\Gate;
use Storage;
use Carbon\Carbon;
use Str;
use Yajra\Datatables\Datatables;
use App\Http\Common\Helper;
use DB;

class SubTopicController extends Controller
{
    public function input_elements_creator()
    {
        $input_elements = [
            [
                "label" => "Select Topic",
                "element_type" => "dropdown",
                "name" => "topic",
                "options" => Topics::where('status', 1)->get(),
                "value_element" => "id",
                "label_element" => "title",
                "additional_ids" => ["id_1", "id_2"],
                "additional_classes" => ["class_1", "class_2"],
                "html_params" => ["required" => "required"],
            ],
            [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Title",
                "name" => "title",
                "placeholder" => "Enter Title",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ],
        ];

        $this->input_elements = $input_elements;
    }

    public $folder_name = 'sub_topic'; // For view routes and file calling and saving
    public $module_name = 'Sub Topic'; // For toast And page header
    public $input_elements;

    public function view(Request $request){
        $this->input_elements_creator();
        if (Gate::denies('category-management-view')) {
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
            $data['result'] = MainModel::orderBy('sequence')->get();
            $file = "admin.".$this->folder_name.".view";
            return view($file,$data);
        }
    }

    public function form(Request $request, $id = null){
        $request->validate(MainModel::getValidationRules($id));
        $sequence = MainModel::count();
        $sequence = $sequence + 1;
        $data = [
            'title' => $request->title,
            'slug' => \Str::slug($request->title),
            'topic_id' => $request->topic,
            'lang_id' => 1,
        ];

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
        if (Gate::denies('category-management-add')) {
            abort(403);
        }
        if($request->isMethod('post')){
            return $this->form($request);
        }else{
            $data['page_header'] = "Add ".$this->module_name;
            $data['result'] = null;
            $input_elements = $this->input_elements;
            $data['include_status_radio'] = 1;
            $data['include_is_mobile_show_radio'] = 1;
            $data['include_is_web_show_radio'] = 1;
            return view('general_crud.general_view',$data)->with(compact('input_elements'));
        }
    }

    public function edit(Request $request, $id){
        $this->input_elements_creator();
        $id = decrypt($id);
        if (Gate::denies('category-management-update')) {
            abort(403);
        }
        if($request->isMethod('post')){
            return $this->form($request,$id);
        }else{
            $data['page_header'] = "Edit ".$this->module_name;
            $data['result'] = MainModel::find($id);
            $data['include_status_radio'] = 1;
            $data['include_is_mobile_show_radio'] = 1;
            $data['include_is_web_show_radio'] = 1;
            $input_elements = $this->input_elements;
            return view('general_crud.general_view',$data)->with(compact('input_elements'));
        }
    }

}
