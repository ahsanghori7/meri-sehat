<?php

namespace App\Http\Controllers\Admin;

use App\Http\Common\Constant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\{Settings, ArticleBadge as MainModel};
use App\Http\Common\Helper;

class ArticleBadgeController extends Controller
{
    public function input_elements_creator()
    {
        $input_elements = [
            [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Name",
                "name" => "title",
                "placeholder" => "Enter Badge Title",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ],
            [
                "label" => "Color Code",
                "element_type" => "dropdown",
                "name" => "background_color",
                "options" => [
                    [
                        "type" => "#72d54a",
                        "name" => "Green",
                    ],
                    [
                        "type" => "#28bcc1",
                        "name" => "Sky Blue",
                    ],
                    [
                        "type" => "#f5d730",
                        "name" => "Yellow",
                    ],
                    [
                        "type" => "#ef6286",
                        "name" => "Pink",
                    ],
                    [
                        "type" => "#bef5f1",
                        "name" => "Light Blue",
                    ],
                    [
                        "type" => "#e9eaef",
                        "name" => "Bright Gray",
                    ],
                ],
                "value_element" => "type",
                "select_element" => "background_color",
                "label_element" => "name",
                "additional_ids" => ["id_1", "id_2"],
                "additional_classes" => ["class_1", "class_2"],
                "html_params" => ["required" => "required"],
            ],
        ];

        $this->input_elements = $input_elements;
    }

    public $folder_name = 'article_badge'; // For view routes and file calling and saving
    public $module_name = 'Article Badge'; // For toast And page header
    public $input_elements;

    public function view(Request $request){
        if (Gate::denies('article-management-view')) {
            abort(403);
        }
        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
        $data['result'] = MainModel::get();
        $file = "admin.".$this->folder_name.".view";
        return view($file,$data);
    }

    public function form(Request $request, $id = null){
        Settings::where('key', Constant::CACHE_SIGNATURE)->updateOrCreate([ 'key' => Constant::CACHE_SIGNATURE ], [ 'value' => rand(100000, 999999)]);
        $data = [
            'title' => $request->title,
            'background_color' => $request->background_color,
            'status' => $request->status ?? 1,
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
        if (Gate::denies('article-management-add')) {
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
            return view('general_crud.general_view', $data)->with(compact('input_elements'));
        }
    }

    public function edit(Request $request, $id){
        $this->input_elements_creator();
        if (Gate::denies('article-management-update')) {
            abort(403);
        }
        $id = decrypt($id);
        if($request->isMethod('post')){
            return $this->form($request,$id);
        }else{
            $data['page_header'] = "Edit ".$this->module_name;
            $data['result'] = MainModel::find($id);
            $data['include_status_radio'] = 1;
            $data['include_is_mobile_show_radio'] = 1;
            $data['include_is_web_show_radio'] = 1;
            $input_elements = $this->input_elements;
            return view('general_crud.general_view', $data)->with(compact('input_elements'));
        }
    }
}
