<?php

namespace App\Http\Controllers\Api;
use App\Http\Common\Constant;

class DashboardController extends Controller
{
    public function getDoctors(Request $request)
    {
        try {
            $getDoctors = User::getAllDoctors($request->header('user_id'), $this->getConstantByValue('DOCTOR_ROLE_ID'), $request->doctor, $request->city, $request->speciality, $request->service, $request->q, $request->is_appointment, $request->is_featured);
            if(!count($getDoctors)){
                return $this->returnResponse(400, 'There is no doctor\'s available right now.');
            }
            return $this->returnResponse(200, '', UserSummeryResource::collection($getDoctors));
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function getEarning(Request $request)
    {
        try {
            $user_id = $request->header('user_id');
            $user = User::where('id', $user_id)->with('hasDoctor')->first();
            $bank_info = $user->doctorBankDetails ?? null;

            $today_date = Carbon::now();
            $strt_date_this_month = $today_date->firstOfMonth()->format('Y-m-d');
            $end_date_this_month = $today_date->lastOfMonth()->format('Y-m-d');
            $today_date = Carbon::now()->format('Y-m-d');

            $earnings = Appointment::where(['doctor_id' => $user_id, 'progress' => (new Constant)->APPOINTMENT_STATUS_COMPLETED])->get();
            $earnings = count($earnings) > 0 ? $earnings : null;


            $this_month = $this->getEarningBaseQuery($user_id)->whereBetween('date', [$strt_date_this_month, $end_date_this_month])->sum('doctor_total');
            $all_time = $this->getEarningBaseQuery($user_id)->sum('doctor_total');
            $today = $this->getEarningBaseQuery($user_id)->where('date', $today_date)->sum('doctor_total');
            $online_consultation = $this->getEarningBaseQuery($user_id)->where('type', 'schedule')->sum('doctor_total');
            $in_person_appointment = $this->getEarningBaseQuery($user_id)->where('type', 'in-person')->sum('doctor_total');


            $earning_totals = [
                'this_month' => $this_month,
                'all_time' => $all_time,
                'today' => $today,
                'in_person_appointment' => $in_person_appointment,
                'online_consultation' => $online_consultation,
            ];
            $bank_details = [
                'account_name' => $bank_info != null ? $bank_info->account_name: '-',
                'iban_number' => $bank_info != null ? $bank_info->iban_number: '-',
                'bank_name' => $bank_info != null ? $bank_info->bank_name: '-',
                'account_number' => $bank_info != null ? $bank_info->account_number: '-',
            ];

            if (!$bank_info) {
                $bank_details = '';
            }

            $data = [
                'bank_details' => $bank_details,
                'earning_totals' => $earning_totals,
                'earnings' => $earnings,
            ];

            return $this->returnResponse(200, '', $data);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to update the available status of the doctor
     */
    public function updateAvailablity(Request $request)
    {
        try {
            $getDoctorDetails = User::find($request->header('user_id'));
            if(!$getDoctorDetails){
                return $this->returnResponse(400, 'There is no doctor\'s available right now.');
            }
            $getDoctorDetails->doctorDetail()->update(['is_available' => !$getDoctorDetails->doctorDetail->is_available]);
            return $this->returnResponse(200, '', new UserSummeryResource(User::find($request->header('user_id'))));
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to Get lisiting of the clinics with their time slots
     */
    public function clinicListing(Request $request)
    {
        try {
            $doctorId = $request->doctor_id ?? $request->header('user_id');
            $getDoctorDetails = User::where(['id' => $doctorId, 'role_id' => (new Constant)->DOCTOR_ROLE_ID])->first();
            if(!$getDoctorDetails){
                return $this->returnResponse(400, 'You are not authorized to get the details.');
            }
            $getDoctorClinic = DoctorClinic::getClinicWithTimeSlots($doctorId, $request->is_physical);
            if(!count($getDoctorClinic)){
                return $this->returnResponse(400, 'You are create any clinics yet.');
            }
            return $this->returnResponse(200, '', $getDoctorClinic);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    function getEarningBaseQuery($user_id){
        return $base_query = Appointment::where(['doctor_id' => $user_id, 'progress' => (new Constant)->APPOINTMENT_STATUS_COMPLETED]);
    }

}
