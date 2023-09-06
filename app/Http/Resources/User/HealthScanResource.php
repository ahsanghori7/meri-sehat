<?php

namespace App\Http\Resources\User;

use App\Http\Common\Constant;
use App\Http\Common\Helper;
use App\Http\Resources\Article\ArticleSummeryResource;
use App\Models\{Article, HealthScan, HealthScanSuggestion, Language, Settings, User};
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Jenssegers\Agent\Agent;

class HealthScanResource extends JsonResource
{
    private $blood_pressure_value, $stress_level_value, $spo2_value, $respiratory_rate_value, $heart_rate_value, $sdnn_value;
    private $blood_pressure_description, $stress_level_description, $spo2_description, $respiratory_rate_description, $heart_rate_description, $sdnn_description;
    private $blood_pressure_percentage, $stress_level_percentage, $spo2_percentage, $respiratory_rate_percentage, $heart_rate_percentage, $sdnn_percentage;
    public   $high_diagnosis_text, $medium_diagnosis_text, $low_diagnosis_text, $diagnosis_text;
    private $diagnosis_color, $general_recommendation;
    private $sehat_score ,$value;
    private $preDignosisText;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function __construct($resource, $value = false) {
        // Ensure we call the parent constructor
        parent::__construct($resource);
        $this->resource = $resource;
        $this->value = $value; // $apple param passed
    }

