<?php

namespace App\Http\Controllers\Admin;

use App\Http\Common\Helper;
use App\Http\Controllers\Controller;
use App\Models\PromoCode as MainModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PromoCodeManagementController extends Controller
{
    public $folder_name = 'promocode'; // For view routes and file calling and saving
    public $module_name = 'PromoCode'; // For toast And page header

    public function view(Request $request)
    {
        if (Gate::denies('promo-code-management-view')) {
            abort(403);
        }
        if ($request->isMethod('post')) {
            foreach ($request->sequence as $key => $id) {
                $sequence = $key + 1;
                MainModel::find($id)->update(['sequence' => str_pad($sequence,2,"0",STR_PAD_LEFT)]);
            }
            return back();
        } else {
            $data['folder_name'] = $this->folder_name;
            $data['module_name'] = $this->module_name;
            $data['result'] = MainModel::get();
            $file = "admin." . $this->folder_name . ".view";
            return view($file, $data);
        }
    }

    public function form(Request $request, $id = null)
    {
        $sequence = MainModel::count();
        $sequence = $sequence + 1;
        if($request->is_starred){
            MainModel::where('is_starred', true)->update(['is_starred' => false]);
        }

        $rules = [
            'title_health_vitals' => $request->title_health_vitals,
            'description_health_vitals' => $request->description_health_vitals,
            'value_text_health_vitals' => $request->value_text_health_vitals,
            'consume_health_vitals' => $request->health_vitals ? 0 : 'unlimited',
            'health_vitals' => $request->health_vitals ?? 'unlimited',
            'title_free_video_consults' => $request->title_free_video_consults,
            'description_free_video_consults' => $request->description_free_video_consults,
            'value_text_free_video_consults' => $request->value_text_free_video_consults,
            'consume_free_video_consults' => $request->free_video_consults ? 0 : 'unlimited',
            'free_video_consults' => $request->free_video_consults ?? 'unlimited',
            'title_scan_limit' => $request->title_scan_limit,
            'description_scan_limit' => $request->description_scan_limit,
            'value_text_scan_limit' => $request->value_text_scan_limit,
            'consume_scan_limit' => $request->scan_limit ? 0 : 'unlimited',
            'scan_limit' => $request->scan_limit ?? 'unlimited',
            'title_detail_history' => $request->title_detail_history,
            'description_detail_history' => $request->description_detail_history,
            'value_text_detail_history' => $request->value_text_detail_history,
            'consume_detail_history' => $request->detail_history ? 0 : 'unlimited',
            'detail_history' => $request->detail_history ?? 'unlimited',
            'title_sehat_score' => $request->title_sehat_score,
            'description_sehat_score' => $request->description_sehat_score,
            'value_text_sehat_score' => $request->value_text_sehat_score,
            'consume_sehat_score' => $request->sehat_score ? 0 : 'unlimited',
            'sehat_score' => $request->sehat_score ?? 'unlimited',
            'title_online_prescription' => $request->title_online_prescription,
            'description_online_prescription' => $request->description_online_prescription,
            'value_text_online_prescription' => $request->value_text_online_prescription,
            'consume_online_prescription' => $request->online_prescription ? 0 : 'unlimited',
            'online_prescription' => $request->online_prescription ?? 'unlimited',
            'title_dedicated_customer_support' => $request->title_dedicated_customer_support,
            'description_dedicated_customer_support' => $request->description_dedicated_customer_support,
            'value_text_dedicated_customer_support' => $request->value_text_dedicated_customer_support,
            'consume_dedicated_customer_support' => $request->dedicated_customer_support ? 0 : 'unlimited',
            'dedicated_customer_support' => $request->dedicated_customer_support ?? 'unlimited',
        ];

        $data = [
            'code' => $request->code,
            'no_of_consultation' => $request->no_of_consultation,
            'no_of_sehat_scan' => $request->no_of_sehat_scan,
            'expire_date' => $request->expire_date,
            'status' => $request->status ,
        ];

        if ($id) {
            if (MainModel::find($id)->update($data)) {
                Helper::toast('success', $this->module_name . ' Updated.');
            }
        } else {
            if (MainModel::create($data)) {
                Helper::toast('success', $this->module_name . ' Created.');
            }
        }
        return redirect()->route($this->folder_name . '-view');
    }

    public function toggleStatus(Request $request)
    {
        $result = MainModel::find($request->id);
        $result->status = $request->val;
        $result->save();
    }

    public function add(Request $request){
        if (Gate::denies('promo-code-management-add')) {
            abort(403);
        }
        if($request->isMethod('post')){
            return $this->form($request);
        }else{
            $data['page_header'] = "Add ".$this->module_name;
            $data['result'] = null;
            $data['include_status_radio'] = 1;
            $data['user']=User::where('role_id',2)->get();

            $file = "admin.".$this->folder_name.".form";
            return view('general_crud.general_view', $data);
        }
    }

    public function edit(Request $request, $id)
    {
        if (Gate::denies('promo-code-management-update')) {
            abort(403);
        }
        $id = decrypt($id);
        if ($request->isMethod('post')) {
            return $this->form($request, $id);
        } else {
            $row = MainModel::find($id);
            if($row){
                $rules = json_decode($row->rules);
                if($rules){
                    $row->title_health_vitals = $rules->title_health_vitals;
                    $row->description_health_vitals = $rules->description_health_vitals;
                    $row->value_text_health_vitals = $rules->value_text_health_vitals;
                    $row->health_vitals = $rules->health_vitals;
                    $row->title_free_video_consults = $rules->title_free_video_consults;
                    $row->description_free_video_consults = $rules->description_free_video_consults;
                    $row->value_text_free_video_consults = $rules->value_text_free_video_consults;
                    $row->free_video_consults = $rules->free_video_consults;
                    $row->title_scan_limit = $rules->title_scan_limit;
                    $row->description_scan_limit = $rules->description_scan_limit;
                    $row->value_text_scan_limit = $rules->value_text_scan_limit;
                    $row->scan_limit = $rules->scan_limit;
                    $row->title_detail_history = $rules->title_detail_history;
                    $row->description_detail_history = $rules->description_detail_history;
                    $row->value_text_detail_history = $rules->value_text_detail_history;
                    $row->detail_history = $rules->detail_history;
                    $row->title_sehat_score = $rules->title_sehat_score;
                    $row->description_sehat_score = $rules->description_sehat_score;
                    $row->value_text_sehat_score = $rules->value_text_sehat_score;
                    $row->sehat_score = $rules->sehat_score;
                    $row->title_online_prescription = $rules->title_online_prescription;
                    $row->description_online_prescription = $rules->description_online_prescription;
                    $row->value_text_online_prescription = $rules->value_text_online_prescription;
                    $row->online_prescription = $rules->online_prescription;
                    $row->title_dedicated_customer_support = $rules->title_dedicated_customer_support;
                    $row->description_dedicated_customer_support = $rules->description_dedicated_customer_support;
                    $row->value_text_dedicated_customer_support = $rules->value_text_dedicated_customer_support;
                    $row->dedicated_customer_support = $rules->dedicated_customer_support;
                }
            }
            $data['page_header'] = "Edit " . $this->module_name;
            $data['result'] = $row;
            $data['include_status_radio'] = 1;
            $data['include_is_mobile_show_radio'] = 1;
            $input_elements = $this->input_elements;
            return view('general_crud.general_view', $data)->with(compact('input_elements'));
        }
    }
}
