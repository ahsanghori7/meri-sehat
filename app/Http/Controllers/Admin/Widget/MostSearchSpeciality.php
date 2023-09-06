<?php

namespace App\Http\Controllers\Admin\Widget;

use App\Http\Common\Constant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\{Settings,Speciality,ReferenceWidget, WidgetMostSearchSpeciality as MainModel};
use App\Http\Common\Helper;

class MostSearchSpeciality extends Controller
{
    public $folder_name = 'most-searched-specialties'; // For view routes and file calling and saving
    public $module_name = 'Most Search Speciality'; // For toast And page header
    public $module_slug; // check module slug

    public function __construct(array $attributes = array())
    {
        $this->module_slug = Helper::module_chk();
    }

    public function index(Request $request,$reference_id){
        if($request->isMethod('post')){
            return $this->form($request,$reference_id);
        }else{
            $getReferenceWidget = ReferenceWidget::find($reference_id);
            $selectedLanguage = $getReferenceWidget->page->lang_id;
            $data['reference_id'] = $reference_id;
            $data['page_header'] = "Edit ".$this->module_name;
            $data['result'] = MainModel::where(['reference_id' => $reference_id])->first() ?? null ;
            $data['reference'] = $getReferenceWidget;
            $data['specialities'] = Speciality::where(['status' => true, 'lang_id' => $selectedLanguage])
            ->where(function($query) use($selectedLanguage){
                if($selectedLanguage != 1){
                    $query->whereHas('translationOf.doctorSpecialities');
                }else{
                    $query->whereHas('doctorSpecialities');
                }
            })->get();
            $this->module_slug = Helper::module_chk($reference_id);
            $data['folder_name'] = $this->folder_name;
            $data['module_name'] = $this->module_name;
            $data['module_slug'] = $this->module_slug;
            $data['include_status_radio'] = 0;
            $data['include_is_mobile_show_radio'] = 0;
            $data['include_is_web_show_radio'] = 0;
            if (Gate::check($this->module_slug.'-widgets-most-searched-specialities-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check($this->module_slug.'-widgets-most-searched-specialities-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check($this->module_slug.'-widgets-most-searched-specialities-web_show')) {
                $data['include_is_web_show_radio'] = 1;
            }
            $file = "admin.widget.".$this->folder_name.".form";
            return view($file,$data);
        }
    }

    public function form(Request $request,$reference_id, $id = null){
        $this->updateGeneralWidgetData($request, $reference_id);
        $getMainModelDetails = MainModel::where(['reference_id' => $reference_id])->get();
        $data = null;
        if ($reference_id) {
            $data['reference_id'] = $reference_id;
        }
        if ($request->has('speciality_id')) {
            $data['speciality_id'] = $request->speciality_id;
        }
        if ($request->has('status')) {
            $data['status'] = $request->status ?? 1;
        }

        if(count($getMainModelDetails)){
            MainModel::where(['reference_id' => $reference_id])->delete();
        }
        foreach($data['speciality_id'] as $speciality)
        {
            if ($reference_id) {
                $data['reference_id'] = $reference_id;
            }
            if ($speciality) {
                $data['speciality_id'] = $speciality;
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
                MainModel::create($data);
            }
        }
        Helper::toast('success',$this->module_name.' created.');
        return redirect()->route('widget-'.$this->folder_name.'-index', ['reference_id' => $reference_id]);
    }
}
