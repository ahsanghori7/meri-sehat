<?php

namespace App\Http\Controllers\Admin\Widget;

use App\Http\Common\Helper;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Gate;
use App\Models\{ReferenceWidget, WidgetCallToAction as MainModel};
use Illuminate\Http\Request;

class CallToActionController extends Controller
{
    public $folder_name = 'call-to-action'; // For view routes and file calling and saving
    public $module_name = 'Call to Action'; // For toast And page header
    public $module_slug; // check module slug
    public $input_elements;

    public function __construct(array $attributes = array())
    {
        $this->middleware(function ($request, $next) {
            $input_elements = array();
            $this->module_slug = Helper::module_chk();
            $reference_id = null;
            $current_uri = request()->segments();

            if (isset($current_uri[4]) && is_numeric($current_uri[4])) {
                $reference_id = $current_uri[4];
            }

            if (Gate::check($this->module_slug.'-widgets-call-to-action-button_text')) {
                $input_element = array_push($input_elements, [
                    "element_type" => "input",
                    "input_type" => "text",
                    "label" => "Button Text",
                    "name" => "button_text",
                    "placeholder" => "Enter Button Text",
                    "additional_ids" => [],
                    "additional_classes" => [],
                    "html_params" => ["required" => "required"],
                ]);
            }
            if (Gate::check($this->module_slug.'-widgets-call-to-action-button_color')) {
                $input_element = array_push($input_elements, [
                    "label" => "Button Color",
                    "element_type" => "dropdown",
                    "name" => "button_color",
                    "options" => [
                        [
                            "button_color" => "yellow",
                            "name" => "Yellow",
                        ],
                        [
                            "button_color" => "pink",
                            "name" => "Pink",
                        ],
                        [
                            "button_color" => "green",
                            "name" => "Green",
                        ],
                        [
                            "button_color" => "blue",
                            "name" => "Blue",
                        ],
                        [
                            "button_color" => "orange",
                            "name" => "Orange",
                        ],
                    ],
                    "value_element" => "button_color",
                    "label_element" => "name",
                    "additional_ids" => ["id_1", "id_2"],
                    "additional_classes" => [],
                    "html_params" => ["required" => "required"],
                ]);
            }
            if (Gate::check($this->module_slug.'-widgets-call-to-action-image_position')) {
                $input_element = array_push($input_elements, [
                    "label" => "Image Position",
                    "element_type" => "dropdown",
                    "name" => "image_position",
                    "options" => [
                        [
                            "image_position" => "left",
                            "name" => "Left",
                        ],
                        [
                            "image_position" => "right",
                            "name" => "Right",
                        ],
                    ],
                    "value_element" => "image_position",
                    "label_element" => "name",
                    "additional_ids" => ["id_1", "id_2"],
                    "additional_classes" => [],
                    "html_params" => ["required" => "required"],
                ]);
            }
            if (Gate::check($this->module_slug.'-widgets-call-to-action-image')) {
                $input_element = array_push($input_elements, [
                    "element_type" => "image",
                    "label" => "Image",
                    "name" => "image",
                    "additional_ids" => [],
                    "additional_classes" => [],
                    "html_params" => [],
                ]);
            }

            $this->input_elements = $input_elements;
            return $next($request);

        });

    }

    public function index(Request $request, $reference_id)
    {
        if ($request->isMethod('post')) {
            return $this->form($request, $reference_id);
        } else {
            $data['reference_id'] = $reference_id;
            $data['page_header'] = "Edit " . $this->module_name;
            $data['result'] = MainModel::where(['reference_id' => $reference_id])->first() ?? null;
            $data['reference'] = ReferenceWidget::find($reference_id);
            $this->module_slug = Helper::module_chk($reference_id);
            $data['folder_name'] = $this->folder_name;
            $data['module_slug'] = $this->module_slug;
            $data['include_status_radio'] = 0;
            $data['include_is_mobile_show_radio'] = 0;
            $data['include_is_web_show_radio'] = 0;
            if (Gate::check($this->module_slug.'-widgets-call-to-action-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check($this->module_slug.'-widgets-call-to-action-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check($this->module_slug.'-widgets-call-to-action-web_show')) {
                $data['include_is_web_show_radio'] = 1;
            }
            $input_elements = $this->input_elements;
            return view('general_crud.general_widget_view', $data)->with(compact('input_elements'));
        }
    }

    public function form(Request $request, $reference_id)
    {
        $data = null;
        if ($request->has('button_text')) {
            $data['button_text'] = $request->button_text;
        }
        if ($request->has('button_color')) {
            $data['button_color'] = $request->button_color;
        }
        if ($request->has('image_position')) {
            $data['image_position'] = $request->image_position;
        }
        if ($request->has('status')) {
            $data['status'] = $request->status ?? 1;
        }
        if ($request->hasFile('image')) {
            $folder=$this->folder_name;
            $key="image";
            $data['image'] = $this->S3UploaderSpecificKey($key,$request,$folder);
        }
        $this->updateGeneralWidgetData($request, $reference_id);
        if ($data) {
            MainModel::updateOrCreate(
                [
                    'reference_id' => $reference_id,
                ], $data
            );
        }
        Helper::toast('success', $this->module_name . ' created.');
        return redirect()->route('widget-' . $this->folder_name . '-index', ['reference_id' => $reference_id]);
    }
}
