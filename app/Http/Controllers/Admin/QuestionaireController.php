<?php

namespace App\Http\Controllers\Admin;

use App\Http\Common\Constant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\{
    QuestionaireForm as MainModel,
    QuestionaireFormFields,
    Settings,
    Speciality,
};
use App\Http\Common\Helper;

class QuestionaireController extends Controller
{
    public $folder_name = 'questionaire_form'; // For view routes and file calling and saving
    public $module_name = 'Questionaire Form'; // For toast And page header

    public function view(Request $request){
        if (Gate::denies('questionaire-form-view')) {
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
        if ($request->has('speciality_id')) {
            $data['speciality_id'] = $request->speciality_id == 'none' ? null : $request->speciality_id;
        }
        if ($request->has('status')) {
            $data['status'] = $request->status ?? 1;
        }

        if($id){
            if(MainModel::find($id)->update($data)){
                Helper::toast('success',$this->module_name.' Updated.');
            }
        }else{
            if( $created_data = MainModel::create($data)){
                Helper::toast('success',$this->module_name.' created.');
            }
        }
        $questionaire_form_id = $id ?? $created_data->id;
        QuestionaireFormFields::where('questionaire_form_id' , $questionaire_form_id)->delete();
        if(isset($request->title)){
            foreach($request->title as $key => $value){
                try{
//                    $form_fields = [
//                        'questionaire_form_id' => $id ?? $created_data->id,
//                        'title' => $request->title[$key],
//                        'input_type' => $request->input_type[$key],
//                        'description' => $request->description[$key],
//                        'json_params' => $request->input_type[$key] == 'dropdown' ? json_encode($request->option[$key]) : null,
//                    ];
                    if ($id) {
                        $form_fields['questionaire_form_id'] = $id ?? $created_data->id;
                    }
                    if ($request->title[$key]) {
                        $form_fields['title'] = $request->title[$key];
                    }
                    if ($request->input_type[$key]) {
                        $form_fields['input_type'] = $request->input_type[$key];
                    }
                    if ($request->description[$key]) {
                        $form_fields['description'] = $request->description[$key];
                    }
                    if ($request->input_type[$key]) {
                        $form_fields['json_params'] = $request->input_type[$key] == 'dropdown' ? json_encode($request->option[$key]) : null;
                    }

                    QuestionaireFormFields::create($form_fields);
                }catch(Exception $e){
                    continue;
                }
            }
        }

        return redirect()->route($this->folder_name.'-view');
    }

    public function add(Request $request){
        if (Gate::denies('questionaire-form-add')) {
            abort(403);
        }
        if($request->isMethod('post')){
            return $this->form($request);
        }else{
            $data['page_header'] = "Add ".$this->module_name;
            $data['result'] = null;
            $data['specialities'] = Speciality::doesnthave('questionaire_forms')->get();
            $data['include_status_radio'] = 1;
            $file = "admin.".$this->folder_name.".form";
            return view($file,$data);
        }
    }

    public function edit(Request $request, $id){
        if (Gate::denies('questionaire-form-update')) {
            abort(403);
        }
        $id = decrypt($id);
        if($request->isMethod('post')){
            return $this->form($request,$id);
        }else{
            $data['page_header'] = "Edit ".$this->module_name;
            $data['result'] = MainModel::find($id);
            $data['result']->speciality;
            $data['specialities'] = Speciality::doesnthave('questionaire_forms')->get();
            $data['include_status_radio'] = 1;
            $file = "admin.".$this->folder_name.".form";
            return view($file,$data);
        }
    }

    public function getParentsByType(Request $request){
        $general_response = ['status' => true];
        $type = $request->type;
        try{
            if($type == 'dropdown-link'){
                $data['parents'] = MainModel::where('type','dropdown-header')->get();
            }else if($type == 'dropdown-header') {
                $data['parents'] = MainModel::where('type','nav-link-with-dropdown')->get();
            }
            $file = "admin.".$this->folder_name.".parents_dropdown";
            $html = view($file,$data)->render();
            $general_response['html'] = $html;
            return $general_response;
        }catch(\Exception $e){
            return [
                'status' => false,
                'message' => 'Something went wrong'
            ];
        }
    }
}
