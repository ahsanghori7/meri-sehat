<?php

namespace App\Http\Resources\User;

use App\Models\HealthScan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Common\Helper;
use App\Http\Common\Constant;
use App\Http\Resources\Article\ArticleSummeryResource;

class HealthScanCurrentMonthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $hash_color = '#ff0000';
        $withouthash_color = 'ff0000';
        $latest_record = HealthScan::where('user_id', $this->user_id)->whereDate('created_at', Carbon::parse($this->created_at))->orderBy('id', 'DESC')->first();
        if (isset($latest_record)) {
            $score = $this->calculate_sehat_score($latest_record);
            if ($score >= 4 && $score <= 6) {
                $hash_color = '#ffc000';
                $withouthash_color = 'ffc000';
            } elseif ($score >= 7 && $score <= 10) {
                $hash_color = '#70ad47';
                $withouthash_color = '70ad47';
            }
        }
        return [
            'date' => Carbon::parse($this->created_at)->format('Y-m-d'),
            'hex_with_hash_color' => $hash_color,
            'hex_without_hash_color' => $withouthash_color,
        ];
    }

    public function calculate_sehat_score($request) {

        $sehat_score = 0.0;
        $total_vitals = 0;
        // For Blood Pressure
        if(isset($request->blood_pressure)){
            $total_vitals = $total_vitals + 1;
            $bloodPressure = explode('/', $request->blood_pressure)[0];
            if($bloodPressure >= 140){
                $sehat_score = $sehat_score + 1.5;
            }else if($bloodPressure <= 139 && $bloodPressure >= 110){
                $sehat_score = $sehat_score +  2;
            }else{
                $sehat_score = $sehat_score +  0.5;
            }
        }
        // For Respiratory Level
        if(isset($request->respiratory_rate) && !empty($request->respiratory_rate) && $request->respiratory_rate){
            $total_vitals = $total_vitals + 1;
            if($request->respiratory_rate >= 22){
                $sehat_score = $sehat_score +  1.5;
            }else if($request->respiratory_rate >= 12 && $request->respiratory_rate <= 21){
                $sehat_score = $sehat_score +  2;
            }else{
                $sehat_score = $sehat_score +  0.5;
            }
        }
        // For SPO2
        if(isset($request->spo2) && !empty($request->spo2) && $request->spo2){
            $total_vitals = $total_vitals + 1;
            if($request->spo2 >= 95){
                $sehat_score = $sehat_score +  2;
            }elseif($request->spo2 >= 90 && $request->spo2 <= 94){
                $sehat_score = $sehat_score +  1.5;
            }else{
                $sehat_score = $sehat_score +  0.5;
            }
        }
        // For Heart Rate / Pulse Rate
        if(isset($request->heart_rate) && !empty($request->heart_rate) && $request->heart_rate){
            $total_vitals = $total_vitals + 1;
            if($request->heart_rate >= 100){
                $sehat_score = $sehat_score +  1.5;
            }else if($request->heart_rate < 100 && $request->heart_rate >= 60){
                $sehat_score = $sehat_score +  2;
            }else{
                $sehat_score += 0.5;
            }
        }
        // For SDNN
        if(isset($request->sdnn) && !empty($request->sdnn) && $request->sdnn){
            $total_vitals = $total_vitals + 1;
            if($request->sdnn > 100){
                $sehat_score = $sehat_score +  2;
            }elseif($request->sdnn <= 100 && $request->heart_rate >= 50){
                $sehat_score = $sehat_score +  1.5;
            }else{
                $sehat_score = $sehat_score +  0.5;
            }
        }
        //////////////////////////// CALCULATE SEHAT SCORE/////////////////////////////////////////
        $final_score = ($sehat_score / ($total_vitals * 2)) * 10;

        $final_score = number_format((float)$final_score, 2, '.', '');
        $f = explode(".",$final_score);

        if(isset($f[1])){
            $f[1] = $f[1] / 100;
            if($f[1] > 0.56){
                $sehat_score = ceil($final_score);
            }else{
                $sehat_score = floor($final_score);
            }
        }
        return $sehat_score;
    }
}
