<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Faq as MainModel,
    FaqsArchive};
use App\Models\FaqCategory;
use Auth;
use Illuminate\Support\Facades\Gate;
use Storage;
use Carbon\Carbon;
use Str;
use Yajra\Datatables\Datatables;
use App\Http\Common\Helper;
use DB;

class FaqController extends Controller
{
    public function input_elements_creator()
    {
        $input_elements = array();

        if (Gate::check('faq-category_id')) {
            $input_element = array_push($input_elements, [
                "label" => "FAQ Category",
                "element_type" => "dropdown",
                "name" => "category_id",
                "options" => FaqCategory::where('status', 1)->get(),
                "value_element" => "id",
                "label_element" => "name",
                "select_element" => "category_id",
                "additional_ids" => ["id_1", "id_2"],
                "additional_classes" => ["class_1", "class_2"],
                "html_params" => ["required" => "required"],
            ]);
        }
        if (Gate::check('faq-question')) {
            $input_element = array_push($input_elements, [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Question",
                "name" => "question",
                "placeholder" => "Enter Question",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }
        if (Gate::check('faq-answer')) {
            $input_element = array_push($input_elements, [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Answer",
                "name" => "answer",
                "placeholder" => "Please Enter Answer",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }

        if (Gate::check('faq-goto_live')) {
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
//        if (Gate::check('faq-answer')) {
//            $input_element = array_push($input_elements, [
//                "label" => "Answer",
//                "element_type" => "textarea",
//                "name" => "answer",
//                "editor" => 1,
//                "rows" => 10,
//                "placeholder" => "Please Enter Answer",
//                "additional_ids" => ["id_1", "id_2"],
//                "additional_classes" => ["class_1", "class_2"],
//                "html_params" => ["required" => "required"],
//            ]);
//        }

        $this->input_elements = $input_elements;
    }

    public $folder_name = 'faq'; // For view routes and file calling and saving
    public $module_name = 'Faqs'; // For toast And page header
    public $input_elements;

    public function draftView(Request $request){
        if (Gate::denies('faq-draft')) {
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
        if (Gate::denies('faq-view')) {
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
            $data['result'] = MainModel::where('draft', false)->orderBy('sequence')->get();
            $file = "admin.".$this->folder_name.".view";
            return view($file,$data);
        }
    }

    public function markDraft(Request $request, $id){
        if (Gate::denies('faq-draft')) {
            abort(403);
        }
        $id = decrypt($id);
        $getFaq = MainModel::find($id);
        $getFaq->draft = 1;
        $getFaq->status = 0;
        $faq_save = $getFaq->save();
        if (isset($faq_save)) {
            Helper::toast('success',$getFaq->name.' successfully draft.');
        } else {
            Helper::toast('failed',$this->module_name.' not going to draft.');
        }
        return redirect()->route($this->folder_name.'-draft');
    }

    public function gotoLive(Request $request, $id){
        if (Gate::denies('faq-goto_live')) {
            abort(403);
        }
        $id = decrypt($id);
        $getFaq = MainModel::find($id);
        $getFaq->draft = 0;
        $getFaq->status = 1;
        $faq_save = $getFaq->save();
        if (isset($faq_save)) {
            Helper::toast('success',$getFaq->name.' successfully live.');
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
//            'category_id' => $request->category_id,
//            'question' => $request->question,
//            'answer' => $request->answer,
//            // 'sequence' => $sequence,
//            'status' => $request->status ?? 1,
//        ];
        if ($request->has('category_id')) {
            $data['category_id'] = $request->category_id;
        }
        if ($request->has('question')) {
            $data['question'] = $request->question;
        }
        if ($request->has('answer')) {
            $data['answer'] = $request->answer;
        }
        if ($request->has('status')) {
            $data['status'] = $request->status ?? 1;
        }
        if ($request->has('goto_live')) {
            $data['draft'] = 0;
            $data['status'] = true;
        } else {
            if ($request->segment(3) == 'draft') {
                $data['draft'] = 1;
            }
        }
        if($request->hasFile('image')){
            $folder=$this->folder_name;
            $data['image'] = $this->S3Uploader($request,$folder);
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
        if (Gate::denies('faq-add')) {
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
            if (Gate::check('faq-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check('faq-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check('faq-web_show')) {
                $data['include_is_web_show_radio'] = 1;
            }
            return view('general_crud.general_view',$data)->with(compact('input_elements'));
        }
    }

    public function edit(Request $request, $id){
        $this->input_elements_creator();
        if (Gate::denies('faq-update')) {
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
            if (Gate::check('faq-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check('faq-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check('faq-web_show')) {
                $data['include_is_web_show_radio'] = 1;
            }
            $input_elements = $this->input_elements;
            return view('general_crud.general_view',$data)->with(compact('input_elements'));
        }
    }

    public function faqArchive(Request $request, $id){
        if (Gate::denies('faq-archive')) {
            abort(403);
        }
        $id = decrypt($id);
        $faqsArchive= new FaqsArchive;
        $getFaqs= MainModel::where('id',$id)->first();
        if($getFaqs){
            $faqsArchive->sequence=$getFaqs->sequence;
            $faqsArchive->category_id=$getFaqs->category_id;
            $faqsArchive->question=$getFaqs->question;
            $faqsArchive->answer=$getFaqs->answer;
            $faqsArchive->status=$getFaqs->status;
            $faqsArchive->faqs_id=$getFaqs->id;
            $faqsArchive->save();
            $getFaqs->forceDelete();
            return redirect()->back();
        }

    }

}
