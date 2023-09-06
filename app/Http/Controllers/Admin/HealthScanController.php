<?php

namespace App\Http\Controllers\Admin;

use App\Http\Common\Constant;
use App\Http\Common\Helper;
use App\Http\Controllers\Controller;
use App\Models\{Appointment, Article, HealthScanSuggestion as MainModel, User};
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Gate;

class HealthScanController extends Controller
{
    public $folder_name = 'health-scan-management'; // For view routes and file calling and saving
    public $module_name = 'Health Scan Management'; // For toast And page header
    public $input_elements;

    public function input_elements_creator($mode = null)
    {
        $input_elements = array();

        if (Gate::check('scan-management-general-name')) {
            if ($mode == 'add') {
                $input_element = array_push($input_elements, [
                    "element_type" => "input",
                    "input_type" => "text",
                    "label" => "Name",
                    "name" => "name",
                    "placeholder" => "",
                    "additional_ids" => ["id_1", "id_2"],
                    "additional_classes" => ["class_1", "class_2"],
                    "html_params" => [],
                ]);
            } else {
                $input_element = array_push($input_elements, [
                    "element_type" => "input",
                    "input_type" => "text",
                    "label" => "Name",
                    "name" => "name",
                    "placeholder" => "",
                    "additional_ids" => ["id_1", "id_2"],
                    "additional_classes" => ["class_1", "class_2"],
                    "html_params" => ["disabled" => "disabled"],
                ]);
            }
        }
        if (Gate::check('scan-management-general-doctor')) {
            $input_element = array_push($input_elements, [
                "label" => " Doctor",
                "element_type" => "dropdown",
                "name" => "doctor_id",
                "options" => User::whereHas('hasDoctor')->get(),
                "value_element" => "id",
                "label_element" => "name",
                "select_element" => "doctor_id",
                "additional_ids" => [],
                "additional_classes" => ["class_1", "class_2"],
                "html_params" => ["required" => "required"],
            ]);
        }
        if (Gate::check('scan-management-general-article')) {
            $input_element = array_push($input_elements, [
                "label" => " Select Article",
                "element_type" => "dropdown",
                "name" => "article_id",
                "options" => Article::where('status', true)->get(),
                "value_element" => "id",
                "label_element" => "name",
                "select_element" => "article_id",
                "additional_ids" => [],
                "additional_classes" => ["class_1", "class_2"],
                "html_params" => ["required" => "required"],
            ]);
        }
        if (Gate::check('scan-management-general-description_eng')) {
            $input_element = array_push($input_elements, [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Description",
                "name" => "description",
                "placeholder" => "Enter Description",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => [],
            ]);
        }
        if (Gate::check('scan-management-general-description_urdu')) {
            $input_element = array_push($input_elements, [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Urdu Description",
                "name" => "urdu_description",
                "placeholder" => "تفصیل درج کریں۔",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ['dir' => 'rtl'],
            ]);
        }
        if (Gate::check('scan-management-general-diagnosis_eng')) {
            $input_element = array_push($input_elements, [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Scan Result",
                "name" => "diagnosis",
                "placeholder" => "Enter Scan Result",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => [],
            ]);
        }
        if (Gate::check('scan-management-general-diagnosis_urdu')) {
            $input_element = array_push($input_elements, [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Scan Result in Urdu",
                "name" => "urdu_diagnosis",
                "placeholder" => "اسکین کا نتیجہ درج کریں۔",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ['dir' => 'rtl'],
            ]);
        }
        if (Gate::check('scan-management-general-type')) {
            $input_element = array_push($input_elements, [
                "label" => " Type",
                "element_type" => "dropdown",
                "name" => "type",
                "options" => [
                    [
                        "type" => "low",
                        "name" => "Low",
                    ],
                    [
                        "type" => "medium",
                        "name" => "Medium",
                    ],
                    [
                        "type" => "high",
                        "name" => "High",
                    ],
                ],
                "value_element" => "type",
                "label_element" => "name",
                "additional_ids" => [],
                "additional_classes" => ["class_1", "class_2"],
                "html_params" => [],
            ]);
        }

        $this->input_elements = $input_elements;
    }

