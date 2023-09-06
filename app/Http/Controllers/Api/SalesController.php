<?php

namespace App\Http\Controllers\Api;

use App\Http\Common\Constant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Common\Helper as CustomHelper;
use Illuminate\Support\Str;
use App\Models\{Clinic,
    Disease,
    DoctorClinic,
    Feedbacks,
    Sales,
    SalesVisit,
    User,
    ApiToken,
    Appointment,
    Article,
    City,
    DoctorDetail,
    DoctorService,
    DoctorEducation,
    Faq,
    DoctorSpeciality,
    Language,
    Newsletter,
    Service,
    Speciality,
    UserArticleReview,
    UserSubscription,
    VisitType,
    Degree,
    University,
    DoctorExperience,
    ClinicTiming};
use App\Http\Resources\User\{AppointmentResource,
    ClinicResource,
    DoctorClinicResource,
    DoctorResource,
    UserDetailResource,
    UserDoctorResource,
    UserResource,
    UserSummeryResource
    };
use App\Mail\Mails;
use Carbon\{Carbon, CarbonPeriod};
use Illuminate\Support\Facades\{Auth, DB, Hash, Mail, Validator};
use App\Http\Common\{Helper, SmsHelper, EmailHelper};
use Illuminate\Support\Arr;

class SalesController extends Controller
{


