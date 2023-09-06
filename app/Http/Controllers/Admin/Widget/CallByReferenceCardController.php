<?php

namespace App\Http\Controllers\Admin\Widget;

use App\Http\Common\Helper;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Models\{ReferenceWidget, WidgetCallByReferenceCard as MainModel};
use Illuminate\Http\Request;

class CallByReferenceCardController extends Controller
{
    public $folder_name = 'call-by-reference-card'; // For view routes and file calling and saving
    public $module_name = 'Call by Reference Card'; // For toast And page header
    public $module_slug; // check module slug
    public $input_elements;
    public $types = [
        [
            "type" => "single-column",
            "name" => "Single Column",
        ],
        [
            "type" => "double-column",
            "name" => "Double Column",
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
            $data['reference_id'] = $reference_id;
            $data['folder_name'] = $this->folder_name;
            $data['module_name'] = "Edit ".$this->module_name;
            $data['types'] = $this->types;
            $data['result'] = MainModel::where(['reference_id' => $reference_id])->get() ?? null;
            $data['reference'] = ReferenceWidget::find($reference_id);
            $this->module_slug = Helper::module_chk($reference_id);
            $data['folder_name'] = $this->folder_name;
            $data['module_slug'] = $this->module_slug;
            $data['include_status_radio'] = 0;
            $data['include_is_mobile_show_radio'] = 0;
            $data['include_is_web_show_radio'] = 0;
            if (Gate::check($this->module_slug.'-widgets-call-by-reference-card-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check($this->module_slug.'-widgets-call-by-reference-card-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check($this->module_slug.'-widgets-call-by-reference-card-web_show')) {
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
                if($request->type == 'single-column' || count($getMainModels) == 1){
                    if ($request->single_column_heading[$key]) {
                        $data['heading'] = $request->single_column_heading[$key];
                    }
                    if ($request->single_column_description[$key]) {
                        $data['description'] = $request->single_column_description[$key];
                    }
                    if ($request->single_column_redirect_url[$key]) {
                        $data['redirect_url'] = $request->single_column_redirect_url[$key];
                    }
                    if ($request->single_column_image_position[$key]) {
                        $data['image_position'] = $request->single_column_image_position[$key];
                    }
                    if ($request->single_column_button_text[$key]) {
                        $data['button_text'] = $request->single_column_button_text[$key];
                    }
                    if ($request->single_column_card_color[$key]) {
                        $data['card_color'] = $request->single_column_card_color[$key];
                    }
                    if ($request->status) {
                        $data['status'] = $request->status ?? 1;
                    }
                    if ($request->is_mobile_show) {
                        $data['is_mobile_show'] = $request->is_mobile_show ?? 1;
                    }
                    if ($request->is_web_show) {
                        $data['is_web_show'] = $request->is_web_show ?? 1;
                    }
                    $data['type'] = 'single-column';
                }else{
                    if ($request->heading[$key]) {
                        $data['heading'] = $request->heading[$key];
                    }
                    if ($request->description[$key]) {
                        $data['description'] = $request->description[$key];
                    }
                    if ($request->redirect_url[$key]) {
                        $data['redirect_url'] = $request->redirect_url[$key];
                    }
                    if ($request->image_position[$key]) {
                        $data['image_position'] = $request->image_position[$key];
                    }
                    if ($request->button_text[$key]) {
                        $data['button_text'] = $request->button_text[$key];
                    }
                    if ($request->card_color[$key]) {
                        $data['card_color'] = $request->card_color[$key];
                    }
                    if ($request->status) {
                        $data['status'] = $request->status ?? 1;
                    }
                    if ($request->is_mobile_show) {
                        $data['is_mobile_show'] = $request->is_mobile_show ?? 1;
                    }
                    if ($request->is_web_show) {
                        $data['is_web_show'] = $request->is_web_show ?? 1;
                    }
                    $data['type'] = 'double-column';
                }
                $data['reference_id'] = $reference_id;
                if($request->hasFile('single_column_image')){
                    $folder=$this->folder_name;
                    $request->validate([
                        "single_column_image" => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
                    ]);
                    $path = Storage::disk('s3')->put($folder,$request->single_column_image);
                    $data['image'] = $path;
                }
                if($request->hasFile('image') && isset($request->image[$key])){
                    $folder=$this->folder_name;
//                    $key="image[$key]";
//                    $data['image'] = $this->S3UploaderSpecificKey($key,$request,$folder);
                    $request->validate([
                        "image.*" => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
                    ]);
                    $path = Storage::disk('s3')->put($folder,$request->image[$key]);
                    $data['image'] = $path;
                }
                $getMainModel->update($data);
            }
        }
        // for edit
        else{
            if($request->type == 'single-column'){
                if ($request->single_column_heading[0]) {
                    $data['heading'] = $request->single_column_heading[0];
                }
                if ($request->single_column_description[0]) {
                    $data['description'] = $request->single_column_description[0];
                }
                if ($request->single_column_redirect_url[0]) {
                    $data['redirect_url'] = $request->single_column_redirect_url[0];
                }
                if ($request->single_column_image_position[0]) {
                    $data['image_position'] = $request->single_column_image_position[0];
                }
                if ($request->single_column_button_text[0]) {
                    $data['button_text'] = $request->single_column_button_text[0];
                }
                if ($request->single_column_card_color[0]) {
                    $data['card_color'] = $request->single_column_card_color[0];
                }
                if ($request->status) {
                    $data['status'] = $request->status ?? 1;
                }
                if ($request->is_mobile_show) {
                    $data['is_mobile_show'] = $request->is_mobile_show ?? 1;
                }
                if ($request->is_web_show) {
                    $data['is_web_show'] = $request->is_web_show ?? 1;
                }
                $data['type'] = 'single-column';
                if($request->hasFile('single_column_image')){
                    $folder=$this->folder_name;
//                    $key="single_column_image";
//                    $data['image'] = $this->S3UploaderSpecificKey($key,$request,$folder);
                    $request->validate([
                        "single_column_image" => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
                    ]);
                    $path = Storage::disk('s3')->put($folder,$request->single_column_image);
                    $data['image'] = $path;

                }
                $data['reference_id'] = $reference_id;
                MainModel::create($data);
            }else{
                foreach($request->heading as $key => $heading){

                    if ($request->heading[$key]) {
                        $data['heading'] = $request->heading[$key];
                    }
                    if ($request->description[$key]) {
                        $data['description'] = $request->description[$key];
                    }
                    if ($request->redirect_url[$key]) {
                        $data['redirect_url'] = $request->redirect_url[$key];
                    }
                    if ($request->image_position[$key]) {
                        $data['image_position'] = $request->image_position[$key];
                    }
                    if ($request->button_text[$key]) {
                        $data['button_text'] = $request->button_text[$key];
                    }
                    if ($request->card_color[$key]) {
                        $data['card_color'] = $request->card_color[$key];
                    }
                    if ($request->status) {
                        $data['status'] = $request->status ?? 1;
                    }
                    if ($request->is_mobile_show) {
                        $data['is_mobile_show'] = $request->is_mobile_show ?? 1;
                    }
                    if ($request->is_web_show) {
                        $data['is_web_show'] = $request->is_web_show ?? 1;
                    }
                    $data['type'] = 'double-column';
                    if($request->hasFile('image')){
                        $folder=$this->folder_name;
//                        $key="image";
//                        $data['image'] = $this->S3UploaderSpecificKey($key,$request,$folder);
                        $request->validate([
                            "image.*" => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
                        ]);
                        $path = Storage::disk('s3')->put($folder,$request->image[$key]);
                        $data['image'] = $path;

                    }
                    $data['reference_id'] = $reference_id;
                    MainModel::create($data);
                }
            }
        }
        Helper::toast('success', $this->module_name . ' updated.');
        return redirect()->route('widget-' . $this->folder_name . '-index', ['reference_id' => $reference_id]);
    }
}
