<?php

namespace App\Http\Controllers\Admin\Widget;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\{Speciality, ReferenceWidget, WidgetDoctor as MainModel};
use App\Http\Common\Helper;
use Illuminate\Support\Facades\Validator;

class TopDoctorController extends Controller
{
    public $folder_name = 'doctor'; // For view routes and file calling and saving
    public $module_name = 'Doctors'; // For toast And page header
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
                $getSpeciality = Speciality::where(['status' => true, 'lang_id' => $selectedLanguage])
                    ->where(function ($query) use ($selectedLanguage) {
                        if ($selectedLanguage != 1) {
                            $query->whereHas('translationOf.doctorSpecialities');
                        } else {
                            $query->whereHas('doctorSpecialities');
                        }
                    })->get();

                if (Gate::check($this->module_slug.'-widgets-doctor-speciality')) {
                    $input_element = array_push($input_elements, [
                        "label" => "Speciality Type",
                        "element_type" => "dropdown",
                        "name" => "speciality_id",
                        "options" => $getSpeciality,
                        "value_element" => "id",
                        "label_element" => "name",
                        "select_element" => "speciality_id",
                        "additional_ids" => ["id_1", "id_2"],
                        "additional_classes" => [],
                        "html_params" => ["required" => "required"],
                    ]);
                }

                if (Gate::check($this->module_slug.'-widgets-doctor-doctor_count')) {
                    $input_element = array_push($input_elements, [
                        "element_type" => "input",
                        "input_type" => "number",
                        "label" => "Total Doctors",
                        "name" => "doctor_count",
                        "placeholder" => "Enter Total Doctors Count",
                        "additional_ids" => [],
                        "additional_classes" => [],
                        "html_params" => ["min" => "5", "max" => "15", "required" => "required"],
                    ]);
                }

                $this->input_elements = $input_elements;
            }
            return $next($request);
        });
    }
    public function index(Request $request,$reference_id){
        if($request->isMethod('post')){
            return $this->form($request,$reference_id);
        }else{
            $data['reference_id'] = $reference_id;
            $data['page_header'] = "Edit ".$this->module_name;
            $data['result'] = MainModel::where(['reference_id' => $reference_id])->first() ?? null ;
            $data['reference'] = ReferenceWidget::find($reference_id);
            $this->module_slug = Helper::module_chk($reference_id);
            $data['folder_name'] = $this->folder_name;
            $data['module_slug'] = $this->module_slug;
            $data['include_status_radio'] = 0;
            $data['include_is_mobile_show_radio'] = 0;
            $data['include_is_web_show_radio'] = 0;
            if (Gate::check($this->module_slug.'-widgets-doctor-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check($this->module_slug.'-widgets-doctor-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check($this->module_slug.'-widgets-doctor-web_show')) {
                $data['include_is_web_show_radio'] = 1;
            }
            $input_elements = $this->input_elements;
            return view('general_crud.general_widget_view',$data)->with(compact('input_elements'));
        }
    }

    public function form(Request $request,$reference_id){
        $this->updateGeneralWidgetData($request, $reference_id);
        $validation = Validator::make($request->all(), [
            'speciality_id' => ['required'],
            'doctor_count' => ['required'],
        ]);
        if ($validation->fails()) {
            Helper::toast('error', $validation->errors()->first());
            return redirect()->back();
        }
        $data = null;
        if ($request->has('speciality_id')) {
            $data['speciality_id'] = $request->speciality_id;
        }
        if ($request->has('doctor_count')) {
            $data['doctor_count'] = $request->doctor_count;
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
