<?php

namespace App\Http\Controllers\Admin\Widget;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\{Speciality, ReferenceWidget, WidgetSpeciality as MainModel};
use App\Http\Common\Helper;

class TagSpecialityController extends Controller
{

    public $folder_name = 'speciality'; // For view routes and file calling and saving
    public $module_name = 'Speciality'; // For toast And page header
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
            $getReferenceWidget = ReferenceWidget::find($reference_id);
            $selectedLanguage = $getReferenceWidget->page->lang_id;
            $data['selected_speciality_id'] = array();
            $data['reference_id'] = $reference_id;
            $this->module_slug = Helper::module_chk($reference_id);
            $data['folder_name'] = $this->folder_name;
            $data['module_name'] = "Edit ".$this->module_name;
            $data['module_slug'] = $this->module_slug;
            $data['result'] = MainModel::where(['reference_id' => $reference_id])->get() ?? null ;
            $data['reference'] = $getReferenceWidget;
            $data['speciality'] = Speciality::where(['status' => true, 'lang_id' => $selectedLanguage])
            ->where(function($query) use($selectedLanguage){
                if($selectedLanguage != 1){
                    $query->whereHas('translationOf.doctorSpecialities');
                }else{
                    $query->whereHas('doctorSpecialities');
                }
            })->get();
            $data['include_status_radio'] = 0;
            $data['include_is_mobile_show_radio'] = 0;
            $data['include_is_web_show_radio'] = 0;

            if (Gate::check($this->module_slug.'-widgets-speciality-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check($this->module_slug.'-widgets-speciality-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check($this->module_slug.'-widgets-speciality-web_show')) {
                $data['include_is_web_show_radio'] = 1;
            }
            $file = 'admin.widget.'.$this->folder_name.'.form';

            foreach($data['result'] as $item){
                array_push($data['selected_speciality_id'],$item->speciality_id);
            }
            return view($file, $data);
        }
    }

    public function form(Request $request,$reference_id){
        $this->updateGeneralWidgetData($request, $reference_id);
        $getMainModelDetails = MainModel::where(['reference_id' => $reference_id])->get();
        if(count($getMainModelDetails)){
            MainModel::where(['reference_id' => $reference_id])->delete();
        }
        $data = null;
        $data = $request->spaciality;
        foreach($data as $key => $value){
            $data['sequence'] = ++$key;
            if ($reference_id) {
                $data['reference_id'] = $reference_id;
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
            if ($value) {
                $data['speciality_id'] = $value;
            }
            if ($data) {
                MainModel::create($data);
            }
        }
        Helper::toast('success',$this->module_name.' created.');
        return redirect()->route('widget-'.$this->folder_name.'-index', ['reference_id' => $reference_id]);
    }
}
