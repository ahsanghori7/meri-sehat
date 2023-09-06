<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{ ArticleFact as MainModel };
use App\Http\Common\Helper;
use Illuminate\Support\Facades\Gate;

class ArticleFactController extends Controller
{
    public $folder_name = 'article-fact'; // For view routes and file calling and saving
    public $module_name = 'Article Facts'; // For toast And page header
    public $input_elements;

    public function input_elements_creator()
    {
        $input_elements = [
            [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Name",
                "name" => "name",
                "placeholder" => "Enter Article Fact Name",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ],
            [
                "label" => "Color Code",
                "element_type" => "dropdown",
                "name" => "color",
                "options" => [
                    [
                        "type" => "#FF0000",
                        "name" => "Red",
                    ],
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
                    [
                        "type" => "#E9F5F1",
                        "name" => "Clear Day",
                    ],
                    [
                        "type" => "#F7EEE9",
                        "name" => "Hint Of Red",
                    ],
                    [
                        "type" => "#E9EAEF",
                        "name" => "Solitude",
                    ],
                ],
                "value_element" => "type",
                "select_element" => "color",
                "label_element" => "name",
                "additional_ids" => ["id_1", "id_2"],
                "additional_classes" => ["class_1", "class_2"],
                "html_params" => ["required" => "required"],
            ],
        ];

        $this->input_elements = $input_elements;
    }

    public function view(Request $request){
        if (Gate::denies('article-fact-view')) {
            abort(403);
        }
        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
        $data['result'] = MainModel::get();
        // dd(MainModel::get());
        $file = "admin.".$this->folder_name.".view";
        return view($file,$data);
    }

    public function form(Request $request, $id = null){
        if ($request->has('name')) {
            $data['name'] = $request->name;
        }
        if ($request->has('color')) {
            $data['color'] = $request->color;
        }
        if ($request->has('status')) {
            $data['status'] = $request->status ?? 1;
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
        if (Gate::denies('article-fact-add')) {
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
            if (Gate::check('article-fact-add-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check('article-fact-add-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check('article-fact-add-web_show')) {
                $data['include_is_web_show_radio'] = 1;
            }
            return view('general_crud.general_view', $data)->with(compact('input_elements'));
        }
    }

    public function edit(Request $request, $id){
        $this->input_elements_creator();
        if (Gate::denies('article-fact-update')) {
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
            if (Gate::check('article-fact-add-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check('article-fact-add-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check('article-fact-add-web_show')) {
                $data['include_is_web_show_radio'] = 1;
            }
            $input_elements = $this->input_elements;
            return view('general_crud.general_view', $data)->with(compact('input_elements'));
        }
    }
}
