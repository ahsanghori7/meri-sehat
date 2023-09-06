<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Http\Resources\User\UserDetailResource;
use App\Models\{Clinic, ClinicTiming, DoctorClinic, User};
use Carbon\Carbon;

class ClinicController extends Controller
{
    public function createClinic(Request $request, $id = null)
    {
        try{
            DB::beginTransaction();
            $input = $request->all();
            $userId = $request->header('user_id');

            $validator = Validator::make($input, [
                'clinic_id' => ['required'],
                'consultation_fee' => ['required'],
                'consultation_duration' => ['required'],

                'clinic_timing' => ['required','array'],
                'clinic_timing.*.day' => ['required'],
                'clinic_timing.*.start_time' => ['required'],
                'clinic_timing.*.end_time' => ['required'],
            ]);
            if($id){
                $validator = Validator::make($input, [
                    'clinic_id' => ['required'],
                    'consultation_fee' => ['sometimes'],
                    'consultation_duration' => ['sometimes'],

                    'clinic_timing' => ['sometimes','array'],
                    'clinic_timing.*.day' => ['sometimes'],
                    'clinic_timing.*.start_time' => ['sometimes'],
                    'clinic_timing.*.end_time' => ['sometimes'],
                ]);
            }
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            $doctorClinics = [
                'doctor_id' => $userId,
                'consultation_fee' => $input['consultation_fee'],
                'clinic_id' => $input['clinic_id'],
                'consultation_duration' => str_contains($input['consultation_duration'], ":") ? Carbon::parse($input['consultation_duration'])->format('H:i') : $input['consultation_duration'],
            ];

            if($id){
                $getDoctorClinic = DoctorClinic::find($id);
                if(isset($request->clinic_timing) && count($request->clinic_timing) && isset($getDoctorClinic->clinicTimings)){
                    $getDoctorClinic->clinicTimings->delete();
                }
                $getDoctorClinic->update([
                    'consultation_fee' => $input['consultation_fee'],
                    'consultation_duration' => str_contains($input['consultation_duration'], ":") ? Carbon::parse($input['consultation_duration'])->format('H:i') : $input['consultation_duration'],
                ]);
            }else{
                $getDoctorClinic = DoctorClinic::updateOrCreate([
                    'doctor_id' => $userId,
                    'clinic_id' => $doctorClinics['clinic_id'],
                ],$doctorClinics);
            }

            ClinicTiming::where('doctor_clinic_id', $getDoctorClinic->id)->delete();

            foreach($request->clinic_timing as $clinicTiming) {
                ClinicTiming::create([
                    'doctor_clinic_id' => $getDoctorClinic->id,
                    'day' => strtolower($clinicTiming['day']),
                    'start_time' => Carbon::parse($clinicTiming['start_time'])->format('H:i'),
                    'end_time' => Carbon::parse($clinicTiming['end_time'])->format('H:i'),
                    'is_physical' => $clinicTiming['is_physical'],
                ]);
            }
            if(!$getDoctorClinic){
                return $this->returnResponse(400, 'Unable to create or update doctor clinic.');
            }
            DB::commit();
            return $this->returnResponse(200, 'Create clinic timings successfully.');
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to get all the time slots with respect their timings
     */
    public function getAllTimeSlots(Request $request, $doctorClinicId)
    {
        try{
            $validator = Validator::make($request->all(), [
                'date' => ['required', 'date_format:Y-m-d'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, 'Invalid date format.');
            }
            if($doctorClinicId){
                $doctorClinicDetails = DoctorClinic::where(['id' => $doctorClinicId, 'status' => true])->first();
            }else{
                $doctorClinicDetails = DoctorClinic::where(['doctor_id' => $request->doctor_id, 'status' => true])->where(function($query){
                    $query->orWhere('clinic_id', null);
                    $query->orWhere('clinic_id', 0);
                })->first();
            }
            if(!$doctorClinicDetails){
                return $this->returnResponse(404, 'Invalid doctor clinic id.');
            }
            return $this->returnResponse(200, '', DoctorClinic::doctorClinicTimings($doctorClinicDetails, $request->date));
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to enable / disable the clinics
     */
    public function updateStatus(Request $request, $clinicId)
    {
        try{
            $getDoctorClinic = DoctorClinic::where(['id' => $clinicId, 'doctor_id' => $request->header('user_id')])->first();
            if(!$getDoctorClinic){
                return $this->returnResponse(404, 'Invalid doctor clinic id.');
            }
            $getDoctorClinic->update(['status' => !($getDoctorClinic->status)]);
            return $this->returnResponse(200, 'Update clinic successfully.');
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to enable / disable the clinics
     */
    public function deleteClinic(Request $request, $clinicId)
    {
        try{
            $getDoctorClinic = DoctorClinic::where(['id' => $clinicId, 'doctor_id' => $request->header('user_id')])->first();
            if(!$getDoctorClinic){
                return $this->returnResponse(404, 'Invalid doctor clinic id.');
            }
            $getDoctorClinic->delete();
            return $this->returnResponse(200, 'Clinic deleted successfully.');
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
}