    public function login(Request $request)
    {
        try
        {
            $input = $request->all();
            $validator = Validator::make($input, [
                'email' => ['required', 'email', 'exists:sales,email'],
                'password' => ['required'],
                'platform' => ['required'],
                'device_id' => ['required']
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            $getUser = Sales::where(['status' => true, 'email' => $request->email])->first();

            $passwordCheck = Hash::check($request->password, $getUser->password);
            if(!$passwordCheck) {
                return $this->returnResponse(400, 'Invalid email or password');
            }
            $token = md5(uniqid(rand(), true));
            $api_token = ApiToken::updateOrCreate([
                'device_id' => $request->device_id,
            ], [
                    'platform' => $request->platform,
                    'fcm_token' => $request->fcm_token ?? '',
                    'token' => $token,
                    'app_version' => $request->app_version ?? "v1.0",
                    'expiry_date' => now()->addDays(2),
                    'user_id' => $getUser->id,
                ]
            );
            return $this->returnResponse(200, 'Successfully logged in!', [
                'mask_email' => CustomHelper::emailMasking($getUser->email),
                'mask_phone' => CustomHelper::phoneMasking($getUser->phone),
                'token' => $api_token->token
            ]);
        }
        catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try{
            $api_token = ApiToken::where('token', $request->bearerToken())->first()->delete();
            return $this->returnResponse(200, 'Logged out successfully.');
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function signup(Request $request)
    {
        try
        {
            $input = $request->all();
            $validator = Validator::make($input, [
                'city_id' => ['required'],
                'name' => ['required'],
                'email' => ['required', 'email', 'unique:sales,email'],
                'password' => ['required'],
                'phone' => ['required'],
                'gender' => ['required'],
                'birth_date' => ['sometimes'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            $getUser = Sales::create([
                'role_id' => $this->getConstantByValue("SALE_ROLE_ID"),
                'city_id' => $request->city_id,
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
                'status' => 1,
            ]);

            if(!$getUser) {
                return $this->returnResponse(400, 'Cannot create sales agent');
            }

            return $this->returnResponse(200, 'Successfully created sales agent!', [
                'mask_email' => CustomHelper::emailMasking($getUser->email),
                'mask_phone' => CustomHelper::phoneMasking($getUser->phone),
            ]);
        }
        catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    /**
     * This methoid is used to get User details
     */
    public function getUser(Request $request){
        try{
            $userId = isset($request->user) ? $request->user : $request->header('user_id');
            $getUser = Sales::find($userId);
            if(!$getUser){
                return $this->returnResponse(400, 'User not found.');
            }
            return $this->returnResponse(200, 'User Details.', ['user' => $getUser]);
        }
        catch(\Exception $e){
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to update User profile
     */
    public function updateProfile(Request $request){
        try{
            $input = $request->all();
            $validator = Validator::make($input, [
                'city_id' => ['required', 'number'],
                'name' => ['required'],
                'email' => ['required', 'email', 'exists:sales,email'],
                'password' => ['required'],
                'phone' => ['required'],
                'gender' => ['required'],
                'birth_date' => ['sometimes'],
                'status' => ['sometimes'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }

            $getUser = Sales::find($request->userId);
            $updateUser = $getUser->update($input);
            if(!$updateUser){
                return $this->returnResponse(400, 'Unable to update user profile.');
            }
            return $this->returnResponse(200, 'Updated user profile successfully.', ['user' => new UserResource(User::find($getUser->id))]);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function salesVisit(Request $request)
    {
        try{
            $status = 'unapproved';
            $distance = 0;
            DB::beginTransaction();
            $input = $request->all();

            $validator = Validator::make($input, [
                'user_id' => ['required'],
                'doctor_id' => ['required'],
                'clinic_id' => ['required'],
                'visit_type' => ['required'],
                'feedback_type' => ['required'],
                'comments' => ['required'],
                'lat' => ['required'],
                'long' => ['required']
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            $clinic = Clinic::find($request->clinic_id);
            if ($clinic) {
                if ($clinic->lat != '' && $clinic->long != '') {
                    $distance = \KMLaravel\GeographicalCalculator\Facade\GeoFacade::setPoint([$clinic->lat, $clinic->long])
                        ->setOptions(['units' => ['mile']])
                        ->setPoint([$request->lat, $request->long])
                        ->getDistance(function (\Illuminate\Support\Collection $result) {
                            return $result->first();
                        });
                    $distance = $distance['mile'] ?? 0;
                }
            }

            if ($distance <= 5) {
                $status = 'approved';
            }

            $createSalesType = SalesVisit::create([
                'sales_id' => $request->user_id,
                'doctor_id' => $request->doctor_id,
                'clinic_id' => $request->clinic_id,
                'visit_type' => $request->visit_type,
                'feedback_type' => $request->feedback_type,
                'comments' => $request->comments,
                'lat' => $request->lat,
                'long' => $request->long,
                'status' => $status
            ]);

            if(!$createSalesType){
                return $this->returnResponse(400, 'Unable to create sales visit.');
            }
            DB::commit();
            return $this->returnResponse(200, 'Sales visit has been created successfully.');
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function doctorRegistration(Request $request)
    {
        try{
            DB::beginTransaction();
            $input = $request->all();

            $validator = Validator::make($input, [
                'name' => ['required'],
                'phone' => ['required', 'unique:users,phone'],
                'email' => ['required', 'email', 'unique:users,email'],
                'gender' => ['required'],
                //'password' => ['required'],
                'city_id' => ['required', 'exists:cities,id'],
                'prefix' => ['required'],
                'cnic' => ['required'],
                'experience_year' => ['required'],
                'pmc_no' => ['required'],
                'waiting_time' => ['required'],
                'about' => ['required'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }

            $createdDoctorUser = [
                'role_id' => $this->getConstantByValue("DOCTOR_ROLE_ID"),
                'city_id' => $request->city_id,
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'gender' => $request->gender,
                'birth_date' => $request->has('birth_date') ? Carbon::parse(strtotime($request->birth_date))->format('Y-m-d') : null,
                //'password' => Hash::make($request->password),
            ];
            if($request->hasFile('image')){
                $createdDoctorUser['image'] = $request->image->store('user', Helper::STATIC_ASSET_DISK);
            }
            $createDoctor = User::create($createdDoctorUser);

            $doctorDetails = DoctorDetail::create([
                'doctor_id' => $createDoctor->id,
                'prefix' => $request->prefix,
                'cnic' => $request->cnic,
                'experience_year' => $request->experience_year,
                'pmc_no' => $request->pmc_no,
                'waiting_time' => $request->waiting_time,
                'about' => $request->about,
                'badge' => $request->has('badge')?$request->badge:null,
            ]);

            DoctorSpeciality::where('doctor_id',$createDoctor->id)->delete();
            if(isset($request->specialities) && count($request->specialities) > 0){
                foreach($request->specialities as $speciality){
                    $DoctorSpeciality = [
                        'doctor_id' => $createDoctor->id,
                        'speciality_id' => $speciality,
                    ];
                DoctorSpeciality::create($DoctorSpeciality);
                }
            }

            DoctorService::where('doctor_id',$createDoctor->id)->delete();
            if(isset($request->services) && count($request->services) > 0){
                foreach($request->services as $service){
                    $DoctorService =[
                        'doctor_id' => $createDoctor->id,
                        'service_id' => $service,
                    ];
                    DoctorService::create($DoctorService);
                }
            }

            $this->educationExperience($request, $createDoctor->id);
            $this->availability($request, $createDoctor->id);
            if(!$createDoctor || !$doctorDetails){
                return $this->returnResponse(400, 'Unable to create doctor profile.');
            }
            DB::commit();
            return $this->returnResponse(200, 'Doctor profile has been created successfully.');
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function educationExperience(Request $request, $id){
        if(isset($request->degree) && count($request->degree) > 0){
            DoctorEducation::where('doctor_id', $id)->delete();
            foreach($request->degree as $key => $degree){
                $DoctorEducation =[
                    'doctor_id' => $id,
                    'degree' => $degree,
                    'institute' => $request->institute[$key],
                    'year_of_completion' => $request->year_of_completion[$key],
                ];
                if( $request->institute[$key] == "other"){
                    $created_university = University::create([
                        'name' => $request->other_institute_name[$key],
                        'sector' => $request->other_institute_sector[$key],
                        'city' => $request->other_institute_city[$key],
                    ]);
                    $DoctorEducation['institute'] = $created_university->id;
                }
                DoctorEducation::create($DoctorEducation);
            }
        }
        if(isset($request->experience_institute[0]) && count($request->experience_institute) > 0){
            DoctorExperience::where('doctor_id', $id)->delete();
            foreach($request->position as $key => $position){
                $DoctorExperience =[
                    'doctor_id' => $id,
                    'position' => $position,
                    'institute' => $request->experience_institute[$key],
                    'start_year' => $request->start_year[$key],
                    'end_year' => $request->end_year[$key],
                ];
                DoctorExperience::create($DoctorExperience);
            }
        }
        if(isset($request->collaborations) && count($request->collaborations) > 0){
            DoctorDetail::where('doctor_id',$id)->update(['collaborations' => json_encode($request->collaborations)]);
        }
        return $id;
    }

    public function availability(Request $request, $id){
        if(isset($request->clinic[0]) && isset($request->consultation_fee[0]) && isset($request->consultation_duration[0]) && isset($request->start_time[0][0]) && isset($request->end_time[0][0]) && count($request->clinic) > 0){
            $doctor_clinic_ids = DoctorClinic::where('doctor_id', $id)->where('clinic_id', '!=', null)->pluck('id')->toArray();
            DoctorClinic::where('doctor_id', $id)->where('clinic_id', '!=', null)->delete();
            ClinicTiming::whereIn('doctor_clinic_id', $doctor_clinic_ids)->delete();
            foreach($request->clinic as $key => $clinic){
                $DoctorClinic = [
                    'doctor_id' => $id,
                    'clinic_id' => $clinic,
                    'consultation_fee' => $request->consultation_fee[$key],
                    'consultation_duration' => $request->consultation_duration[$key],
                ];
                if( $request->clinic[$key] == "other"){
                    $created_clinic = Clinic::create([
                        'name' => $request->other_clinic_name[$key],
                        'address' => $request->other_clinic_address[$key],
                    ]);
                    $DoctorClinic['clinic_id'] = $created_clinic->id;
                }
                $created_doctor_clinic = DoctorClinic::create($DoctorClinic);
                foreach($request->day[$key] as $time_key => $day){
                    $periods = CarbonPeriod::create($request->start_time[$key][$time_key], $request->consultation_duration[$key] . " minutes", $request->end_time[$key][$time_key]);
                    if(count($periods)){
                        foreach($periods as $periodKey => $periodValue){
                            if(count($periods)-1 != $periodKey){
                                $ClinicTiming = [
                                    'doctor_clinic_id' => $created_doctor_clinic->id,
                                    'day' => $day,
                                    'start_time' => $periodValue->format('H:i:s'),
                                    'end_time' => $periodValue->addMinutes($request->consultation_duration[$key])->format('H:i:s'),
                                ];
                                ClinicTiming::create($ClinicTiming);
                            }
                        }
                    }
                }
            }
        }
        return $id;
    }

    public function updateDoctor(Request $request)
    {
        try{
            DB::beginTransaction();
            $input = $request->all();
            $userId = $this->getUserIdFromHeader($request->header());
            $validator = Validator::make($input, [
                'phone' => ['required', 'regex:/(03)[0-9]{9}$/'],
                'email' => ['required', 'email'],
                'gender' => ['required'],
                'name' => ['required'],
                'experience_year' => ['required'],
                'city_id' => ['required','exists:cities,id'],
                'education' => ['required','array'],
                'birth_date' => ['required','date'],
                'speciality' => ['required','array'],
                'speciality.*' => ['required','exists:specialities,id'],
                'service' => ['required','array'],
                'service.*' => ['required','exists:services,id'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }

            $updateDoctorUser = [
                'city_id' => $request->city_id,
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'gender' => $request->gender,
                'birth_date' => $request->has('birth_date') ? Carbon::parse(strtotime($request->birth_date))->format('Y-m-d') : null,
            ];
            if($request->hasFile('image')){
                $updateDoctorUser['image'] = $request->image->store('user', Helper::STATIC_ASSET_DISK);
            }

            $updateDoctor = User::where(['status' => true, 'is_blocked' => false, 'id' => $userId])->update($updateDoctorUser);

            DoctorDetail::where('doctor_id',$userId)->update([
                'experience_year' =>$request->experience_year,
                'is_physical_consultancy' => isset($request->is_physical_consultancy) ? $request->is_physical_consultancy : 0,
                'is_video_consultancy' => isset($request->is_video_consultancy) ? $request->is_video_consultancy : 0,
                'is_voice_consultancy' => isset($request->is_voice_consultancy) ? $request->is_voice_consultancy : 0,
            ]);

            foreach($request->speciality as $specialityId) {
                DoctorService::updateOrCreate(
                [
                    'doctor_id' => $userId,
                    'service_id' => $specialityId,
                ], [
                    'doctor_id' => $userId,
                    'service_id' => $specialityId,
                ]);
            }
            foreach($request->speciality as $specialityId) {
                DoctorSpeciality::updateOrCreate(
                [
                    'doctor_id' => $userId,
                    'speciality_id' => $specialityId,
                ],
                [
                    'doctor_id' => $userId,
                    'speciality_id' => $specialityId,
                ]);
            }

            foreach($request->education as $educations) {
                DoctorEducation::updateOrCreate(
                [
                    'degree' => $educations['degree'],
                ],
                [
                    'doctor_id' => $userId,
                    'degree' => $educations['degree'],
                    'institute' => $educations['institute'],
                    'year_of_completion' => isset($educations['year_of_completion']) ? $educations['year_of_completion'] : "",
                    'is_completed' => $educations['is_completed'],
                ]);
            }
            $this->educationExperience($request, $userId);
            $this->availability($request, $userId);
            if(!$updateDoctor){
                return $this->returnResponse(400, 'Unable to update user profile.');
            }
            DB::commit();
            return $this->returnResponse(200, 'Updated user profile successfully.', ['user' => new UserSummeryResource(User::find($userId))]);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }


    public function doctors(Request $request)
    {
        try {
            $getDoctors = User::where('role_id', $this->getConstantByValue('DOCTOR_ROLE_ID'))->get();
            if(!count($getDoctors)){
                return $this->returnResponse(400, 'There is no doctor available right now.');
            }
            return $this->returnResponse(200, 'Doctors fetch successfully.', ['doctors' => UserDoctorResource::collection($getDoctors)]);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function visits(Request $request)
    {
        try {
            $getVisitTypes = VisitType::all();
            if(!count($getVisitTypes)){
                return $this->returnResponse(400, 'There is no visit types available right now.');
            }
            return $this->returnResponse(200, '', $getVisitTypes);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function feedbacks(Request $request)
    {
        try {
            $getFeedbacks = Feedbacks::all();
            if(!count($getFeedbacks)){
                return $this->returnResponse(400, 'There is no feedbacks available right now.');
            }
            return $this->returnResponse(200, '', $getFeedbacks);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function getClinics(Request $request)
    {
        return $this->returnResponse(200, '', Clinic::getAllClinics());
    }

    public function doctorClinic(Request $request, $doctor_id)
    {

        try {
            if (!$doctor_id) {
                return $this->returnResponse(400, 'Doctor is required.');
            }

            $getClinics = DoctorClinic::where('doctor_id', $doctor_id)->groupBy('clinic_id')->get();
            if(!count($getClinics)){
                return $this->returnResponse(400, 'There is no clinics available right now.');
            }
            return $this->returnResponse(200, '', ClinicResource::collection($getClinics));
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function getCities(Request $request)
    {
        try {
            $getCities = City::all();
            if(!count($getCities)){
                return $this->returnResponse(400, 'There is no city available right now.');
            }
            return $this->returnResponse(200, '', $getCities);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function getSpecialities(Request $request)
    {
        try {
            $getSpecialities = Speciality::all();
            if(!count($getSpecialities)){
                return $this->returnResponse(400, 'There is no speciality right now.');
            }
            return $this->returnResponse(200, '', $getSpecialities);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function getServices(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'specialities' => ['required'],
        ]);
        if ($validator->fails()) {
            return $this->returnResponse(400, $validator->errors()->first());
        }
        $general_response = ['status' => true];
        try{
            if($request->has('specialities')){
                $specialities = explode(',', $request->specialities);
                $data = Service::whereIn('speciality_id',$specialities)->where('status', true)->get();
                return $this->returnResponse(200, '', $data);
            }
            return $this->returnResponse(400, 'Please select specialities');
        }catch(\Exception $e){
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function getDegrees(Request $request)
    {
        return $this->returnResponse(200, '', Degree::getAllDegrees());
    }

    public function getUniversities(Request $request)
    {
        return $this->returnResponse(200, '', University::getAllUniversities());
    }

    public function getDesinations(Request $request)
    {
        return $this->returnResponse(200, '', config('app.designations'));
    }
}
