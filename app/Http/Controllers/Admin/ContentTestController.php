<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteContent as MainModel;
use App\Http\Common\Helper;
use Storage;
use Auth;
use App\Models\Language;
class ContentTestController extends Controller
{
    //
    public function input_elements_creator()
    {
        $input_elements = [
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
            [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Slug",
                "name" => "slug",
                "placeholder" => "slug",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ],
            [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Heading",
                "name" => "heading",
                "placeholder" => "Heading",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ],
            [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Keyword",
                "name" => "keyword",
                "placeholder" => "Heading",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ],
            [
                "label" => "Meta Description",
                "element_type" => "textarea",
                "name" => "meta_description",
                "editor" => 0,
                "rows" => 3,
                "placeholder" => "Please Enter Description",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ],
            [
                "label" => "Content",
                "element_type" => "textarea",
                "name" => "content",
                "editor" => 1,
                "rows" => 5,
                "placeholder" => "Please Enter Content",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ],
        ];

        $this->input_elements = $input_elements;
        $this->english_records = MainModel::where('lang_id',Language::ENGLISH)->get();
        $this->english_records_name_element = 'title';
    }

    public $folder_name = 'site-content'; // For view routes and file calling and saving
    public $module_name = 'Site Content'; // For toast And page header
    public $include_translation_section = 1; // Language and translation section
    public $english_records = [];
    public $input_elements;


    public function view(Request $request){
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
        $data = [
            'title' => $request->title,
            'slug' => $request->slug,
            'heading' => $request->heading,
            'keyword' => $request->keyword,
            'meta_description' => $request->meta_description,
            'content' => $request->content,
            'edit_by' => Auth::user()->id,
            'status' => $request->status ?? 1,
        ];
        // return $data;
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
        if($request->isMethod('post')){
            return $this->form($request);
        }else{
            $data['page_header'] = "Add ".$this->module_name;
            $data['result'] = null;
            $input_elements = $this->input_elements;
            $data['include_status_radio'] = 1;
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
        $id = decrypt($id);
        // return $id;
        if($request->isMethod('post')){
            return $this->form($request,$id);
        }else{
            $data['page_header'] = "Edit ".$this->module_name;
            $data['result'] = MainModel::find($id);
            $data['include_status_radio'] = 1;
            $data['include_is_mobile_show_radio'] = 1;
            $data['include_is_web_show_radio'] = 1;
            $input_elements = $this->input_elements;
            $data['include_translation_section'] = $this->include_translation_section;
            $data['english_records'] = $this->english_records;
            $data['english_records_name_element'] = $this->english_records_name_element;
            return view('general_crud.general_view',$data)->with(compact('input_elements'));
        }
    }
}