    public function toArray($request)
    {
        $viewData = [];
        $createdDate = date('F j, Y', strtotime($this->created_at));
        $sugestedArticleAndDoctors = $this->convertParameters($request);
        $this->saveDiagnosis($this->diagnosis_text);
        $data = [
            'date' => $createdDate,
            'id' => $this->id,
            'user_id' => $this->user_id,
            'family_member_id' => $this->family_member_id,
            'share_url' => '/vitals/' . base_convert($this->id, 10, 36),
            'sehat_score' => (int) $this->sehat_score,

            // 'blood_pressure' => (string) $this->blood_pressure,
            // 'blood_pressure_value' => $this->blood_pressure_value,
            // 'blood_pressure_percentage' => (string) $this->blood_pressure_percentage,
            // 'blood_pressure_description' => $this->blood_pressure_description,
            // 'blood_pressure_color' => $this->blood_pressure_color,

            'stress_level' => (string) $this->stress_level,
            'stress_level_value' => $this->stress_level_value,
            'stress_level_percentage' => (string) $this->stress_level_percentage,
            'stress_level_description' => $this->stress_level_description,
            'stress_level_color' => $this->stress_level_color,

            'respiratory_rate' => (string) $this->respiratory_rate,
            'respiratory_rate_value' => $this->respiratory_rate_value,
            'respiratory_rate_percentage' => (string) $this->respiratory_rate_percentage,
            'respiratory_rate_description' => $this->respiratory_rate_description,
            'respiratory_rate_color' => $this->respiratory_rate_color,

            'sdnn' => (string) $this->sdnn,
            'sdnn_value' => $this->sdnn_value,
            'sdnn_percentage' => (string) $this->sdnn_percentage,
            'sdnn_description' => $this->sdnn_description,
            'sdnn_color' => $this->sdnn_color,

            'heart_rate' => (string) $this->heart_rate,
            'heart_rate_value' => $this->heart_rate_value,
            'heart_rate_percentage' => (string) $this->heart_rate_percentage,
            'heart_rate_description' => $this->heart_rate_description,
            'heart_rate_color' => $this->heart_rate_color,

            'dateTime' => Carbon::parse($this->created_at)->isoFormat('h:mm A'),
            'diagnosis' => [
                'bg_color' => $this->diagnosis_color,
                'text' => Helper::sanitizeData($this->diagnosis_text),
            ],
            'recommendation' => $this->general_recommendation ?? null,
            'ip_address' => $this->ip_address,
            'score' => $this->score,

            'user' => new UserSummeryResource($this->user),
            'family_member' => $this->familyMember ? [
                'id' => $this->familyMember ? $this->familyMember->id : null,
                'name' => $this->familyMember ? $this->familyMember->name : null,
                'relationship' => $this->familyMember ? $this->familyMember->relationship : null,
            ] : null,
        ];

        // $agent = new Agent();
        // if($agent->isAndroidOS()){
            $viewData = [
                'blood_pressure' => (string) $this->blood_pressure,
                'blood_pressure_value' => $this->blood_pressure_value,
                'blood_pressure_percentage' => (string) $this->blood_pressure_percentage,
                'blood_pressure_description' => $this->blood_pressure_description,
                'blood_pressure_color' => $this->blood_pressure_color,
                
                'spo2' => (string) $this->spo2,
                'spo2_value' => $this->spo2_value,
                'spo2_percentage' => (string) $this->spo2_percentage,
                'spo2_description' => $this->spo2_description,
                'spo2_color' => $this->spo2_color,
            ];
        // }

        $data = array_merge($data, $viewData);
        if(!$this->value){
            if(count($sugestedArticleAndDoctors['article_id']) && $sugestedArticleAndDoctors['doctor_id']){
                $data['suggested_doctors'] = UserSummeryResource::collection(User::whereIn('id', $sugestedArticleAndDoctors['doctor_id'])->get());
                $data['suggested_articles'] = ArticleSummeryResource::collection(Article::whereIn('id', $sugestedArticleAndDoctors['article_id'])->where('lang_id', $request->header('locale'))->limit(5)->get());
                $data['suggestions'] = ['suggested_doctors', 'suggested_articles'];
                return $data;
            }
            $data['suggested_articles'] = ArticleSummeryResource::collection(Article::where('status', true)->where('lang_id', $request->header('locale'))->limit(5)->get());
            $data['suggested_doctors'] = UserSummeryResource::collection(User::whereHas('doctorSpecialities', function($query){
                $query->where('speciality_id', Constant::GENERAL_PHYSICIAN_SPECIALITY_ID);
            })->take(5)->get());
            $data['suggestion'] = ['suggested_articles', 'suggested_doctors'];
        }
        $title = Settings::where('key','health_scan_report_title')->exists() ? Settings::getValue('health_scan_report_title') : 'Health Scan Check';
        $chooserTitle = Settings::where('key','health_scan_report_chooserTitle')->exists() ? Settings::getValue('health_scan_report_chooserTitle') : "";
        $linkUrl = Settings::where('key','health_scan_repport_linkUrl')->exists() ? Settings::getValue('health_scan_repport_linkUrl') : 'www.merisehat.pk/app';
        
        $data['share_message'] = [
            'title' =>  $title,
            'chooserTitle' => $chooserTitle,
            'linkUrl' => $linkUrl,
            'text' =>  'MERI SEHAT measured my vitals:\nBlood Pressure: '.$this->blood_pressure.'\nRespiration Rate: '.$this->respiratory_rate.'\nHeart Rate: '.$this->heart_rate.'\nSdnn: '.$this->sdnn.'\nStress Level: '.$this->stress_level.'\nOxygen Saturation: '.$this->spo2.'\nSehat Score: '.$this->sehat_score.'/10\n\nTaken on ('.$createdDate.' Download today for a free scan:)'
        ];
        return $data;
    }
    /**
     * This method is used to convert the parameters into a string of high, medium and low values
     */
    public function convertParameters($request)
    {
        $doctorIds = [];
        $articleIds = [];
        $diagnosisColor = [];
        $getSettings = Settings::getValues(['health_scan_high', 'health_scan_medium', 'health_scan_low']);
        $color = [
            "high" => $getSettings['health_scan_high'],
            "medium" => $getSettings['health_scan_medium'],
            "low" => $getSettings['health_scan_low']
        ];

        $this->sehat_score = 0;

        $total_vitals = 0;
        // For Blood Pressure
        if(isset($this->blood_pressure ) && !empty($this->blood_pressure) && $this->blood_pressure){
           $total_vitals = $total_vitals + 1;
           $bloodPressure = explode('/', $this->blood_pressure)[0];
           if($bloodPressure >= 140){
               $suggestion = HealthScanSuggestion::where(['name' => 'blood_pressure', 'type' => 'high'])->first();
               array_push($diagnosisColor, 3);
               $this->blood_pressure_value = "High";
               $this->blood_pressure_color = $color['high'];
               ($request->header('locale') == Language::ENGLISH)
                   ?   $this->high_diagnosis_text = isset($suggestion->diagnosis) && $suggestion->diagnosis ? $this->high_diagnosis_text . "," . $suggestion->diagnosis : ""
                   :   $this->high_diagnosis_text = $suggestion->urdu_diagnosis ? $this->high_diagnosis_text . "," . $suggestion->urdu_diagnosis : "";
               $this->sehat_score = $this->sehat_score + 1.5;
           }else if($bloodPressure <= 139 && $bloodPressure >= 110){
               $suggestion = HealthScanSuggestion::where(['name' => 'blood_pressure', 'type' => 'medium'])->first();
               array_push($diagnosisColor, 2);
               $this->blood_pressure_value = "Normal";
               $this->blood_pressure_color = $color['medium'];
               ($request->header('locale') == Language::ENGLISH)
                   ?   $this->medium_diagnosis_text = isset($suggestion->diagnosis) && $suggestion->diagnosis ? $this->medium_diagnosis_text . "," . $suggestion->diagnosis : ""
                   :   $this->medium_diagnosis_text = $suggestion->urdu_diagnosis ? $this->medium_diagnosis_text . "," . $suggestion->urdu_diagnosis : "";
               $this->sehat_score = $this->sehat_score +  2;
           }else{
               $suggestion = HealthScanSuggestion::where(['name' => 'blood_pressure', 'type' => 'low'])->first();
               array_push($diagnosisColor, 1);
               $this->blood_pressure_value = "Low";
               $this->blood_pressure_color = $color['low'];
               ($request->header('locale') == Language::ENGLISH)
                   ?   $this->low_diagnosis_text = isset($suggestion->diagnosis) && $suggestion->diagnosis ? $this->low_diagnosis_text . "," . $suggestion->diagnosis : ""
                   :   $this->low_diagnosis_text = $suggestion->urdu_diagnosis ? $this->low_diagnosis_text . "," . $suggestion->urdu_diagnosis : "";
               $this->sehat_score = $this->sehat_score +  0.5;
           }
           if($suggestion){
            array_push($doctorIds, $suggestion->doctor_id);
            array_push($articleIds, $suggestion->article_id);
            $this->blood_pressure_percentage = $bloodPressure >= 170 ? 100 : ($bloodPressure <= 110 ? 1 : round(($bloodPressure - 110) * 1.67, 2));
            ($request->header('locale') == Language::ENGLISH)
                ?   $this->blood_pressure_description = $suggestion->description
                :   $this->blood_pressure_description = $suggestion->urdu_description;
           }
        }
        // // For Stress Level
        if(isset($this->sdnn) && !empty($this->sdnn) && $this->sdnn){
            if($this->sdnn < 50){
                $suggestion = HealthScanSuggestion::where(['name' => 'stress_level', 'type' => 'high'])->first();
                array_push($diagnosisColor, 3);
                $this->stress_level_value = "High";
                $this->stress_level_color = $color['high'];

                ($request->header('locale') == Language::ENGLISH)
                    ?   $this->high_diagnosis_text = isset($suggestion->diagnosis) && $suggestion->diagnosis ? $this->high_diagnosis_text . "," . $suggestion->diagnosis : ""
                    :   $this->high_diagnosis_text = $suggestion->urdu_diagnosis ? $this->high_diagnosis_text . "," . $suggestion->urdu_diagnosis : "";

            }else if($this->sdnn >= 50 && $this->sdnn <= 100){
                $suggestion = HealthScanSuggestion::where(['name' => 'stress_level', 'type' => 'medium'])->first();
                array_push($diagnosisColor, 2);
                $this->stress_level_value = "Normal";
                $this->stress_level_color = $color['medium'];
                ($request->header('locale') == Language::ENGLISH)
                    ?   $this->medium_diagnosis_text = isset($suggestion->diagnosis) && $suggestion->diagnosis ? $this->medium_diagnosis_text . "," . $suggestion->diagnosis : ""
                    :   $this->medium_diagnosis_text = $suggestion->urdu_diagnosis ? $this->medium_diagnosis_text . "," . $suggestion->urdu_diagnosis : "";
            }else{
                $suggestion = HealthScanSuggestion::where(['name' => 'stress_level', 'type' => 'low'])->first();
                array_push($diagnosisColor, 1);
                $this->stress_level_value = "Low";
                $this->stress_level_color = $color['low'];
                ($request->header('locale') == Language::ENGLISH)
                    ?   $this->low_diagnosis_text = isset($suggestion->diagnosis) && $suggestion->diagnosis ? $this->low_diagnosis_text . "," . $suggestion->diagnosis : ""
                    :   $this->low_diagnosis_text = $suggestion->urdu_diagnosis ? $this->low_diagnosis_text . "," . $suggestion->urdu_diagnosis : "";
            }
            if($suggestion){
                array_push($doctorIds, $suggestion->doctor_id);
                array_push($articleIds, $suggestion->article_id);
                $this->stress_level_percentage = $this->stress_level >= 5 ? 100 : round(($this->stress_level / 6 * 100), 2);
                ($request->header('locale') == Language::ENGLISH)
                    ?   $this->stress_level_description = $suggestion->description
                    :   $this->stress_level_description = $suggestion->urdu_description;
            }
        }
        // // For Respiratory Level
        if(isset($this->respiratory_rate) && !empty($this->respiratory_rate) && $this->respiratory_rate){
            $total_vitals = $total_vitals + 1;
            if($this->respiratory_rate >= 22){
                $suggestion = HealthScanSuggestion::where(['name' => 'respiratory_rate', 'type' => 'high'])->first();
                array_push($diagnosisColor, 3);
                $this->respiratory_rate_value = "High";
                $this->respiratory_rate_color = $color['high'];

                ($request->header('locale') == Language::ENGLISH)
                    ?   $this->high_diagnosis_text = isset($suggestion->diagnosis) && $suggestion->diagnosis ? $this->high_diagnosis_text . "," . $suggestion->diagnosis : ""
                    :   $this->high_diagnosis_text = $suggestion->urdu_diagnosis ? $this->high_diagnosis_text . "," . $suggestion->urdu_diagnosis : "";
                $this->sehat_score = $this->sehat_score +  1.5;
            }else if($this->respiratory_rate >= 12 && $this->respiratory_rate <= 21){
                $suggestion = HealthScanSuggestion::where(['name' => 'respiratory_rate', 'type' => 'medium'])->first();
                array_push($diagnosisColor, 2);
                $this->respiratory_rate_value = "Normal";
                $this->respiratory_rate_color = $color['medium'];
                ($request->header('locale') == Language::ENGLISH)
                    ?   $this->medium_diagnosis_text = isset($suggestion->diagnosis) && $suggestion->diagnosis ? $this->medium_diagnosis_text . "," . $suggestion->diagnosis : ""
                    :   $this->medium_diagnosis_text = $suggestion->urdu_diagnosis ? $this->medium_diagnosis_text . "," . $suggestion->urdu_diagnosis : "";
                $this->sehat_score = $this->sehat_score +  2;
            }else{
                $suggestion = HealthScanSuggestion::where(['name' => 'respiratory_rate', 'type' => 'low'])->first();
                array_push($diagnosisColor, 1);
                $this->respiratory_rate_value = "Low";
                $this->respiratory_rate_color = $color['low'];
                ($request->header('locale') == Language::ENGLISH)
                    ?   $this->low_diagnosis_text = isset($suggestion->diagnosis) && $suggestion->diagnosis ? $this->low_diagnosis_text . "," . $suggestion->diagnosis : ""
                    :   $this->low_diagnosis_text = $suggestion->urdu_diagnosis ? $this->low_diagnosis_text . "," . $suggestion->urdu_diagnosis : "";
                $this->sehat_score = $this->sehat_score +  0.5;
            }

            if($suggestion){
                array_push($doctorIds, $suggestion->doctor_id);
                array_push($articleIds, $suggestion->article_id);
                $this->respiratory_rate_percentage = $this->respiratory_rate >= 100 ? 100 : (int) $this->respiratory_rate;
                ($request->header('locale') == Language::ENGLISH)
                    ?   $this->respiratory_rate_description = $suggestion->description
                    :   $this->respiratory_rate_description = $suggestion->urdu_description;
            }
        }
        // // For SPO2
        if(isset($this->spo2) && !empty($this->spo2) && $this->spo2){
           $total_vitals = $total_vitals + 1;
           if($this->spo2 >= 95){
               $suggestion = HealthScanSuggestion::where(['name' => 'spo2', 'type' => 'medium'])->first();
               array_push($diagnosisColor, 2);
               $this->spo2_value = "Normal";
               $this->spo2_color = $color['medium'];

               ($request->header('locale') == Language::ENGLISH)
                   ?   $this->high_diagnosis_text = isset($suggestion->diagnosis) && $suggestion->diagnosis ? $this->high_diagnosis_text . "," . $suggestion->diagnosis : ""
                   :   $this->high_diagnosis_text = $suggestion->urdu_diagnosis ? $this->high_diagnosis_text . "," . $suggestion->urdu_diagnosis : "";
               $this->sehat_score = $this->sehat_score +  2;
           }elseif($this->spo2 >= 90 && $this->spo2 <= 94){
               $suggestion = HealthScanSuggestion::where(['name' => 'spo2', 'type' => 'medium'])->first();
               array_push($diagnosisColor, 2);
               $this->spo2_value = "Medium";
               $this->spo2_color = $color['medium'];
               ($request->header('locale') == Language::ENGLISH)
                   ?   $this->medium_diagnosis_text = isset($suggestion->diagnosis) && $suggestion->diagnosis ? $this->medium_diagnosis_text . "," . $suggestion->diagnosis : ""
                   :   $this->medium_diagnosis_text = $suggestion->urdu_diagnosis ? $this->medium_diagnosis_text . "," . $suggestion->urdu_diagnosis : "";
               $this->sehat_score = $this->sehat_score +  1.5;
           }else{
               $suggestion = HealthScanSuggestion::where(['name' => 'spo2', 'type' => 'low'])->first();
               array_push($diagnosisColor, 1);
               $this->spo2_value = "Low";
               $this->spo2_color = $color['low'];
               ($request->header('locale') == Language::ENGLISH)
                   ?   $this->low_diagnosis_text = isset($suggestion->diagnosis) && $suggestion->diagnosis ? $this->low_diagnosis_text . "," . $suggestion->diagnosis : ""
                   :   $this->low_diagnosis_text = $suggestion->urdu_diagnosis ? $this->low_diagnosis_text . "," . $suggestion->urdu_diagnosis : "";
               $this->sehat_score = $this->sehat_score +  0.5;
           }
           if($suggestion){
                array_push($doctorIds, $suggestion->doctor_id);
                array_push($articleIds, $suggestion->article_id);
                $this->spo2_percentage = ($this->spo2 <= 88 ? 100 : ($this->spo2 > 97 ? 1 : ($this->spo2 == 97 ? 10 : array_keys([97,96,95,94,93,92,91,90,89,88], $this->spo2)[0] * 10)));
                ($request->header('locale') == Language::ENGLISH)
                    ?   $this->spo2_description = $suggestion->description
                    :   $this->spo2_description = $suggestion->urdu_description;
           }
           
        }
        // // For Heart Rate / Pulse Rate
        if(isset($this->heart_rate) && !empty($this->heart_rate) && $this->heart_rate){
            $total_vitals = $total_vitals + 1;
            if($this->heart_rate >= 100){
                $suggestion = HealthScanSuggestion::where(['name' => 'heart_rate', 'type' => 'high'])->first();
                array_push($diagnosisColor, 3);
                $this->heart_rate_value = "High";
                $this->heart_rate_color = $color['high'];

                ($request->header('locale') == Language::ENGLISH)
                    ?   $this->high_diagnosis_text = isset($suggestion->diagnosis) && $suggestion->diagnosis ? $this->high_diagnosis_text . "," . $suggestion->diagnosis : ""
                    :   $this->high_diagnosis_text = $suggestion->urdu_diagnosis ? $this->high_diagnosis_text . "," . $suggestion->urdu_diagnosis : "";
                $this->sehat_score = $this->sehat_score +  1.5;
            }else if($this->heart_rate < 100 && $this->heart_rate >= 60){
                $suggestion = HealthScanSuggestion::where(['name' => 'heart_rate', 'type' => 'medium'])->first();
                array_push($diagnosisColor, 2);
                $this->heart_rate_value = "Normal";
                $this->heart_rate_color = $color['medium'];
                ($request->header('locale') == Language::ENGLISH)
                    ?   $this->medium_diagnosis_text = isset($suggestion->diagnosis) && $suggestion->diagnosis ? $this->medium_diagnosis_text . "," . $suggestion->diagnosis : ""
                    :   $this->medium_diagnosis_text = $suggestion->urdu_diagnosis ? $this->medium_diagnosis_text . "," . $suggestion->urdu_diagnosis : "";
                $this->sehat_score = $this->sehat_score +  2;
            }else{
                $suggestion = HealthScanSuggestion::where(['name' => 'heart_rate', 'type' => 'low'])->first();
                array_push($diagnosisColor, 1);
                $this->heart_rate_value = "Low";
                $this->heart_rate_color = $color['low'];
                ($request->header('locale') == Language::ENGLISH)
                    ?   $this->low_diagnosis_text = isset($suggestion->diagnosis) && $suggestion->diagnosis ? $this->low_diagnosis_text . "," . $suggestion->diagnosis : ""
                    :   $this->low_diagnosis_text = $suggestion->urdu_diagnosis ? $this->low_diagnosis_text . "," . $suggestion->urdu_diagnosis : "";
                $this->sehat_score += 0.5;
            }

            if($suggestion){
                array_push($doctorIds, $suggestion->doctor_id);
                array_push($articleIds, $suggestion->article_id);
                $this->heart_rate_percentage = $this->heart_rate >= 100 ? 100 : ($this->heart_rate <= 50 ? 1 : ($this->heart_rate - 50) * 2);
                ($request->header('locale') == Language::ENGLISH)
                    ?   $this->heart_rate_description = $suggestion->description
                    :   $this->heart_rate_description = $suggestion->urdu_description;
            }
            
        }
        // // For SDNN
        if(isset($this->sdnn) && !empty($this->sdnn) && $this->sdnn){
            $total_vitals = $total_vitals + 1;
            if($this->sdnn > 100){
                $suggestion = HealthScanSuggestion::where(['name' => 'sdnn', 'type' => 'high'])->first();
                array_push($diagnosisColor, 3);
                $this->sdnn_value = "High";
                $this->sdnn_color = $color['high'];

                ($request->header('locale') == Language::ENGLISH)
                    ?   $this->high_diagnosis_text = isset($suggestion->diagnosis) && $suggestion->diagnosis ? $this->high_diagnosis_text . "," . $suggestion->diagnosis : ""
                    :   $this->high_diagnosis_text = $suggestion->urdu_diagnosis ? $this->high_diagnosis_text . "," . $suggestion->urdu_diagnosis : "";
                $this->sehat_score = $this->sehat_score +  2;
            }elseif($this->sdnn <= 100 && $this->heart_rate >= 50){
                $suggestion = HealthScanSuggestion::where(['name' => 'sdnn', 'type' => 'medium'])->first();
                array_push($diagnosisColor, 2);
                $this->sdnn_value = "Medium";
                $this->sdnn_color = $color['medium'];
                ($request->header('locale') == Language::ENGLISH)
                    ?   $this->medium_diagnosis_text = isset($suggestion->diagnosis) && $suggestion->diagnosis ? $this->medium_diagnosis_text . "," . $suggestion->diagnosis : ""
                    :   $this->medium_diagnosis_text = $suggestion->urdu_diagnosis ? $this->medium_diagnosis_text . "," . $suggestion->urdu_diagnosis : "";
                $this->sehat_score = $this->sehat_score +  1.5;
            }else{
                $suggestion = HealthScanSuggestion::where(['name' => 'sdnn', 'type' => 'low'])->first();
                array_push($diagnosisColor, 1);
                $this->sdnn_value = "Low";
                $this->sdnn_color = $color['low'];
                ($request->header('locale') == Language::ENGLISH)
                    ?   $this->low_diagnosis_text = isset($suggestion->diagnosis) && $suggestion->diagnosis ? $this->low_diagnosis_text . "," . $suggestion->diagnosis : ""
                    :   $this->low_diagnosis_text = $suggestion->urdu_diagnosis ? $this->low_diagnosis_text . "," . $suggestion->urdu_diagnosis : "";
                $this->sehat_score = $this->sehat_score +  0.5;
            }

            if($suggestion){
                array_push($doctorIds, $suggestion->doctor_id);
                array_push($articleIds, $suggestion->article_id);
                $this->sdnn_percentage = $this->sdnn >= 100 ? 1 : ($this->sdnn <= 1 ? 100 : round((120 - $this->sdnn) / 1.2, 2));
                ($request->header('locale') == Language::ENGLISH)
                    ?   $this->sdnn_description = $suggestion->description
                    :   $this->sdnn_description = $suggestion->urdu_description;    
            }
        }
        // For getting the Diagnosis Color value
        $high = 0;
        $medium = 0;
        $low = 0;
        $language = $request->header('locale');
        $this->preDignosisText = Settings::where(function($query) use($language){
                ($language == Language::ENGLISH)
                    ?   $query->where('key','pre_diagnosis_text')
                    :   $query->where('key','pre_diagnosis_text_ur');
            })->value('value') ?? null;
        foreach($diagnosisColor as $color){
            $color == 1 ? $low++ : ($color == 2 ? $medium++ : $high++);
        }

        if($high >= $medium && $high > $low){
            $this->diagnosis_color = Settings::where('key', 'health_scan_high')->value('value');
            $this->general_recommendation = Settings::where(function($query) use($language){
                ($language == Language::ENGLISH)
                    ?   $query->where('key', 'general_recommendation')
                    :   $query->where('key', 'general_recommendation_ur');
            })->value('value');
            $this->diagnosis_text = $this->preDignosisText." ".$this->high_diagnosis_text." ".$this->medium_diagnosis_text." ".$this->low_diagnosis_text;

        }elseif($high < $medium && $high <= $low && $high != 0){
            $this->diagnosis_color = Settings::where('key', 'health_scan_medium')->value('value');
            $this->diagnosis_text = $this->preDignosisText." ".$this->high_diagnosis_text.",".$this->medium_diagnosis_text." ".$this->low_diagnosis_text;
        }elseif($high == 0){
            $this->diagnosis_color = Settings::where('key', 'health_scan_low')->value('value');
            $this->diagnosis_text = $this->preDignosisText." ".$this->high_diagnosis_text.",".$this->medium_diagnosis_text." ".$this->low_diagnosis_text;
        }else{
            $this->diagnosis_color = Settings::where('key', 'health_scan_low')->value('value');
            $this->diagnosis_text = $this->preDignosisText." ".$this->high_diagnosis_text.",".$this->medium_diagnosis_text." ".$this->low_diagnosis_text;
        }


        //////////////////////////// CALCULATE SEHAT SCORE/////////////////////////////////////////

        $final_score = ($this->sehat_score != 0) ? ($this->sehat_score / ($total_vitals * 2)) * 10 : 0;
        $final_score = number_format((float)$final_score, 2, '.', '');
        $f = explode(".",$final_score);

        if(isset($f[1])){
            $f[1] = $f[1] / 100;

            if($f[1] > 0.56){
                $this->sehat_score = ceil($final_score);
            }else{
                $this->sehat_score = floor($final_score);
            }
        }

        return [
            'doctor_id' => array_unique($doctorIds),
            'article_id' => array_unique($articleIds),
        ];
    }
    private function saveDiagnosis($diagnosis){
        HealthScan::where('id', $this->id)->update(['diagnosis' => Helper::sanitizeData($diagnosis)]);
    }
}
