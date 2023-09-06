<?php

namespace App\Http\Controllers\Admin;

use App\Http\Common\Helper;
use App\Http\Controllers\Controller;
use App\Models\Subscription as MainModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SubscriptionManagementController extends Controller
{
    public $folder_name = 'subscription'; // For view routes and file calling and saving
    public $module_name = 'Subscription'; // For toast And page header
    public $input_elements;

    public function input_elements_creator()
    {
        $input_elements = array();
        if(Gate::check('subscription-add-subscription-name'))
        {
            $input_element = array_push($input_elements,[
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Name",
                "name" => "name",
                "placeholder" => "Enter Package Name",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);

        }
        if(Gate::check('subscription-add-subscription-total-days')){
            $input_element = array_push($input_elements, [
                "element_type" => "input",
                "input_type" => "number",
                "label" => "Total Days",
                "name" => "duration",
                "placeholder" => "Enter Total Duration in Days",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }
        if(Gate::check('subscription-add-subscription-total-days-yearly')){
            $input_element = array_push($input_elements,[
                "element_type" => "input",
                "input_type" => "number",
                "label" => "Total Days Yearly",
                "name" => "duration_yearly",
                "placeholder" => "Enter Total Duration in Days Yearly",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }
        if(Gate::check('subscription-add-subscription-total-duration-yearly')){
            $input_element = array_push($input_elements,[
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Total Duration Yearly",
                "name" => "duration_text_yearly",
                "placeholder" => "Enter Total Duration in Words Yearly",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }
        if(Gate::check('subscription-add-subscription-total-duration-')){
            $input_element = array_push($input_elements,[
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Total Duration",
                "name" => "duration_text",
                "placeholder" => "Enter Total Duration in Words",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }
        if(Gate::check('subscription-add-subscription-color-code')){
            $input_element = array_push($input_elements,[
                "label" => "Color Code",
                "element_type" => "dropdown",
                "name" => "color_code",
                "options" => [
                    [
                        "type" => "#72d54a",
                        "name" => "Green",
                    ],
                    [
                        "type" => "#28bcc1",
                        "name" => "Sky Blue",
                    ],
                    [
                        "type" => "#f5d730",
                        "name" => "Yellow",
                    ],
                    [
                        "type" => "#ef6286",
                        "name" => "Pink",
                    ],
                    [
                        "type" => "#bef5f1",
                        "name" => "Light Blue",
                    ],
                    [
                        "type" => "#e9eaef",
                        "name" => "Bright Gray",
                    ],
                ],
                "value_element" => "type",
                "select_element" => "color_code",
                "label_element" => "name",
                "additional_ids" => ["id_1", "id_2"],
                "additional_classes" => ["class_1", "class_2"],
                "html_params" => ["required" => "required"],
            ]);
        }
        if(Gate::check('subscription-add-subscription-price')){
            $input_element = array_push($input_elements,[
                "element_type" => "input",
                "input_type" => "number",
                "label" => "Price",
                "name" => "price",
                "placeholder" => "Enter Original Price",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }
        if(Gate::check('subscription-add-subscription-price-yearly')){
            $input_element = array_push($input_elements, [
                "element_type" => "input",
                "input_type" => "number",
                "label" => "Price Yearly",
                "name" => "price_yearly",
                "placeholder" => "Enter Original Price (Yearly)",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }
        if(Gate::check('subscription-add-subscription-discounted-price')){
            $input_element = array_push($input_elements,[
                "element_type" => "input",
                "input_type" => "number",
                "label" => "Discounted Price",
                "name" => "discounted_price",
                "placeholder" => "Enter Discounted Price",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => [],
            ]);
        }
        if(Gate::check('subscription-add-subscription-yearly-discounted-price')){
            $input_element = array_push($input_elements,[
                "element_type" => "input",
                "input_type" => "number",
                "label" => "Yearly Discounted Price",
                "name" => "discounted_price_yearly",
                "placeholder" => "Enter Discounted Price (Yearly)",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => [],
            ]);
        }
        if(Gate::check('subscription-add-subscription-description')){
            $input_element = array_push($input_elements,[
                "label" => "Description",
                "element_type" => "textarea",
                "name" => "description",
                "editor" => 1,
                "rows" => 10,
                "placeholder" => "Please Enter Description",
                "additional_ids" => ["id_1", "id_2"],
                "additional_classes" => ["class_1", "class_2"],
                "html_params" => ["required" => "required"],
            ]);
        }
        if(Gate::check('subscription-add-subscription-addon-heading')){
            $input_element = array_push($input_elements,[
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Addon Heading",
                "name" => "addon_heading",
                "placeholder" => "Enter Addon Heading",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }

        if(Gate::check('subscription-add-subscription-addon-text')){
            $input_element = array_push($input_elements,[
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Addon Text (Use Comma Seperate Values)",
                "name" => "addon_text",
                "placeholder" => "Please USE Comma Seperate Values ie. Premade templates,Core integrations",
                "additional_ids" => ["id_1", "id_2"],
                "additional_classes" => ["class_1", "class_2"],
                "html_params" => ["required" => "required"],
            ]);
        }

            if(Gate::check('subscription-add-subscription-make-it-favourite-package')){
            $input_element = array_push($input_elements,[
                "label" => "Make it Favourite Package",
                "element_type" => "radio",
                "name" => "is_starred",
                "html_params" => ["required" => "required"],
                "buttons" => [
                    [
                        "value_element" => "text",
                        "label" => "Favourite",
                        "value" => "1",
                        "checked_on_null" => 1,
                        "additional_ids" => ["id_1", "id_2"],
                        "additional_classes" => ["class_1", "class_2"],
                    ],
                    [
                        "value_element" => "text",
                        "label" => "Not Favourite",
                        "value" => "0",
                        "checked_on_null" => 0,
                        "additional_ids" => ["id_1", "id_2"],
                        "additional_classes" => ["class_1", "class_2"],
                    ],
                ],
            ]);
        }

        if(Gate::check('subscription-add-subscription-health-vitals-title')){
            $input_element = array_push($input_elements,[
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Health Vitals Title",
                "name" => "title_health_vitals",
                "placeholder" => "Will be display on subscription page.",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }
        if(Gate::check('subscription-add-subscription-health-vitals-description')){
            $input_element = array_push($input_elements,[
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Health Vitals Description",
                "name" => "description_health_vitals",
                "placeholder" => "Will be display on subscription page.",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => [],
            ]);
        }
        if(Gate::check('subscription-add-subscription-health-vitals-value-text')){
            $input_element = array_push($input_elements,[
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Health Vitals value text",
                "name" => "value_text_health_vitals",
                "placeholder" => "Will be display on subscription page.",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }
        if(Gate::check('subscription-add-subscription-health-vitals-leave-blank-for-unlimited')){
            $input_element = array_push($input_elements,[
                "element_type" => "input",
                "input_type" => "number",
                "label" => "Health Vitals (Leave Blank for unlimited)",
                "name" => "health_vitals",
                "placeholder" => "Enter Health Vitals (Leave Blank for unlimited)",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => [],
            ]);
        }

        if(Gate::check('subscription-add-subscription-free-video-consults-title')){
            $input_element = array_push($input_elements,[
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Free Video Consults Title",
                "name" => "title_free_video_consults",
                "placeholder" => "Will be display on subscription page.",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }
        if(Gate::check('subscription-add-subscription-free-video-consults-description')){
            $input_element = array_push($input_elements,[
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Free Video Consults Description",
                "name" => "description_free_video_consults",
                "placeholder" => "Will be display on subscription page.",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => [],
            ]);
        }
        if(Gate::check('subscription-add-subscription-free-video-consults-value-text')){
            $input_element = array_push($input_elements,[
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Free Video Consults value text",
                "name" => "value_text_free_video_consults",
                "placeholder" => "Will be display on subscription page.",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }

        if(Gate::check('subscription-add-subscription-free-video-consults-Leave-Blank-for-unlimited')){
            $input_element = array_push($input_elements,[
                "element_type" => "input",
                "input_type" => "number",
                "label" => "Free Video Consults (Leave Blank for unlimited)",
                "name" => "free_video_consults",
                "placeholder" => "Free Video Consults (Leave Blank for unlimited)",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => [],
            ]);
        }
        if(Gate::check('subscription-add-subscription-scan-limit-title')){
            $input_element = array_push($input_elements,[
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Scan Limit Title",
                "name" => "title_scan_limit",
                "placeholder" => "Will be display on subscription page.",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }
        if(Gate::check('subscription-add-subscription-scan-limit-Description')){
            $input_element = array_push($input_elements,[
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Scan Limit Description",
                "name" => "description_scan_limit",
                "placeholder" => "Will be display on subscription page.",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => [],
            ]);
        }
        if(Gate::check('subscription-add-subscription-scan-limit-value-text')){
            $input_element = array_push($input_elements,[
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Scan Limit value text",
                "name" => "value_text_scan_limit",
                "placeholder" => "Will be display on subscription page.",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }
        if(Gate::check('subscription-add-subscription-scan-limit-leave-blank-for-unlimited')){
            $input_element = array_push($input_elements,[
                "element_type" => "input",
                "input_type" => "number",
                "label" => "Scan Limit (Leave Blank for unlimited)",
                "name" => "scan_limit",
                "placeholder" => "Scan Limit (Leave Blank for unlimited)",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => [],
            ]);
        }
        if(Gate::check('subscription-add-subscription-history-title')){
            $input_element = array_push($input_elements, [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "History Title",
                "name" => "title_detail_history",
                "placeholder" => "Will be display on subscription page.",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }
        if(Gate::check('subscription-add-subscription-history-description')){
            $input_element = array_push($input_elements,[
                "element_type" => "input",
                "input_type" => "text",
                "label" => "History Description",
                "name" => "description_detail_history",
                "placeholder" => "Will be display on subscription page.",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => [],
            ]);
        }
        if(Gate::check('subscription-add-subscription-history-value-text')){
            $input_element = array_push($input_elements,
            [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "History value text",
                "name" => "value_text_detail_history",
                "placeholder" => "Will be display on subscription page.",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }
        if(Gate::check('subscription-add-subscription-history-leave-blank-for-unlimited')){
            $input_element = array_push($input_elements,[
                "element_type" => "input",
                "input_type" => "number",
                "label" => "History (Leave Blank for unlimited)",
                "name" => "detail_history",
                "placeholder" => "History (Leave Blank for unlimited)",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => [],
            ]);
        }
        if(Gate::check('subscription-add-subscription-sehat-score-title')){
            $input_element = array_push($input_elements,
            [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Sehat Score Title",
                "name" => "title_sehat_score",
                "placeholder" => "Will be display on subscription page.",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }
        if(Gate::check('subscription-add-subscription-sehat-score-description')){
            $input_element = array_push($input_elements,
            [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Sehat Score Description",
                "name" => "description_sehat_score",
                "placeholder" => "Will be display on subscription page.",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => [],
            ]);
        }
        if(Gate::check('subscription-add-subscription-sehat-score-value-text')){
            $input_element = array_push($input_elements,
            [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Sehat Score value text",
                "name" => "value_text_sehat_score",
                "placeholder" => "Will be display on subscription page.",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }
        if(Gate::check('subscription-add-subscription-sehat-score-leave-blank-for-unlimited')){
            $input_element = array_push($input_elements,[
                "element_type" => "input",
                "input_type" => "number",
                "label" => "Sehat Score (Leave Blank for unlimited)",
                "name" => "sehat_score",
                "placeholder" => "History (Leave Blank for unlimited)",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => [],
            ]);
        }

        if(Gate::check('subscription-add-subscription-online-prescription-title')){
            $input_element = array_push($input_elements,[
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Online Prescription Title",
                "name" => "title_online_prescription",
                "placeholder" => "Will be display on subscription page.",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }
        if(Gate::check('subscription-add-subscription-online-prescription-description')){
            $input_element = array_push($input_elements,
            [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Online Prescription Description",
                "name" => "description_online_prescription",
                "placeholder" => "Will be display on subscription page.",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => [],
            ]);
        }
        if(Gate::check('subscription-add-subscription-online-prescription-value-text')){
            $input_element = array_push($input_elements,[
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Online Prescription value text",
                "name" => "value_text_online_prescription",
                "placeholder" => "Will be display on subscription page.",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }
        if(Gate::check('subscription-add-subscription-online-prescription-leave-blank-for-unlimited')){
            $input_element = array_push($input_elements,
            [
                "element_type" => "input",
                "input_type" => "number",
                "label" => "Online Prescription (Leave Blank for unlimited)",
                "name" => "online_prescription",
                "placeholder" => "History (Leave Blank for unlimited)",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => [],
            ]);
        }
        if(Gate::check('subscription-add-subscription-dedicated-customer-support-title')){
            $input_element = array_push($input_elements,[
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Dedicated Customer Support Title",
                "name" => "title_dedicated_customer_support",
                "placeholder" => "Will be display on subscription page.",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }
        if(Gate::check('subscription-add-subscription-dedicated-customer-support-description')){
            $input_element = array_push($input_elements,
            [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Dedicated Customer Support Description",
                "name" => "description_dedicated_customer_support",
                "placeholder" => "Will be display on subscription page.",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => [],
            ]);
        }
        if(Gate::check('subscription-add-subscription-dedicated-customer-support-value-text')){
            $input_element = array_push($input_elements,
            [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Dedicated Customer Support value text",
                "name" => "value_text_dedicated_customer_support",
                "placeholder" => "Will be display on subscription page.",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        }
        if(Gate::check('subscription-add-subscription-dedicated-customer-support-leave-blank-for-unlimited')){
            $input_element = array_push($input_elements,
            [
                "element_type" => "input",
                "input_type" => "number",
                "label" => "Dedicated Customer Support (Leave Blank for unlimited)",
                "name" => "dedicated_customer_support",
                "placeholder" => "History (Leave Blank for unlimited)",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => [],
            ]);
        }

        $this->input_elements = $input_elements;
    }

    public function view(Request $request)
    {
        // $this->input_elements = $this->input_elements();
        if (Gate::denies('subscription-view')) {
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
        if ($request->has('title_health_vitals')) {
            $rules['title_health_vitals']=$request->title_health_vitals;
        }if ($request->has('description_health_vitals')) {
            $rules['description_health_vitals']=$request->description_health_vitals;
        }if ($request->has('value_text_health_vitals')) {
            $rules['value_text_health_vitals']=$request->value_text_health_vitals;
        }if ($request->has('consume_health_vitals')) {
            $rules['consume_health_vitals']=$request->consume_health_vitals ? 0 :'unlimited';
        }if ($request->has('health_vitals')) {
            $rules['health_vitals']=$request->health_vitals ?? 'unlimited';
        }if ($request->has('title_free_video_consults')) {
            $rules['title_free_video_consults']=$request->title_free_video_consults;
        }if ($request->has('description_free_video_consults')) {
            $rules['description_free_video_consults']=$request->description_free_video_consults;
        }if ($request->has('value_text_free_video_consults')) {
            $rules['value_text_free_video_consults']=$request->value_text_free_video_consults;
        }if ($request->has('consume_free_video_consults')) {
            $rules['consume_free_video_consults']=$request->free_video_consults ? 0 : 'unlimited';
        }if ($request->has('free_video_consults')) {
            $rules['free_video_consults']=$request->free_video_consults ?? 'unlimited';
        }if ($request->has('title_scan_limit')) {
            $rules['title_scan_limit']=$request->title_scan_limit;
        }if ($request->has('description_scan_limit')) {
            $rules['description_scan_limit']=$request->description_scan_limit;
        }if ($request->has('value_text_scan_limit')) {
            $rules['value_text_scan_limit']=$request->value_text_scan_limit;
        }if ($request->has('consume_scan_limit')) {
            $rules['consume_scan_limit']=$request->scan_limit ? 0 : 'unlimited';
        }if ($request->has('scan_limit')) {
            $rules['scan_limit']=$request->scan_limit ?? 'unlimited';
        }if ($request->has('title_detail_history')) {
            $rules['title_detail_history']=$request->title_detail_history;
        }if ($request->has('description_detail_history')) {
            $rules['description_detail_history']=$request->description_detail_history;
        }if ($request->has('title_detail_history')) {
            $rules['title_detail_history']=$request->title_detail_history;
        }if ($request->has('description_detail_history')) {
            $rules['description_detail_history']=$request->description_detail_history;
        }if ($request->has('value_text_detail_history')) {
            $rules['value_text_detail_history']=$request->value_text_detail_history;
        }if ($request->has('consume_detail_history')) {
            $rules['consume_detail_history']=$request->detail_history ? 0 : 'unlimited';
        }if ($request->has('detail_history')) {
            $rules['detail_history']=$request->detail_history ?? 'unlimited';
        }if ($request->has('title_sehat_score')) {
            $rules['title_sehat_score']=$request->title_sehat_score;
        }if ($request->has('description_sehat_score')) {
            $rules['description_sehat_score']=$request->description_sehat_score;
        }if ($request->has('value_text_sehat_score')) {
            $rules['value_text_sehat_score']=$request->value_text_sehat_score;
        }if ($request->has('consume_sehat_score')) {
            $rules['consume_sehat_score']=$request->sehat_score ? 0 : 'unlimited';
        }if ($request->has('sehat_score')) {
            $rules['sehat_score']=$request->sehat_score ?? 'unlimited';
        }if ($request->has('title_online_prescription')) {
            $rules['title_online_prescription']=$request->title_online_prescription;
        }if ($request->has('description_online_prescription')) {
            $rules['description_online_prescription']=$request->description_online_prescription;
        }if ($request->has('value_text_online_prescription')) {
            $rules['value_text_online_prescription']=$request->value_text_online_prescription;
        }if ($request->has('consume_online_prescription')) {
            $rules['consume_online_prescription']=$request->online_prescription ? 0 : 'unlimited';
        }if ($request->has('online_prescription')) {
            $rules['online_prescription']=$request->online_prescription ?? 'unlimited';
        }if ($request->has('title_dedicated_customer_support')) {
            $rules['title_dedicated_customer_support']=$request->title_dedicated_customer_support;
        }if ($request->has('description_dedicated_customer_support')) {
            $rules['description_dedicated_customer_support']=$request->description_dedicated_customer_support;
        }if ($request->has('value_text_dedicated_customer_support')) {
            $rules['value_text_dedicated_customer_support']=$request->value_text_dedicated_customer_support;
        }if ($request->has('consume_dedicated_customer_support')) {
            $rules['consume_dedicated_customer_support']=$request->dedicated_customer_support ? 0 : 'unlimited';
        }if ($request->has('dedicated_customer_support')) {
            $rules['dedicated_customer_support']=$request->dedicated_customer_support ?? 'unlimited';
        }

        if ($request->has('name')) {
            $data['name']=$request->name;
        }if ($request->has('duration')) {
            $data['duration']=$request->duration;
        }if ($request->has('duration_text')) {
            $data['duration_text']=$request->duration_text;
        }if ($request->has('duration_yearly')) {
            $data['duration_yearly']=$request->duration_yearly;
        }if ($request->has('duration_text_yearly')) {
            $data['duration_text_yearly']=$request->duration_text_yearly;
        }if ($request->has('color_code')) {
            $data['color_code']=$request->color_code;
        }if ($request->has('price')) {
            $data['price']=$request->price;
        }if ($request->has('discounted_price')) {
            $data['discounted_price']=$request->discounted_price;
        }if ($request->has('price_yearly')) {
            $data['price_yearly']=$request->price_yearly;
        }if ($request->has('discounted_price_yearly')) {
            $data['discounted_price_yearly']=$request->discounted_price_yearly;
        }if ($request->has('description')) {
            $data['description']=$request->description;
        }if ($request->has('addon_heading')) {
            $data['addon_heading']=$request->addon_heading;
        }if ($request->has('addon_text')) {
            $data['addon_text']=$request->addon_text;
        }if ($request->has('is_starred')) {
            $data['is_starred']=$request->status == false ? false : $request->is_starred;
        }if ($request->has('rules')) {
            $data['rules']=json_encode($rules);
        }if ($request->has('status')) {
            $data['status']=$request->status ?? 1;
        }

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

    public function add(Request $request)
    {
        $this->input_elements_creator();
        if (Gate::denies('subscription-add')) {
            abort(403);
        }
        if ($request->isMethod('post')) {
            return $this->form($request);
        } else {
            $data['page_header'] = "Add " . $this->module_name;
            $data['result'] = null;
            $input_elements = $this->input_elements;
            $data['include_status_radio'] = 1;
            $data['include_is_mobile_show_radio'] = 1;
            return view('general_crud.general_view', $data)->with(compact('input_elements'));
        }
    }

    public function edit(Request $request, $id)
    {
        $this->input_elements_creator();
        $id = decrypt($id);
        if (Gate::denies('subscription-update')) {
            abort(403);
        }
        if ($request->isMethod('post')) {
            return $this->form($request, $id);
        } else {
            $row = MainModel::find($id);
            if($row){
                $rules = json_decode($row->rules);
                if($rules){
                    $row->title_health_vitals = $rules->title_health_vitals ?? null;
                    $row->description_health_vitals = $rules->description_health_vitals  ?? null;
                    $row->value_text_health_vitals = $rules->value_text_health_vitals  ?? null;
                    $row->health_vitals = $rules->health_vitals ?? null;
                    $row->title_free_video_consults = $rules->title_free_video_consults ?? null;
                    $row->description_free_video_consults = $rules->description_free_video_consults ?? null;
                    $row->value_text_free_video_consults = $rules->value_text_free_video_consults ?? null;
                    $row->free_video_consults = $rules->free_video_consults ?? null;
                    $row->title_scan_limit = $rules->title_scan_limit ?? null;
                    $row->description_scan_limit = $rules->description_scan_limit ?? null;
                    $row->value_text_scan_limit = $rules->value_text_scan_limit ?? null;
                    $row->scan_limit = $rules->scan_limit ?? null;
                    $row->title_detail_history = $rules->title_detail_history ?? null;
                    $row->description_detail_history = $rules->description_detail_history ?? null;
                    $row->value_text_detail_history = $rules->value_text_detail_history ?? null;
                    $row->detail_history = $rules->detail_history ?? null;
                    $row->title_sehat_score = $rules->title_sehat_score ?? null;
                    $row->description_sehat_score = $rules->description_sehat_score ?? null;
                    $row->value_text_sehat_score = $rules->value_text_sehat_score ?? null;
                    $row->sehat_score = $rules->sehat_score ?? null;
                    $row->title_online_prescription = $rules->title_online_prescription ?? null;
                    $row->description_online_prescription = $rules->description_online_prescription ?? null;
                    $row->value_text_online_prescription = $rules->value_text_online_prescription ?? null;
                    $row->online_prescription = $rules->online_prescription ?? null;
                    $row->title_dedicated_customer_support = $rules->title_dedicated_customer_support ?? null;
                    $row->description_dedicated_customer_support = $rules->description_dedicated_customer_support ?? null;
                    $row->value_text_dedicated_customer_support = $rules->value_text_dedicated_customer_support ?? null;
                    $row->dedicated_customer_support = $rules->dedicated_customer_support ?? null;
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
