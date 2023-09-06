<?php

namespace App\Http\Controllers\Admin\Widget;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WidgetFaq as MainModel;
use Illuminate\Support\Facades\Gate;
use App\Models\{ReferenceWidget,FaqCategory};
use App\Http\Common\Helper;

class FaqCategoryController extends Controller
{
    public $folder_name = 'faq-category'; // For view routes and file calling and saving
    public $module_name = 'FAQ Category'; // For toast And page header
    public $module_slug; // check module slug
    public $input_elements;

    public function __construct($reference_id = null)
    {
        $this->middleware(function ($request, $next) {
            $input_elements = array();
            $this->module_slug = Helper::module_chk();
            $reference_id = null;
            $current_uri = request()->segments();

            if (isset($current_uri[4]) && is_numeric($current_uri[4])) {
                $reference_id = $current_uri[4];
            }

            if ($reference_id) {
                $getReferenceWidget = ReferenceWidget::find($reference_id);
                $selectedLanguage = $getReferenceWidget->page->lang_id ?? 1;
                $getFaqCategories = FaqCategory::where(['status' => true, 'lang_id' => $selectedLanguage])->get();

                if (Gate::check($this->module_slug.'-widgets-faq-category-category')) {
                    $input_element = array_push($input_elements, [
                        "label" => "Select FAQ Category",
                        "element_type" => "dropdown",
                        "name" => "category_id",
                        "options" => $getFaqCategories,
                        "value_element" => "id",
                        "label_element" => "name",
                        "select_element" => "category_id",
                        "additional_ids" => ["id_1", "id_2"],
                        "additional_classes" => ["class_1", "class_2"],
                        "html_params" => ["required" => "required"],
                    ]);
                }
                $this->input_elements = $input_elements;
            }
            return $next($request);
        });
    }

    public function index(Request $request, $reference_id){
        if($request->isMethod('post')){
            return $this->form($request, $reference_id);
        }else{
            $thisController = new self($reference_id);
            $this->input_elements = $thisController->input_elements;
            $data['reference_id'] = $reference_id;
            $data['page_header'] = "Edit ".$this->module_name;
            $data['reference'] = ReferenceWidget::find($reference_id);
            $data['result'] = MainModel::where(['reference_id' => $reference_id])->first() ?? null ;
            $this->module_slug = Helper::module_chk($reference_id);
            $data['folder_name'] = $this->folder_name;
            $data['module_slug'] = $this->module_slug;
            $data['include_status_radio'] = 0;
            $data['include_is_mobile_show_radio'] = 0;
            $data['include_is_web_show_radio'] = 0;
            if (Gate::check($this->module_slug.'-widgets-faq-category-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check($this->module_slug.'-widgets-faq-category-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check($this->module_slug.'-widgets-faq-category-web_show')) {
                $data['include_is_web_show_radio'] = 1;
            }
            $input_elements = $this->input_elements;
            return view('general_crud.general_widget_view',$data)->with(compact('input_elements'));
        }
    }

    public function form(Request $request, $reference_id){
        $this->updateGeneralWidgetData($request, $reference_id);
        $data = null;
        if ($request->has('status')) {
            $data['status'] = $request->status ?? 1;
        }
        if ($request->has('is_mobile_show')) {
            $data['is_mobile_show'] = $request->is_mobile_show ?? 1;
        }
        if ($request->has('is_web_show')) {
            $data['is_web_show'] = $request->is_web_show ?? 1;
        }
        if ($request->has('category_id')) {
            $data['category_id'] = $request->category_id;
        }
        if ($data) {
            MainModel::updateOrCreate(
                [
                    'reference_id' => $reference_id,
                ], $data
            );
        }
        Helper::toast('success',$this->module_name.' created.');
        return redirect()->route('widget-'.$this->folder_name.'-index', ['reference_id' => $reference_id]);
    }

}
