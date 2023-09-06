<?php

namespace App\Http\Controllers\Admin\Widget;

use App\Http\Controllers\Controller;
use App\Http\Middleware\AuthGate;
use Illuminate\Http\Request;
use App\Models\{WidgetBanner as MainModel, ReferenceWidget};
use App\Http\Common\Helper;
use Illuminate\Support\Facades\{Auth, Gate, Storage};

class BannerController extends Controller
{
    public $folder_name = 'banner'; // For view routes and file calling and saving
    public $module_name = 'Banner'; // For toast And page header
    public $module_slug; // check module slug
    public $input_elements;

    public function __construct(array $attributes = array())
    {
        $this->middleware(function ($request, $next) {

            $input_elements = array();
            $this->module_slug = Helper::module_chk();

            if (Gate::check($this->module_slug.'-widgets-banner-banner_type')) {
                $input_element = array_push($input_elements, [
                    "label" => "Select Banner Type",
                    "element_type" => "dropdown",
                    "name" => "type",
                    "options" => [
                        [
                            "name" => "Use Image",
                            "type" => "image",
                        ],
                        [
                            "name" => "Default Banner",
                            "type" => "default",
                        ],
                        [
                            "name" => "Search Banner",
                            "type" => "web-search",
                        ]
                    ],
                    "value_element" => "type",
                    "select_element" => "type",
                    "label_element" => "name",
                    "additional_ids" => ["id_1", "id_2"],
                    "additional_classes" => ["class_1", "class_2"],
                    "html_params" => ["required" => "required"],
                ]);
            }

            if (Gate::check($this->module_slug.'-widgets-banner-meta_text')) {
                $input_element = array_push($input_elements, [
                    "element_type" => "input",
                    "input_type" => "text",
                    "label" => "Dynamic Text (Use rotating text banner by adding {placeholder} key)",
                    "name" => "meta_text",
                    "placeholder" => "Enter Dynamic Text i.e HealthCheck,Heart Rate",
                    "additional_ids" => [],
                    "additional_classes" => [],
                    "html_params" => [],
                ]);
            }

            if (Gate::check($this->module_slug.'-widgets-banner-banner_color')) {
                $input_element = array_push($input_elements, [
                    "element_type" => "input",
                    "input_type" => "color",
                    "label" => "Banner Color Outer",
                    "name" => "banner_color",
                    "placeholder" => "Enter Banner Color",
                    "additional_ids" => [],
                    "additional_classes" => [],
                    "html_params" => [],
                ]);

                $input_element = array_push($input_elements, [
                    "element_type" => "input",
                    "input_type" => "color",
                    "label" => "Banner Color Inner",
                    "name" => "banner_color_inner",
                    "placeholder" => "Enter Banner Color",
                    "additional_ids" => [],
                    "additional_classes" => [],
                    "html_params" => [],
                ]);
            }

            if (Gate::check($this->module_slug.'-widgets-banner-banner_image')) {
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
            if (Gate::check($this->module_slug.'-widgets-banner-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check($this->module_slug.'-widgets-banner-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check($this->module_slug.'-widgets-banner-web_show')) {
                $data['include_is_web_show_radio'] = 1;
            }
            $input_elements = $this->input_elements;
            return view('general_crud.general_widget_view',$data)->with(compact('input_elements'));
        }
    }

    public function form(Request $request,$reference_id){
        $data = null;
        if($request->hasFile('image')){
            $folder=$this->folder_name;
            $key="image";
            $data['image'] = $this->S3UploaderSpecificKey($key,$request,$folder);
        }
        if ($request->has('type')) {
            $data['type'] = $request->type;
        }
        if ($request->has('meta_text')) {
            $data['meta_text'] = $request->meta_text;
        }
        if ($request->has('reference_description')) {
            $data['description'] = $request->reference_description;
        }
        if ($request->has('banner_color')) {
            $data['banner_color'] = $request->banner_color;
            $data['banner_color_inner'] = $request->banner_color_inner;
        }
        if ($request->has('status')) {
            $data['status'] = $request->status;
        }
        if ($request->has('is_mobile_show')) {
            $data['is_mobile_show'] = $request->is_mobile_show;
        } else {
            $data['is_mobile_show'] = 1;
        }
        if ($request->has('type')) {
            $data['is_web_show'] = $request->is_web_show ?? 1;
        }

        $this->updateGeneralWidgetData($request, $reference_id);
        if ($data) {
            MainModel::updateOrCreate(['reference_id' => $reference_id], $data);
        }
        Helper::toast('success',$this->module_name.' created.');
        return redirect()->route('widget-'.$this->folder_name.'-index', ['reference_id' => $reference_id]);
    }
}
