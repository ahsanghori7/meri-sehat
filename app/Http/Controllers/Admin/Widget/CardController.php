<?php

namespace App\Http\Controllers\Admin\Widget;

use App\Http\Common\Constant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Article, ReferenceWidget, Topics, User, WidgetCard as MainModel};
use App\Http\Common\Helper;
use Illuminate\Support\Facades\Gate;

class CardController extends Controller
{
    public $folder_name = 'card'; // For view routes and file calling and saving
    public $module_name = 'Card'; // For toast And page header
    public $module_slug; // check module slug
    public $card_types = [
        [
            "type" => "doctor",
            "name" => "Doctor",
        ],
        [
            "type" => "topic",
            "name" => "Topic",
        ],
        [
            "type" => "article",
            "name" => "Article",
        ],
        [
            "type" => "wellness_experts",
            "name" => "Wellness Experts",
        ],
    ];

    public function __construct(array $attributes = array())
    {
        $this->module_slug = Helper::module_chk();
    }

    public function index(Request $request,$reference_id){
        if($request->isMethod('post')){
            return $this->form($request,$reference_id);
        }else{
            $getMainModel = MainModel::where(['reference_id' => $reference_id])->get();
            $getReferenceWidget = ReferenceWidget::find($reference_id);
            $selectedLanguage = $getReferenceWidget->page ? $getReferenceWidget->page->lang_id : null;
            $data['reference_id'] = $reference_id;
            $this->module_slug = Helper::module_chk($reference_id);
            $data['folder_name'] = $this->folder_name;
            $data['module_name'] = "Edit ".$this->module_name;
            $data['result'] = MainModel::where(['reference_id' => $reference_id])->first() ?? null ;
            $data['reference'] = ReferenceWidget::find($reference_id);
            $data['selected_parents'] = $getMainModel ? $getMainModel->pluck('parent_id')->toArray() : [];
            $data['card_types'] = $this->card_types;
            $data['articles'] = Article::where(['status' => true, 'lang_id' => $selectedLanguage])->get();
            $data['topics'] = Topics::where('status', true)
            ->whereHas('page', function($query) use($selectedLanguage){
                $query->where('lang_id', $selectedLanguage);
            })->get();
            $data['doctors'] = User::where(['status' => true, 'is_blocked' => false, 'role_id' => (new Constant)->DOCTOR_ROLE_ID])->where('city_id', '!=', null)->whereHas('doctorDetail')->get();
            $get_wellness_profile = User::where(['status' => true, 'is_blocked' => false, 'role_id' => (new Constant)->FITNESS_EXPERTS_ROLE_ID])->where('city_id', '!=', null)->whereHas('fitnessDetail', function($get_wellness_profile) {
                $get_wellness_profile = $get_wellness_profile->where('is_featured', 1)->where('status', 1);
            })->get();
            $data['wellness_experts'] = $get_wellness_profile;
            $data['module_slug'] = $this->module_slug;
            $data['include_status_radio'] = 0;
            $data['include_is_mobile_show_radio'] = 0;
            $data['include_is_web_show_radio'] = 0;

            if (Gate::check($this->module_slug.'-widgets-card-status') || Gate::check($this->module_slug.'-widgets-card-with-slider-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check($this->module_slug.'-widgets-card-mobile_show') || Gate::check($this->module_slug.'-widgets-card-with-slider-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check($this->module_slug.'-widgets-card-web_show') || Gate::check($this->module_slug.'-widgets-card-with-slider-web_show')) {
                $data['include_is_web_show_radio'] = 1;
            }
            $file = 'admin.widget.'.$this->folder_name.'.form';
            return view($file, $data);
        }
    }

    public function form(Request $request,$reference_id){
        $this->updateGeneralWidgetData($request, $reference_id);
        MainModel::where(['reference_id' => $reference_id])->delete();
//        $data = ($request->type == 'topic') ? $request->topic : ($request->article ? $request->article : $request->doctor);
        $data = '';
        if ($request->type == 'topic') {
            $data = $request->topic;
        } elseif ($request->type == 'doctor') {
            $data = $request->doctor;
        } elseif ($request->type == 'article') {
            $data = $request->article;
        } elseif ($request->type == 'wellness_experts') {
            $data = $request->wellness_experts;
        }
        if($data && count($data)){
            foreach($data as $key => $value){
                $data = null;
                if ($reference_id) {
                    $data['reference_id'] = $reference_id;
                }
                if ($request->has('type')) {
                    $data['type'] = $request->type;
                }
                if ($value) {
                    $data['parent_id'] = $value;
                }
                $data['sequence'] = $key + 1;
                if ($request->status) {
                    $data['status'] = $request->status ?? 1;
                }
                if ($request->is_mobile_show) {
                    $data['is_mobile_show'] = $request->is_mobile_show ?? 1;
                }
                if ($request->is_web_show) {
                    $data['is_web_show'] = $request->is_web_show ?? 1;
                }
                if ($data) {
                    MainModel::create($data);
                }
            }
        }else{
            Helper::toast('error',$this->module_name.' error.');
            return redirect()->back();
        }
        Helper::toast('success',$this->module_name.' created.');
        return redirect()->route('widget-'.$this->folder_name.'-index', ['reference_id' => $reference_id]);
    }
}
