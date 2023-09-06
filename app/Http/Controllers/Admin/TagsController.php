<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FaqCategory;
use Illuminate\Http\Request;
use App\Models\{Tags as MainModel,
    TagArchive};
use Auth;
use Illuminate\Support\Facades\Gate;
use Storage;
use Carbon\Carbon;
use Str;
use Yajra\Datatables\Datatables;
use App\Http\Common\Helper;
use DB;

class TagsController extends Controller
{
    public function input_elements_creator()
    {
        $input_elements = array();

        if (Gate::check('tags-name')) {
            $input_element = array_push($input_elements, [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Tag Name",
                "name" => "name",
                "placeholder" => "Enter Tag Name",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }

        if (Gate::check('tags-restricted')) {
            $input_element = array_push($input_elements, [
                "label" => "Restriction",
                "element_type" => "radio",
                "name" => "restricted",
                "html_params" => ["required" => "required"],
                "buttons" => [
                    [
                        "value_element" => "text",
                        "label" => "Restricted",
                        "value" => "1",
                        "checked_on_null" => 0,
                        "additional_ids" => [],
                        "additional_classes" => [],
                    ],
                    [
                        "value_element" => "text",
                        "label" => "Not Restricted",
                        "value" => "0",
                        "checked_on_null" => 1,
                        "additional_ids" => [],
                        "additional_classes" => [],
                    ],
                ],
            ]);
        }

        if (Gate::check('tags-goto_live')) {
            $input_element = array_push($input_elements, [
                "element_type" => "input",
                "input_type" => "checkbox",
                "label" => "Goto Live",
                "name" => "goto_live",
                "placeholder" => "Goto Live",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => [],
            ]);
        }

        $this->input_elements = $input_elements;
    }

    public $folder_name = 'tag'; // For view routes and file calling and saving
    public $module_name = 'Tags'; // For toast And page header
    public $input_elements;

    public function draftView(Request $request){
        if (Gate::denies('tags-draft')) {
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
            $data['result'] = MainModel::where('draft', true)->orderBy('sequence')->get();
            $file = "admin.".$this->folder_name.".draft-view";
            return view($file,$data);
        }
    }

    public function view(Request $request){
        $this->input_elements_creator();
        if (Gate::denies('tags-view')) {
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
            $data['result'] = MainModel::where('draft', false)->get();
            $file = "admin.".$this->folder_name.".view";
            return view($file,$data);
        }
    }

    public function markDraft(Request $request, $id){
        if (Gate::denies('tags-draft')) {
            abort(403);
        }
        $id = decrypt($id);
        $getTag = MainModel::find($id);
        $getTag->draft = 1;
        $getTag->status = 0;
        $tag_save = $getTag->save();
        if (isset($tag_save)) {
            Helper::toast('success',$getTag->name.' successfully draft.');
        } else {
            Helper::toast('failed',$this->module_name.' not going to draft.');
        }
        return redirect()->route($this->folder_name.'-draft');
    }

    public function gotoLive(Request $request, $id){
        if (Gate::denies('tags-goto_live')) {
            abort(403);
        }
        $id = decrypt($id);
        $getTag = MainModel::find($id);
        $getTag->draft = 0;
        $getTag->status = 1;
        $tag_save = $getTag->save();
        if (isset($tag_save)) {
            Helper::toast('success',$getTag->name.' successfully live.');
        } else {
            Helper::toast('failed',$this->module_name.' not going to live.');
        }
        return redirect()->route($this->folder_name.'-draft');
    }

    public function form(Request $request, $id = null){
        $request->validate(MainModel::getValidationRules($id));
        $sequence = MainModel::count();
        $sequence = $sequence + 1;
//        $data = [
//            'name' => $request->name,
//            'restricted' => $request->restricted,
//            'status' => $request->status ?? 1,
//        ];
        if ($request->has('name')) {
            $data['name'] = $request->name;
        }
        if ($request->has('restricted')) {
            $data['restricted'] = $request->restricted;
        }
        if ($request->has('status')) {
            $data['status'] = $request->status ?? 1;
        }
        if($request->hasFile('mobile_image')){
            $folder=$this->folder_name;
            $key="mobile_image";
            $data['mobile_image'] = $this->S3UploaderSpecificKey($key,$request,$folder);
        }
        if ($request->has('goto_live')) {
            $data['draft'] = 0;
            $data['status'] = true;
        } else {
            if ($request->segment(3) == 'draft') {
                $data['draft'] = 1;
            }
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
        if (str_contains($request->segment(3), 'draft')) {
            return redirect()->route($this->folder_name . '-draft');
        }
        return redirect()->route($this->folder_name.'-view');
    }

    public function add(Request $request){
        $this->input_elements_creator();
        if (Gate::denies('tags-add')) {
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
            if (Gate::check('tags-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check('tags-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check('tags-web_show')) {
                $data['include_is_web_show_radio'] = 1;
            }
            return view('general_crud.general_view',$data)->with(compact('input_elements'));
        }
    }

    public function edit(Request $request, $id){
        $this->input_elements_creator();
        $id = decrypt($id);
        if (Gate::denies('tags-update')) {
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
            if (Gate::check('tags-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check('tags-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check('tags-web_show')) {
                $data['include_is_web_show_radio'] = 1;
            }
            return view('general_crud.general_view',$data)->with(compact('input_elements'));
        }
    }

    public function tagArchive(Request $request,$id){
        if (Gate::denies('tags-archive')) {
            abort(403);
        }
        $id = decrypt($id);
        $tagArchive= new TagArchive;
        $getTags= MainModel::where('id',$id)->first();
        if($getTags){
            $tagArchive->name=$getTags->name;
            $tagArchive->restricted=$getTags->restricted;
            $tagArchive->status=$getTags->status;
            $tagArchive->tags_id=$getTags->id;
            $tagArchive->save();
            $getTags->forceDelete();
            return redirect()->back();

        }
    }
}