    public function view(Request $request)
    {
        if (Gate::denies('scan-management-view')) {
            abort(403);
        }
        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
        $data['result'] = MainModel::get();
        $file = "admin." . $this->folder_name . ".view";
        return view($file, $data);
    }

    public function fetch(Request $request){
        $query = new MainModel();
        if ($request->has('search_doctor') && $request->search_doctor != '') {
            $search_doctor = $request->search_doctor;
            $query = $query->whereHas('doctor', function($query) use ($search_doctor){
                $query = $query->where('name', 'like', '%'.$search_doctor.'%');
            });
        }
        if ($request->has('search_type') && $request->search_type != '') {
            $query = $query->where('type', $request->search_type);
        }
        if ($request->has('search_status') && $request->search_status != '') {
            $query = $query->where('status', $request->search_status);
        }
        $query = $query->with('doctor', 'article');
        return DataTables::of($query)->make(true);
    }

    public function form(Request $request, $id = null)
    {
        if($request->is_starred){
            MainModel::where('is_starred', true)->update(['is_starred' => false]);
        }

        if ($request->has('doctor_id')) {
            $data['doctor_id'] = $request->doctor_id;
        }
        if ($request->has('article_id')) {
            $data['article_id'] = $request->article_id;
        }
        if ($request->has('description')) {
            $data['description'] = $request->description;
        }
        if ($request->has('urdu_description')) {
            $data['urdu_description'] = $request->urdu_description;
        }
        if ($request->has('type')) {
            $data['type'] = $request->type;
        }
        if ($request->has('diagnosis')) {
            $data['diagnosis'] = $request->diagnosis;
        }
        if ($request->has('urdu_diagnosis')) {
            $data['urdu_diagnosis'] = $request->urdu_diagnosis;
        }
        if ($request->has('status')) {
            $data['status'] = $request->status ?? 1;
        }

        if($id && MainModel::find($id)->update($data)) {
            Helper::toast('success', $this->module_name . ' Updated.');
        }
        return redirect()->route($this->folder_name . '-view');
    }

    public function toggleStatus(Request $request)
    {
        $result = MainModel::find($request->id);
        $result->status = $request->val;
        $result->save();
    }

    public function add(Request $request)
    {
        $this->input_elements_creator('add');
        if (Gate::denies('scan-management-add')) {
            abort(403);
        }
        if ($request->isMethod('post')) {
            return $this->form($request);
        } else {
            $data['page_header'] = "Add " . $this->module_name;
            $data['result'] = null;
            $input_elements = $this->input_elements;
            $data['include_status_radio'] = 0;
            $data['include_is_mobile_show_radio'] = 0;
            $data['include_is_web_show_radio'] = 0;
            if (Gate::check('scan-management-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check('scan-management-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check('scan-management-web_show')) {
                $data['include_is_web_show_radio'] = 1;
            }
            return view('general_crud.general_view', $data)->with(compact('input_elements'));
        }
    }

    public function edit(Request $request, $id)
    {
        $this->input_elements_creator('edit');
        if (Gate::denies('scan-management-update')) {
            abort(403);
        }
        $id = decrypt($id);
        if ($request->isMethod('post')) {
            Gate::authorize('scan-management-update');
            return $this->form($request, $id);
        } else {
            Gate::authorize('scan-management-view');
            $data['page_header'] = "Edit " . $this->module_name;
            $data['result'] = MainModel::find($id);
            $data['include_status_radio'] = 0;
            $data['include_is_mobile_show_radio'] = 0;
            $data['include_is_web_show_radio'] = 0;
            if (Gate::check('scan-management-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check('scan-management-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check('scan-management-web_show')) {
                $data['include_is_web_show_radio'] = 1;
            }
            $input_elements = $this->input_elements;
            return view('general_crud.general_view', $data)->with(compact('input_elements'));
        }
    }
}
