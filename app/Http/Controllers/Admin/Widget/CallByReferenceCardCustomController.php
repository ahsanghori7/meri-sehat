<?php

namespace App\Http\Controllers\Admin\Widget;

use App\Http\Common\Helper;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Models\{ReferenceWidget, WidgetCallByReferenceCard as MainModel};
use Illuminate\Http\Request;

class CallByReferenceCardCustomController extends Controller
{
    public $folder_name = 'call-by-reference-card-custom'; // For view routes and file calling and saving
    public $module_name = 'Call by Reference Card Custom'; // For toast And page header
    public $module_slug; // check module slug
    public $input_elements;
    public $types = [
        [
            "type" => "single-column",
            "name" => "Single Column",
        ],
        [
            "type" => "single-column-cards",
            "name" => "Single Column With Cards",
        ],
        [
            "type" => "double-column",
            "name" => "Double Column",
        ],
        [
            "type" => "double-column-cards",
            "name" => "Double Column With Cards",
        ]
    ];

    public $card_types = [
        [
            "type" => "default",
            "name" => "Default",
        ],
        [
            "type" => "trusted",
            "name" => "Trusted",
        ],
        [
            "type" => "doctor-waiting",
            "name" => "Doctor Waiting",
        ],
        [
            "type" => "tracking",
            "name" => "Tracking",
        ],
        [
            "type" => "well-experts",
            "name" => "Well Experts",
        ]
    ];

    public $home_types = [
        [
            "type" => "default",
            "name" => "Default",
        ],
        [
            "type" => "looking-for",
            "name" => "Looking For",
        ],
        [
            "type" => "doctor-waiting",
            "name" => "Doctor Waiting",
        ],
        [
            "type" => "subscription-slider",
            "name" => "Subscription Slider",
        ],
        [
            "type" => "doctor-about",
            "name" => "Doctor-About",
        ],
        [
            "type" => "health-today",
            "name" => "Health Today",
        ],
        [
            "type" => "health-package",
            "name" => "Health Package",
        ],
        [
            "type" => "sehat-scan",
            "name" => "Sehat Scan",
        ],
        [
            "type" => "discover-wellness",
            "name" => "Discover Wellness",
        ],
        [
            "type" => "pmc-certified",
            "name" => "PMC Certified",
        ],
        [
            "type" => "footer-card",
            "name" => "Footer Card",
        ],
        [
            "type" => "help-support",
            "name" => "Help Support",
        ]

    ];

    public function __construct(array $attributes = array())
    {
        $this->module_slug = Helper::module_chk();
    }

