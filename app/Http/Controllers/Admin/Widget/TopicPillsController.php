<?php

namespace App\Http\Controllers\Admin\Widget;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{City, Disease, ReferenceWidget, Topics, WidgetTopicPill as MainModel};
use App\Http\Common\Helper;
use Illuminate\Support\Facades\Gate;

class TopicPillsController extends Controller
{

    public $folder_name = 'topic-pills'; // For view routes and file calling and saving
    public $module_name = 'Topic Pills'; // For toast And page header
    public $module_slug; // check module slug
    public $input_elements;
    public $parents = [
        [
            "type" => "topic",
            "name" => "Topic",
        ],
        [
            "type" => "city",
            "name" => "City",
        ],
        [
            "type" => "disease",
            "name" => "Disease"

        ]
    ];

    public function __construct(array $attributes = array())
    {
        $this->module_slug = Helper::module_chk();
    }

    public function index(Request $request,$reference_id){
        if($request->isMethod('post')){
            return $this->form($request,$reference_id);
        }else{
            $getReferenceWidget = ReferenceWidget::find($reference_id);
            $data['selected_topic_id'] = array();
            $data['reference_id'] = $reference_id;
            $this->module_slug = Helper::module_chk($reference_id);
            $data['folder_name'] = $this->folder_name;
            $data['module_name'] = "Edit ".$this->module_name;
            $data['module_slug'] = $this->module_slug;
            $data['parents'] = $this->parents;
            $data['result'] = MainModel::where(['reference_id' => $reference_id])->get() ?? null ;
            $data['reference'] = $getReferenceWidget;
            $selectedLanguage = $getReferenceWidget->page->lang_id;
            $data['topics'] = Topics::where('status', true)
            ->whereHas('page', function($query) use($selectedLanguage){
                $query->where('lang_id', $selectedLanguage);
            })->get();

            $data['cities'] = City::where(['status' => true, 'lang_id' => $selectedLanguage])
            ->where(function($query) use($selectedLanguage){
                if($selectedLanguage != 1){
                    $query->whereHas('translationOf.doctor');
                }else{
                    $query->whereHas('doctor');
                }
            })->get();
            $data['diseases'] = Disease::where(['status' => true, 'lang_id' => $selectedLanguage])->get();
            $file = 'admin.widget.'.$this->folder_name.'.form';
            $data['selected_cities'] = [];
            $data['selected_topics'] = [];
            $data['selected_disease'] = [];
            foreach($data['result'] as $item){
                if($item->type == 'city'){
                    array_push($data['selected_cities'], $item->parent_id);
                }else if ($item->type == 'disease'){
                    array_push($data['selected_disease'], $item->parent_id);
                }else{
                    array_push($data['selected_topics'], $item->parent_id);
                }
            }
            $data['include_status_radio'] = 0;
            $data['include_is_mobile_show_radio'] = 0;
            $data['include_is_web_show_radio'] = 0;
            if (Gate::check($this->module_slug.'-widgets-topic-pills-status')) {
                $data['include_status_radio'] = 1;
            }
            if (Gate::check($this->module_slug.'-widgets-topic-pills-mobile_show')) {
                $data['include_is_mobile_show_radio'] = 1;
            }
            if (Gate::check($this->module_slug.'-widgets-topic-pills-web_show')) {
                $data['include_is_web_show_radio'] = 1;
            }
            return view($file, $data);
        }
    }

    public function form(Request $request,$reference_id){
        $this->updateGeneralWidgetData($request, $reference_id);
        $getMainModelDetails = MainModel::where(['reference_id' => $reference_id])->get();
        $temp='';
        if($request->type == "topic"){
            $temp = $request->topics;
        }else if($request->type == "disease"){
            $temp = $request->disease;
        }else{
            $temp = $request->cities;
        }
        if(!$temp){
            Helper::toast('error', 'Topics or cities is required');
            return redirect()->back();
        }
        if(count($getMainModelDetails)){
            MainModel::where(['reference_id' => $reference_id])->delete();
        }
        foreach($temp as $key => $value){
            MainModel::create([
                'reference_id' => $reference_id,
                'type' => $request->type,
                'parent_id' => $temp[$key],
                'sequence' => ++$key,
                'status' => $request->status ?? 1,
                'is_mobile_show' => $request->is_mobile_show ?? 1,
                'is_web_show' => $request->is_web_show ?? 1,
            ]);
        }
        Helper::toast('success',$this->module_name.' created.');
        return redirect()->route('widget-'.$this->folder_name.'-index', ['reference_id' => $reference_id]);
    }

}
