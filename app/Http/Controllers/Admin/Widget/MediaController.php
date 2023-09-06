<?php

namespace App\Http\Controllers\Admin\Widget;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\{MediaLanguage, ReferenceWidget, WidgetMedia as MainModel};
use App\Http\Common\Helper;

class MediaController extends Controller
{
    public $folder_name = 'media'; // For view routes and file calling and saving
    public $module_name = 'Media'; // For toast And page header
    public $module_slug; // check module slug
    public $input_elements;

    public function __construct(array $attributes = array())
    {
        $this->module_slug = Helper::module_chk();
    }

    public function index(Request $request,$reference_id){
        if($request->isMethod('post')){
            return $this->form($request,$reference_id);
        }else{
            $data['reference_id'] = $reference_id;
            $data['page_header'] = "Edit ".$this->module_name;
            $data['result'] = MainModel::where(['reference_id' => $reference_id])->first() ?? null ;
            $data['videos'] = MainModel::where(['reference_id' => $reference_id])->get();
            $data['reference'] = ReferenceWidget::find($reference_id);
            $data['media_language'] = MediaLanguage::get();
            $this->module_slug = Helper::module_chk($reference_id);
            $data['folder_name'] = $this->folder_name;
            $data['module_slug'] = $this->module_slug;
            $data['include_status_radio'] = 0;
            $data['include_is_mobile_show_radio'] = 0;
            $data['include_is_web_show_radio'] = 0;
            if (Gate::check($this->module_slug.'-widgets-media-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check($this->module_slug.'-widgets-media-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check($this->module_slug.'-widgets-media-web_show')) {
                $data['include_is_web_show_radio'] = 1;
            }
            $file = "admin.widget.".$this->folder_name.".form";
            return view($file,$data);
        }
    }

    public function form(Request $request,$reference_id){
        $this->updateGeneralWidgetData($request, $reference_id);
        if($request->type == "image" && $request->hasFile('image')){
            $request->request->remove('language');
            if (!$request->has('language')) {
                $request->merge(['language' => 1]);
            }
            $folder=$this->folder_name;
            $data = null;
            if($request->hasFile('image')) {
                $source = $this->S3Uploader($request, $folder);
                $data['source'] = $source ?? null;
            }
            if ($request->has('language')) {
                $data['language_id'] = $request->language;
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
            if ($request->has('type')) {
                $data['type'] = $request->type;
            }
            if ($request->has('alt')) {
                $data['alt'] = $request->alt;
            }
            if ($data) {
                MainModel::updateOrCreate(
                    [
                        'reference_id' => $reference_id,
                    ], $data
                );
            }
        }else{
            $counter = count($request->language);
            $delete_old_records = MainModel::where('reference_id', $reference_id)->delete();
            for($sn=0; $sn < $counter; $sn++) {
                $source = $request->source;
                $new_records = new MainModel();
                if ($request->has('is_mobile_show')) {
                    $new_records->is_mobile_show = $request->is_mobile_show ?? 1;
                }
                if ($request->has('is_web_show')) {
                    $new_records->is_web_show = $request->is_web_show ?? 1;
                }
                if ($reference_id) {
                    $new_records->reference_id = $reference_id;
                }
                if ($request->language[$sn]) {
                    $new_records->language_id = $request->language[$sn];
                }
                $new_records->is_default = $request->is_default[$sn] ?? 0;
                if ($request->has('type')) {
                    $new_records->type = $request->type;
                }
                if ($request->has('alt')) {
                    $new_records->alt = $request->alt;
                }
                if ($request->redirect_url[$sn]) {
                    $new_records->source = $request->redirect_url[$sn] ?? null;
                }
                $new_records->save();

            }
        }
        Helper::toast('success',$this->module_name.' created.');
        return redirect()->route('widget-'.$this->folder_name.'-index', ['reference_id' => $reference_id]);
    }
}