    public function index(Request $request, $reference_id)
    {
        if ($request->isMethod('post')) {
            return $this->form($request, $reference_id);
        } else {
            $this->module_slug = Helper::module_chk($reference_id);
            $data['reference_id'] = $reference_id;
            $data['folder_name'] = $this->folder_name;
            $data['module_name'] = "Edit ".$this->module_name;
            $data['types'] = $this->types;
            $data['card_types'] = $this->card_types;
            $data['home_types'] = $this->home_types;
            $data['result'] = MainModel::where(['reference_id' => $reference_id])->get() ?? null;
            $data['reference'] = ReferenceWidget::find($reference_id);
            $data['module_slug'] = $this->module_slug;
            $data['include_status_radio'] = 0;
            $data['include_is_mobile_show_radio'] = 0;
            $data['include_is_web_show_radio'] = 0;
            if (Gate::check($this->module_slug.'-widgets-call-by-reference-card-custom-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check($this->module_slug.'-widgets-call-by-reference-card-custom-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check($this->module_slug.'-widgets-call-by-reference-card-custom-web_show')) {
                $data['include_is_web_show_radio'] = 1;
            }
            $file = 'admin.widget.'.$this->folder_name.'.form';
            return view($file, $data);
        }
    }

    public function form(Request $request, $reference_id)
    {
        $this->updateGeneralWidgetData($request, $reference_id);
        $getMainModels = MainModel::where('reference_id', $reference_id)->get();
        if(count($getMainModels)){
            foreach($getMainModels as $key => $getMainModel){
                if ($request->heading[$key]) {
                    $data['heading'] = $request->heading[$key];
                }
                if ($request->sub_head[$key]) {
                    $data['sub_head'] = $request->sub_head[$key];
                }
                if ($request->description[$key]) {
                    $data['description'] = $request->description[$key];
                }
                if ($request->redirect_url[$key]) {
                    $data['redirect_url'] = $request->redirect_url[$key];
                }
                if ($request->button_text[$key]) {
                    $data['button_text'] = $request->button_text[$key];
                }
                if ($request->has('card_type')) {
                    $data['card_type'] = $request->card_type;
                }
                if ($request->has('home_type')) {
                    $data['home_type'] = $request->home_type;
                }
                if ($request->has('type')) {
                    $data['type'] = $request->type;
                }
//                if ($request->card_color[$key]) {
//                    $data['card_color'] = $request->card_color[$key];
//                }
//                if ($request->card_inner_color[$key]) {
//                    $data['card_inner_color'] = $request->card_inner_color[$key];
//                }
                if ($request->status) {
                    $data['status'] = $request->status ?? 1;
                }
                if ($request->has('is_mobile_show') && $request->is_mobile_show == 1) {
                    $data['is_mobile_show'] = 1;
                } elseif ($request->has('is_mobile_show') && $request->is_mobile_show == 0) {
                    $data['is_mobile_show'] = 0;
                }
                if ($request->has('is_web_show') && $request->is_web_show == 1) {
                    $data['is_web_show'] = 1;
                } elseif ($request->has('is_web_show') && $request->is_web_show == 0) {
                    $data['is_web_show'] = 0;
                }
                $data['reference_id'] = $reference_id;

                if($request->has('card_1_head')){
                    $data['card_1_head'] = $request->card_1_head;
                }
                if($request->hasFile('card_1_icon')){
                    $folder=$this->folder_name;
//                    $data['card_1_icon'] = $this->S3UploaderSpecificKey('card_1_icon',$request, $folder);
                    $request->validate([
                        "card_1_icon" => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
                    ]);
                    $path = Storage::disk('s3')->put($folder,$request->card_1_icon);
                    $data['card_1_icon'] = $path;
                }
                if($request->has('card_1_desc')){
                    $data['card_1_desc'] = $request->card_1_desc;
                }
                if($request->has('card_1_link')){
                    $data['card_1_link'] = $request->card_1_link;
                }

                if($request->has('card_1_color')){
                    $data['card_1_color'] = $request->card_1_color;
                }

                if($request->has('card_1_inner_color')){
                    $data['card_1_inner_color'] = $request->card_1_inner_color;
                }

                if($request->has('card_2_head')){
                    $data['card_2_head'] = $request->card_2_head;
                }
                if($request->hasFile('card_2_icon')){
                    $folder=$this->folder_name;
//                    $data['card_2_icon'] = $this->S3UploaderSpecificKey('card_2_icon',$request, $folder);
                    $request->validate([
                        "card_2_icon" => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
                    ]);
                    $path = Storage::disk('s3')->put($folder,$request->card_2_icon);
                    $data['card_2_icon'] = $path;
                }
                if($request->has('card_2_desc')){
                    $data['card_2_desc'] = $request->card_2_desc;
                }
                if($request->has('card_2_link')){
                    $data['card_2_link'] = $request->card_2_link;
                }

                if($request->has('card_2_color')){
                    $data['card_2_color'] = $request->card_2_color;
                }

                if($request->has('card_2_inner_color')){
                    $data['card_2_inner_color'] = $request->card_2_inner_color;
                }

                if($request->has('card_3_head')){
                    $data['card_3_head'] = $request->card_3_head;
                }
                if($request->hasFile('card_3_icon')){
                    $folder=$this->folder_name;
//                    $data['card_3_icon'] = $this->S3UploaderSpecificKey('card_3_icon',$request, $folder);
                    $request->validate([
                        "card_3_icon" => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
                    ]);
                    $path = Storage::disk('s3')->put($folder,$request->card_3_icon);
                    $data['card_3_icon'] = $path;
                }
                if($request->has('card_3_desc')){
                    $data['card_3_desc'] = $request->card_3_desc;
                }
                if($request->has('card_3_link')){
                    $data['card_3_link'] = $request->card_3_link;
                }

                if($request->has('card_3_color')){
                    $data['card_3_color'] = $request->card_3_color;
                }

                if($request->has('card_3_inner_color')){
                    $data['card_3_inner_color'] = $request->card_3_inner_color;
                }

                if($request->has('card_4_head')){
                    $data['card_4_head'] = $request->card_4_head;
                }
                if($request->hasFile('card_4_icon')){
                    $folder=$this->folder_name;
//                    $data['card_4_icon'] = $this->S3UploaderSpecificKey('card_4_icon',$request, $folder);
                    $request->validate([
                        "card_4_icon" => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
                    ]);
                    $path = Storage::disk('s3')->put($folder,$request->card_4_icon);
                    $data['card_4_icon'] = $path;
                }
                if($request->has('card_4_desc')){
                    $data['card_4_desc'] = $request->card_4_desc;
                }
                if($request->has('card_4_link')){
                    $data['card_4_link'] = $request->card_4_link;
                }

                if($request->has('card_4_color')){
                    $data['card_4_color'] = $request->card_4_color;
                }

                if($request->has('card_4_inner_color')){
                    $data['card_4_inner_color'] = $request->card_4_inner_color;
                }

                if($request->has('card_5_head')){
                    $data['card_5_head'] = $request->card_5_head;
                }
                if($request->hasFile('card_5_icon')){
                    $folder=$this->folder_name;
//                    $data['card_5_icon'] = $this->S3UploaderSpecificKey('card_5_icon',$request, $folder);
                    $request->validate([
                        "card_5_icon" => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
                    ]);
                    $path = Storage::disk('s3')->put($folder,$request->card_5_icon);
                    $data['card_5_icon'] = $path;
                }
                if($request->has('card_5_desc')){
                    $data['card_5_desc'] = $request->card_5_desc;
                }
                if($request->has('card_5_link')){
                    $data['card_5_link'] = $request->card_5_link;
                }

                if($request->has('card_5_color')){
                    $data['card_5_color'] = $request->card_5_color;
                }

                if($request->has('card_5_inner_color')){
                    $data['card_5_inner_color'] = $request->card_5_inner_color;
                }

                if($request->has('card_6_head')){
                    $data['card_6_head'] = $request->card_6_head;
                }
                if($request->hasFile('card_6_icon')){
                    $folder=$this->folder_name;
//                    $data['card_6_icon'] = $this->S3UploaderSpecificKey('card_6_icon',$request, $folder);
                    $request->validate([
                        "card_6_icon" => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
                    ]);
                    $path = Storage::disk('s3')->put($folder,$request->card_6_icon);
                    $data['card_6_icon'] = $path;
                }
                if($request->has('card_6_desc')){
                    $data['card_6_desc'] = $request->card_6_desc;
                }
                if($request->has('card_6_link')){
                    $data['card_6_link'] = $request->card_6_link;
                }

                if($request->has('card_6_color')){
                    $data['card_6_color'] = $request->card_6_color;
                }

                if($request->has('card_6_inner_color')){
                    $data['card_6_inner_color'] = $request->card_6_inner_color;
                }

                if($request->hasFile('image') && isset($request->image[$key])){
                    $folder=$this->folder_name;
//                    $key="image";
//                    $data['image'] = $this->S3UploaderSpecificKey($key,$request,$folder);
                    $request->validate([
                        "image.*" => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
                    ]);
                    $path = Storage::disk('s3')->put($folder,$request->image[$key]);
                    $data['image'] = $path;
                }
                $getMainModel->update($data);
            }
            if (count($request->heading) > count($getMainModels)) {
                for ($key = count($getMainModels); $key < count($request->heading); $key++) {
                    if ($request->heading[$key]) {
                        $data['heading'] = $request->heading[$key];
                    }
                    if ($request->sub_head[$key]) {
                        $data['sub_head'] = $request->sub_head[$key];
                    }
                    if ($request->description[$key]) {
                        $data['description'] = $request->description[$key];
                    }
                    if ($request->redirect_url[$key]) {
                        $data['redirect_url'] = $request->redirect_url[$key];
                    }
                    if ($request->button_text[$key]) {
                        $data['button_text'] = $request->button_text[$key];
                    }
                    if ($request->card_type[$key]) {
                        $data['card_type'] = $request->card_type[$key];
                    }
                    if ($request->type) {
                        $data['type'] = $request->type;
                    }
                    if ($request->has('home_type')) {
                        $data['home_type'] = $request->home_type;
                    }
                    if ($request->status) {
                        $data['status'] = $request->status ?? 1;
                    }
                    if ($request->has('is_mobile_show') && $request->is_mobile_show == 1) {
                        $data['is_mobile_show'] = 1;
                    } else {
                        $data['is_mobile_show'] = 0;
                    }
                    if ($request->has('is_web_show') && $request->is_web_show == 1) {
                        $data['is_web_show'] = $request->is_web_show ?? 1;
                    } else {
                        $data['is_web_show'] = 0;
                    }
                    if ($request->card_color[$key]) {
                        $data['card_color'] = $request->card_color[$key];
                    }
                    if ($request->card_inner_color[$key]) {
                        $data['card_inner_color'] = $request->card_inner_color[$key];
                    }
                    $data['reference_id'] = $reference_id;
                    if($request->hasFile('single_column_image')){
                        $folder=$this->folder_name;
//                        $key="single_column_image";
//                        $data['image'] = $this->S3UploaderSpecificKey($key,$request,$folder);
                        $request->validate([
                            "image" => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
                        ]);
                        $path = Storage::disk('s3')->put($folder,$request->image);
                        $data['image'] = $path;
                    }

                    if($request->has('card_1_head')){
                        $data['card_1_head'] = $request->card_1_head;
                    }
                    if($request->hasFile('card_1_icon')){
                        $folder=$this->folder_name;
//                        $data['card_1_icon'] = $this->S3UploaderSpecificKey('card_1_icon',$request, $folder);
                        $request->validate([
                            "card_1_icon" => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
                        ]);
                        $path = Storage::disk('s3')->put($folder,$request->card_1_icon);
                        $data['card_1_icon'] = $path;
                    }
                    if($request->has('card_1_desc')){
                        $data['card_1_desc'] = $request->card_1_desc;
                    }
                    if($request->has('card_1_link')){
                        $data['card_1_link'] = $request->card_1_link;
                    }

                    if($request->has('card_2_head')){
                        $data['card_2_head'] = $request->card_2_head;
                    }
                    if($request->hasFile('card_2_icon')){
                        $folder=$this->folder_name;
//                        $data['card_2_icon'] = $this->S3UploaderSpecificKey('card_2_icon',$request, $folder);
                        $request->validate([
                            "card_2_icon" => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
                        ]);
                        $path = Storage::disk('s3')->put($folder,$request->card_2_icon);
                        $data['card_2_icon'] = $path;
                    }
                    if($request->has('card_2_desc')){
                        $data['card_2_desc'] = $request->card_2_desc;
                    }
                    if($request->has('card_2_link')){
                        $data['card_2_link'] = $request->card_2_link;
                    }

                    if($request->has('card_3_head')){
                        $data['card_3_head'] = $request->card_3_head;
                    }
                    if($request->hasFile('card_3_icon')){
                        $folder=$this->folder_name;
//                        $data['card_3_icon'] = $this->S3UploaderSpecificKey('card_3_icon',$request, $folder);
                        $request->validate([
                            "card_3_icon" => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
                        ]);
                        $path = Storage::disk('s3')->put($folder,$request->card_3_icon);
                        $data['card_3_icon'] = $path;
                    }
                    if($request->has('card_3_desc')){
                        $data['card_3_desc'] = $request->card_3_desc;
                    }
                    if($request->has('card_3_link')){
                        $data['card_3_link'] = $request->card_3_link;
                    }

                    if($request->has('card_4_head')){
                        $data['card_4_head'] = $request->card_4_head;
                    }
                    if($request->hasFile('card_4_icon')){
                        $folder=$this->folder_name;
//                        $data['card_4_icon'] = $this->S3UploaderSpecificKey('card_4_icon',$request, $folder);
                        $request->validate([
                            "card_4_icon" => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
                        ]);
                        $path = Storage::disk('s3')->put($folder,$request->card_4_icon);
                        $data['card_4_icon'] = $path;
                    }
                    if($request->has('card_4_desc')){
                        $data['card_4_desc'] = $request->card_4_desc;
                    }
                    if($request->has('card_4_link')){
                        $data['card_4_link'] = $request->card_4_link;
                    }

                    if($request->hasFile('image') && isset($request->image[$key])){
                        $folder=$this->folder_name;
//                        $key="image";
//                        $data['image'] = $this->S3UploaderSpecificKey($key,$request,$folder);
                        $request->validate([
                            "image.*" => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
                        ]);
                        $path = Storage::disk('s3')->put($folder,$request->image[$key]);
                        $data['image'] = $path;
                    }
                    MainModel::create($data);
                }
            }
        }
        // for edit
        else{
            foreach($request->heading as $key => $heading){
                if ($request->heading[$key]) {
                    $data['heading'] = $request->heading[$key];
                }
                if ($request->sub_head[$key]) {
                    $data['sub_head'] = $request->sub_head[$key];
                }
                if ($request->description[$key]) {
                    $data['description'] = $request->description[$key];
                }
                if ($request->redirect_url[$key]) {
                    $data['redirect_url'] = $request->redirect_url[$key];
                }
                if ($request->button_text[$key]) {
                    $data['button_text'] = $request->button_text[$key];
                }
                if ($request->card_type[$key]) {
                    $data['card_type'] = $request->card_type[$key];
                }
                if ($request->has('type')) {
                    $data['type'] = $request->type;
                }
                if ($request->has('home_type')) {
                    $data['home_type'] = $request->home_type;
                }
                if ($request->status) {
                    $data['status'] = $request->status ?? 1;
                } else {
                    $data['status'] = 0;
                }
                if ($request->has('is_mobile_show') && $request->is_mobile_show == 1) {
                    $data['is_mobile_show'] = $request->is_mobile_show ?? 1;
                } else {
                    $data['is_mobile_show'] = 0;
                }
                if ($request->has('is_web_show') && $request->is_web_show == 1) {
                    $data['is_web_show'] = $request->is_web_show ?? 1;
                } else {
                    $data['is_web_show'] = 0;
                }


                if($request->has('card_1_head')){
                    $data['card_1_head'] = $request->card_1_head;
                }
                if($request->hasFile('card_1_icon')){
                    $folder=$this->folder_name;
//                    $data['card_1_icon'] = $this->S3UploaderSpecificKey('card_1_icon',$request, $folder);
                    $request->validate([
                        "card_1_icon" => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
                    ]);
                    $path = Storage::disk('s3')->put($folder,$request->card_1_icon);
                    $data['card_1_icon'] = $path;
                }
                if($request->has('card_1_desc')){
                    $data['card_1_desc'] = $request->card_1_desc;
                }
                if($request->has('card_1_link')){
                    $data['card_1_link'] = $request->card_1_link;
                }
                if($request->has('card_1_color')){
                    $data['card_1_color'] = $request->card_1_color;
                }
                if($request->has('card_1_inner_color')){
                    $data['card_1_inner_color'] = $request->card_1_inner_color;
                }

                if($request->has('card_2_head')){
                    $data['card_2_head'] = $request->card_2_head;
                }
                if($request->hasFile('card_2_icon')){
                    $folder=$this->folder_name;
//                    $data['card_2_icon'] = $this->S3UploaderSpecificKey('card_2_icon',$request, $folder);
                    $request->validate([
                        "card_2_icon" => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
                    ]);
                    $path = Storage::disk('s3')->put($folder,$request->card_2_icon);
                    $data['card_2_icon'] = $path;
                }
                if($request->has('card_2_desc')){
                    $data['card_2_desc'] = $request->card_2_desc;
                }
                if($request->has('card_2_link')){
                    $data['card_2_link'] = $request->card_2_link;
                }
                if($request->has('card_2_color')){
                    $data['card_2_color'] = $request->card_2_color;
                }
                if($request->has('card_2_inner_color')){
                    $data['card_2_inner_color'] = $request->card_2_inner_color;
                }

                if($request->has('card_3_head')){
                    $data['card_3_head'] = $request->card_3_head;
                }
                if($request->hasFile('card_3_icon')){
                    $folder=$this->folder_name;
//                    $data['card_3_icon'] = $this->S3UploaderSpecificKey('card_3_icon',$request, $folder);
                    $request->validate([
                        "card_3_icon" => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
                    ]);
                    $path = Storage::disk('s3')->put($folder,$request->card_3_icon);
                    $data['card_3_icon'] = $path;
                }
                if($request->has('card_3_desc')){
                    $data['card_3_desc'] = $request->card_3_desc;
                }
                if($request->has('card_3_link')){
                    $data['card_3_link'] = $request->card_3_link;
                }
                if($request->has('card_3_color')){
                    $data['card_3_color'] = $request->card_3_color;
                }
                if($request->has('card_3_inner_color')){
                    $data['card_3_inner_color'] = $request->card_3_inner_color;
                }

                if($request->has('card_4_head')){
                    $data['card_4_head'] = $request->card_4_head;
                }
                if($request->hasFile('card_4_icon')){
                    $folder=$this->folder_name;
//                    $data['card_4_icon'] = $this->S3UploaderSpecificKey('card_4_icon',$request, $folder);
                    $request->validate([
                        "card_4_icon" => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
                    ]);
                    $path = Storage::disk('s3')->put($folder,$request->card_4_icon);
                    $data['card_4_icon'] = $path;
                }
                if($request->has('card_4_desc')){
                    $data['card_4_desc'] = $request->card_4_desc;
                }
                if($request->has('card_4_link')){
                    $data['card_4_link'] = $request->card_4_link;
                }
                if($request->has('card_4_color')){
                    $data['card_4_color'] = $request->card_4_color;
                }
                if($request->has('card_4_inner_color')){
                    $data['card_4_inner_color'] = $request->card_4_inner_color;
                }

                if($request->has('card_5_head')){
                    $data['card_5_head'] = $request->card_5_head;
                }
                if($request->hasFile('card_5_icon')){
                    $folder=$this->folder_name;
//                    $data['card_5_icon'] = $this->S3UploaderSpecificKey('card_5_icon',$request, $folder);
                    $request->validate([
                        "card_5_icon" => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
                    ]);
                    $path = Storage::disk('s3')->put($folder,$request->card_5_icon);
                    $data['card_5_icon'] = $path;
                }
                if($request->has('card_5_desc')){
                    $data['card_5_desc'] = $request->card_5_desc;
                }
                if($request->has('card_5_link')){
                    $data['card_5_link'] = $request->card_5_link;
                }
                if($request->has('card_5_color')){
                    $data['card_5_color'] = $request->card_5_color;
                }
                if($request->has('card_5_inner_color')){
                    $data['card_5_inner_color'] = $request->card_5_inner_color;
                }

                if($request->has('card_6_head')){
                    $data['card_6_head'] = $request->card_6_head;
                }
                if($request->hasFile('card_6_icon')){
                    $folder=$this->folder_name;
//                    $data['card_6_icon'] = $this->S3UploaderSpecificKey('card_6_icon',$request, $folder);
                    $request->validate([
                        "card_6_icon" => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
                    ]);
                    $path = Storage::disk('s3')->put($folder,$request->card_6_icon);
                    $data['card_6_icon'] = $path;
                }
                if($request->has('card_6_desc')){
                    $data['card_6_desc'] = $request->card_6_desc;
                }
                if($request->has('card_6_link')){
                    $data['card_6_link'] = $request->card_6_link;
                }
                if($request->has('card_6_color')){
                    $data['card_6_color'] = $request->card_6_color;
                }
                if($request->has('card_6_inner_color')){
                    $data['card_6_inner_color'] = $request->card_6_inner_color;
                }
                if($request->hasFile('image')){
                    $folder=$this->folder_name;
//                    $key="image";
//                    $data['image'] = $this->S3UploaderSpecificKey($key,$request,$folder);
                    $request->validate([
                        "image.*" => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
                    ]);
                    $path = Storage::disk('s3')->put($folder,$request->image);
                    $data['image'] = $path;
                }
                $data['reference_id'] = $reference_id;
                MainModel::create($data);
            }
        }
        Helper::toast('success', $this->module_name . ' updated.');
        return redirect()->route('widget-' . $this->folder_name . '-index', ['reference_id' => $reference_id]);
    }
}
