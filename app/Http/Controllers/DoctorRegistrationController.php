<?php

namespace app\Http\Controllers;

use App\Http\Common\Constant;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Common\Helper;
use App\Models\{
    Certification,
    City,
    User,
    Speciality,
    Service,
    DoctorDetail,
    DoctorSpeciality,
    DoctorService,
    DoctorEducation,
    DoctorExperience,
    DoctorClinic,
    Degree,
    Clinic,
    ClinicTiming,
    Disease,
    DoctorCertification,
    DoctorCondition,
    University,
    Language,
    Institute,
    Designation
};
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Support\Facades\Validator;

class DoctorRegistrationController extends Controller
{
    public function index(Request $request){
        return view('register_doctor.index');
    }


    public function about(Request $request, $id = null){
        if($request->isMethod('post')){
            // dd($request->All());
            $validator = Validator::make($request->all(), [
                'phone' => ['required','unique:users,phone', 'regex:/(03)[0-9]{9}$/'],
            ]);


            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            if($id){
                $user = [
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'birth_date' => Carbon::parse(strtotime($request->birth_date))->format('Y-m-d'),
                    'gender' => $request->gender,
                    'city_id' => $request->city_id,
                ];
                if($request->hasFile('image')){
                    $user['image'] = $request->image->store('doctor', Helper::STATIC_ASSET_DISK);
                }
                User::where('id',$id)->update($user);
                $doctor = [
                    'pmc_no' => $request->pmc_no,
                    'experience_year' => $request->expeirence_years,
                    'about' => $request->about,
                    'waiting_time' => $request->waiting_time,
                    'prefix' => $request->prefix,
                    'badge' => $request->badge,
                ];
                if($request->hasFile('visiting_card_image')){
                    $doctor['visiting_card_image'] = $request->visiting_card_image->store('user', Helper::STATIC_ASSET_DISK);
                }
                 DoctorDetail::where('doctor_id',$id)->update($doctor);

                 DoctorSpeciality::where('doctor_id',$id)->delete();
                if(isset($request->specialities) && count($request->specialities) > 0){
                    foreach($request->specialities as $speciality){
                        $DoctorSpeciality = [
                            'doctor_id' => $id,
                            'speciality_id' => $speciality,
                        ];
                    DoctorSpeciality::create($DoctorSpeciality);
                    }
                }

                DoctorService::where('doctor_id',$id)->delete();
                if(isset($request->services) && count($request->services) > 0){
                    foreach($request->services as $service){
                        $DoctorService =[
                            'doctor_id' => $id,
                            'service_id' => $service,
                        ];
                        DoctorService::create($DoctorService);
                    }
                }
                return redirect(route('register_doctor_education_experience',['id' => $id]));

            }else{
                $user = [
                    'role_id' => (new Constant)->DOCTOR_ROLE_ID,
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'birth_date' => Carbon::parse(strtotime($request->birth_date))->format('Y-m-d'),
                    'gender' => $request->gender,
                    'city_id' => $request->city_id,
                ];
                if($request->hasFile('image')){
                    $user['image'] = $request->image->store('user', Helper::STATIC_ASSET_DISK);
                }
                $created_user = User::create($user);

                $doctor = [
                    'doctor_id' => $created_user->id,
                    'pmc_no' => $request->pmc_no,
                    'experience_year' => $request->expeirence_years,
                    'about' => $request->about,
                    'waiting_time' => $request->waiting_time,
                    'collaborations' => json_encode([]),
                    'is_physical_consultancy' => 1,
                    'is_video_consultancy' => 1,
                    'is_voice_consultancy' => 1,
                    'prefix' => $request->prefix,
                    'badge' => $request->badge,
                ];
                if($request->hasFile('visiting_card_image')){
                    $doctor['visiting_card_image'] = $request->visiting_card_image->store('user', Helper::STATIC_ASSET_DISK);
                }
                $created_doctor = DoctorDetail::create($doctor);
                if(isset($request->specialities) && count($request->specialities) > 0){
                    foreach($request->specialities as $speciality){
                        $DoctorSpeciality = [
                            'doctor_id' => $created_user->id,
                            'speciality_id' => $speciality,
                        ];
                    DoctorSpeciality::create($DoctorSpeciality);
                    }
                }


                if(isset($request->services) && count($request->services) > 0){
                    foreach($request->services as $service){
                        $DoctorService =[
                            'doctor_id' => $created_user->id,
                            'service_id' => $service,
                        ];
                        DoctorService::create($DoctorService);
                    }
                }
                return redirect(route('register_doctor_education_experience',['id' => $created_user->id]));
            }
        }else{
            $data['result'] = null;
            if($id){
                $data['result'] = User::find($id);
            }
            $data['cities'] = City::where(['status' => 1, 'lang_id' => Language::ENGLISH])->get();
            $data['specialities'] = Speciality::where(['status' => 1, 'lang_id' => Language::ENGLISH])->get();
            return view('register_doctor.about_you',$data);
        }
    }

    public function educationExperience(Request $request, $id){
        if($request->isMethod('post')){
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
                    if( $request->degree[$key] == "other"){
                        $created_degree = Degree::create([
                            'name' => $request->other_degree_name[$key],
                            'full_name' => $request->other_degree_full_name[$key] ?? '',
                        ]);
                        $DoctorEducation['degree'] = $created_degree->id;
                    }
                    DoctorEducation::create($DoctorEducation);
                }
            }
            if(isset($request->experience_institute) && count($request->experience_institute) > 0){
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

            return redirect(route('register_doctor_availability',['id' => $id]));

        }else{
            $data['result'] = null;
            if($id){
                $data['result'] = User::find($id);
            }
            $data['degrees'] = Degree::where('status', 1)->orderBy('name','asc')->get();
            $data['universities'] = University::where('status', 1)->orderBy('name','asc')->get();
            return view('register_doctor.education_experience',$data);
        }
    }

    public function educationExperienceApi(Request $request){
        try{
            if(Request()->segment(2) === "v2"){
                $id = \Auth::user()->id;
            }else{
                $id = $this->getUserIdFromHeader($request->header());
            }
            if($request->isMethod('post')){
                if(isset($request->education) && count($request->education) > 0){
                    DoctorEducation::where('doctor_id', $id)->delete();
                        foreach($request->education as $key => $education){
                            if(is_iterable($education['degree'])){
                                foreach($education['degree'] as $k => $v){
                                    DoctorEducation::create([
                                        'doctor_id' => $id,
                                        'degree' => $v,
                                        'institute' => $request->education[$key]['institute'],
                                        'year_of_completion' => $request->education[$key]['completion'],
                                    ]);
                                }
                            }
                            else{
                                DoctorEducation::create([
                                    'doctor_id' => $id,
                                    'degree' => $education['degree'],
                                    'institute' => $education['institute'],
                                    'year_of_completion' => $education['completion'],
                                ]);
                            }
                        // if( $request->institute[$key] == "other"){
                        //     $created_university = University::create([
                        //         'name' => $request->other_institute_name[$key],
                        //         'sector' => $request->other_institute_sector[$key],
                        //         'city' => $request->other_institute_city[$key],
                        //     ]);
                        //     $DoctorEducation['institute'] = $created_university->id;
                        // }
                        // if( $request->degree[$key] == "other"){
                        //     $created_degree = Degree::create([
                        //         'name' => $request->other_degree_name[$key],
                        //         'full_name' => $request->other_degree_full_name[$key] ?? '',
                        //     ]);
                        //     $DoctorEducation['degree'] = $created_degree->id;
                        // }
                    }
                }
                if(isset($request->experience) && count($request->experience) > 0){
                    DoctorExperience::where('doctor_id', $id)->delete();
                    foreach($request->experience as $key => $position){
                        $DoctorExperience =[
                            'doctor_id' => $id,
                            'position' => $position['position'],
                            'institute' => $position['experience_institute'],
                            'start_year' => $position['start_year'],
                            'end_year' => $position['end_year'],
                        ];
                        $experinceCreate=DoctorExperience::create($DoctorExperience);
                    }
                }

                if(isset($request->certification)){
                    DoctorCertification::where('doctor_id', $id)->delete();
                    if(is_iterable($request->certification)){
                        foreach($request->certification as $key => $certification){
                            DoctorCertification::create([
                                'doctor_id' => $id,
                                'certification_id' => $certification,
                            ]);
                        }
                    }
                    else{
                        DoctorCertification::create([
                            'doctor_id' => $id,
                            'certification_id' => $request->certification
                        ]);
                    }
                }
                return $this->returnResponse(200, 'created successfully', '');
            }
            else{
                $doctor = User::where('id', $id)->with('doctorExperiences', 'doctorEducation')->get();
                if($doctor){
                    return $this->returnResponse(200, '', $doctor);
                }
            }
        }
        catch(\Exception $e) {
            dd($e);
            return $this->returnResponse(500, $e->getMessage());
        }

    }

    public function educationApi(Request $request){
        try{
            if(Request()->segment(2) === "v2"){
                $id = \Auth::user()->id;
            }else{
                $id = $this->getUserIdFromHeader($request->header());
            }
            if($request->isMethod('post')){
                // dd(isset($request->educ[0]['degree']));
                $input = $request->all();
                $validator = Validator::make($input, [
                    'educ.*.institute' => 'required',
                    'educ.*.year_of_completion' => 'required',
                    'educ.*.degree' => 'required',

                ]);
                if ($validator->fails()) {
                    return $this->returnResponse(400, $validator->errors()->first());
                }

                if(isset($request->educ)){
                    DoctorEducation::where('doctor_id', $id)->delete();
                    foreach($request->educ as $key => $degree){
                        foreach($degree['degree'] as $value){
                            $DoctorEducation =[
                                'doctor_id' => $id,
                                'degree' => $value,
                                'institute' => $degree['institute'],
                                'year_of_completion' => $degree['year_of_completion'],
                            ];
                            $eduCreate=DoctorEducation::create($DoctorEducation);
                        }
                    }
                }
                    return $this->returnResponse(200, 'created eduction details successfully.', '');
            }
            else{
                $doctor = User::where('id', $id)->with('doctorExperiences', 'doctorEducation')->get();
                if($doctor){
                    return $this->returnResponse(200, '', $doctor);
                }
            }
        }
        catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }

    }

    public function experienceApi(Request $request){
        try{
            if(Request()->segment(2) === "v2"){
                $id = \Auth::user()->id;
            }else{
                $id = $this->getUserIdFromHeader($request->header());
            }
            if($request->isMethod('post')){
                $input = $request->all();
                $validator = Validator::make($input, [
                    'exp.*.position' => 'required',
                    'exp.*.institute' => 'required',
                    'exp.*.start_year' => 'required',
                    'exp.*.end_year' => 'required',

                ]);
                if ($validator->fails()) {
                    return $this->returnResponse(400, $validator->errors()->first());
                }
                if(isset($request->exp)){
                    DoctorExperience::where('doctor_id', $id)->delete();
                    foreach($request->exp as $key => $exp){
                        // dd($exp);
                            $DoctorExperience =[
                                'doctor_id' => $id,
                                'position' => $exp['position'],
                                'institute' => $exp['institute'],
                                'start_year'=> $exp['start_year'],
                                'end_year' => $exp['end_year'],
                            ];
                            $expCreate=DoctorExperience::create($DoctorExperience);

                    }
                }
                if(isset($request->certification)){
                    DoctorCertification::where('doctor_id', $id)->delete();
                    if(is_iterable($request->certification)){
                        foreach($request->certification as $key => $certification){
                            DoctorCertification::create([
                                'doctor_id' => $id,
                                'certification_id' => $certification,
                            ]);
                        }
                    }
                    else{
                        DoctorCertification::create([
                            'doctor_id' => $id,
                            'certification_id' => $request->certification
                        ]);
                    }
                }
                return $this->returnResponse(200, 'created experience details successfully.', '');

            }
            else{
                $doctor = User::where('id', $id)->with('doctorExperiences', 'doctorEducation')->get();
                if($doctor){
                    return $this->returnResponse(200, '', $doctor);
                }
            }

        }catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }


    }

    public function availability(Request $request, $id){
        if($request->isMethod('post')){
            if(isset($request->clinic[0]) &&
            isset($request->consultation_fee[0]) &&
            isset($request->consultation_duration[0]) &&
            isset($request->start_time[0][0]) &&
            isset($request->end_time[0][0]) &&
            count($request->clinic) > 0){
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
                        $periods = CarbonPeriod::create($request->start_time[$key][0], $request->consultation_duration[$key] . " minutes", $request->end_time[$key][0]);
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
            return redirect(route('register_doctor_online_consultation',['id' => $id]));
        }else{
            $data['result'] = null;
            if($id){
                $data['result'] = User::find($id);
            }
            $data['clinics'] = Clinic::where('status', 1)->orderBy('name', 'asc')->get();
            return view('register_doctor.availability',$data);
        }
    }

    public function onlineConsultation(Request $request, $id){
        if($request->isMethod('post')){
            if(isset($request->consultation_fee) &&
            isset($request->consultation_duration) &&
            isset($request->day) &&
            count($request->day) > 0 &&
            isset($request->start_time) &&
            count($request->start_time) > 0 &&
            isset($request->end_time) &&
            count($request->end_time) > 0 ) {
                $doctor_clinic_ids = DoctorClinic::where('doctor_id', $id)->where('clinic_id', 0)->pluck('id')->toArray();
                DoctorClinic::where('doctor_id', $id)->where('clinic_id', 0)->delete();
                ClinicTiming::whereIn('doctor_clinic_id', $doctor_clinic_ids)->delete();
                $DoctorClinic = [
                    'doctor_id' => $id,
                    'clinic_id' => 0, // clinic id zero means online clinic
                    'consultation_fee' => $request->consultation_fee,
                    'consultation_duration' => $request->consultation_duration,
                ];
                $created_doctor_clinic = DoctorClinic::create($DoctorClinic);
                foreach($request->day as $key => $day){
                    $periods = CarbonPeriod::create($request->start_time[0], $request->consultation_duration . " minutes", $request->end_time[0]);
                    if(count($periods)){
                        foreach($periods as $periodKey => $periodValue){
                            if(count($periods)-1 != $periodKey){
                                $ClinicTiming = [
                                    'doctor_clinic_id' => $created_doctor_clinic->id,
                                    'day' => $day,
                                    'start_time' => $periodValue->format('H:i:s'),
                                    'end_time' => $periodValue->addMinutes($request->consultation_duration)->format('H:i:s'),
                                    'is_physical' => 0
                                ];
                                ClinicTiming::create($ClinicTiming);
                            }
                        }
                    }
                }
            }
            return redirect(route('register_doctor_thankyou'));
        }else{
            $data['online_clinic'] = null;
            if($id){
                $data['online_clinic'] = DoctorClinic::where('doctor_id', $id)->where('clinic_id', 0)->first();
            }
            return view('register_doctor.online_consultation',$data);
        }
    }

    public function thankyou(Request $request){
        return view('register_doctor.thankyou');
    }

    public function getServices(Request $request){
        $general_response = ['status' => true];
        $type = $request->type;
        try{
            $data['parents'] = Service::whereIn('speciality_id',$request->specialities)->where('status', true)->get();
            $general_response['html'] = view('register_doctor.parents_dropdown',$data)->render();
            return $general_response;
        }catch(\Exception $e){
            return [
                'status' => false,
                'message' => 'Something went wrong'
            ];
        }
    }

    public function profileProgress(Request $request)
    {
        try {
            if(Request()->segment(2) === "v2"){
                $user = \Auth::user() ?? \Auth::guard("api")->user();
                $user_id = ($user == null) ? $request->user_id : $user->id;
            }else{
                $user_id = $request->user_id ? $request->user_id : $request->header('user-id');
            }
            $profile_completion = 10;
            $aboutCompletion = 0;
            $qualificationCompletion = 0;
            $consultationCompletion = 0;
            $user = User::find($user_id);
            if($user->has('doctorDetail')){
                $consult = DoctorDetail::where('doctor_id', $user_id)->first();
                if($consult->is_physical_consultancy || $consult->is_video_consultancy){
                    $profile_completion += 30;
                }
                $profile_completion += 10;
                $aboutCompletion += 20;
            }
            if($user->has('doctorSpecialities')){
                $aboutCompletion += 10;
                $profile_completion += 10;
            }
            if($user->has('doctorBankDetails')){
                $profile_completion += 10;
                $aboutCompletion += 10;
            }
            if($user->has('doctorEducation')){
                $profile_completion += 15;
                $qualificationCompletion += 15;
            }
            if($user->has('doctorExperiences')){
                $profile_completion += 15;
                $qualificationCompletion += 15;
            }
            if($user->has('doctorClinics')){
                $consultationCompletion += 30;
            }

            $profile_verification = "20%";

            $data = [
                'profile_completion' => $profile_completion,
                'profile_verification' => $consult->is_admin_verified == 1 ? 1 : 0,
                'profile_status' => $profile_completion < 100 ? 'Incomplete' : 'Pending Verification',
                'about_completion' => $aboutCompletion,
                'qualification_completion' => $qualificationCompletion,
                'consultation_completion' => $consultationCompletion,
            ];
            return $this->returnResponse(200, '', $data);
        } catch (Exception $th) {
            return $this->returnResponse(500, $th->getMessage());
        }
    }

    public function minuteMeeting(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'meeting_date' => ['required'],
                'meeting_time' => ['required'],
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            if(Request()->segment(2) === "v2"){
                $user = \Auth::user() ?? \Auth::guard("api")->user();
                $user_id = ($user == null) ? $request->user_id : $user->id;
            }else{
                $user_id = $request->user_id ? $request->user_id : $request->header('user-id');
            }
            $data = ['meeting_date' => $request->meeting_date, 'meeting_time' => $request->meeting_time];
            DoctorDetail::where('doctor_id',$user_id)->update($data);
            return $this->returnResponse(200, '', $data);
        } catch (Exception $th) {
            return $this->returnResponse(500, $th->getMessage());
        }
    }

    public function updatePersonalInformation(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'meeting_date' => ['required'],
                'meeting_time' => ['required'],
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            if(Request()->segment(2) === "v2"){
                $user = \Auth::user() ?? \Auth::guard("api")->user();
                $user_id = ($user == null) ? $request->user_id : $user->id;
            }else{
                $user_id = $request->user_id ? $request->user_id : $request->header('user-id');
            }
            $data = ['meeting_date' => $request->meeting_date, 'meeting_time' => $request->meeting_time];
            DoctorDetail::where('doctor_id',$user_id)->update($data);
            return $this->returnResponse(200, '', $data);
        } catch (Exception $th) {
            return $this->returnResponse(500, $th->getMessage());
        }
    }

    public function practiceDetails(Request $request){
        // dd($request->all());
        if(Request()->segment(2) === "v2"){
            $user = \Auth::user() ?? \Auth::guard("api")->user();
            $user_id = ($user == null) ? $request->user_id : $user->id;
        }else{
            $user_id = $request->user_id ? $request->user_id : $request->header('user-id');
        }
        if($request->has('about')){
            $detail['about'] = $request->about;
        }
        if($request->has('pmc_no')){
            $detail['pmc_no'] = $request->pmc_no;
        }
        // DoctorDetail::where('doctor_id',$user_id)->update($detail);
        if($request->has('speciality')){
            DoctorSpeciality::where('doctor_id', $user_id)->delete();
            foreach($request->speciality as $key => $value){
                DoctorSpeciality::create([
                    'doctor_id' => $user_id,
                    'speciality_id' => $value
                ]);
            }
        }
        if($request->has('service')){
            DoctorService::where('doctor_id', $user_id)->delete();
            foreach($request->service as $key => $value){
                DoctorService::insert([
                    'doctor_id' => $user_id,
                    'service_id' => $value,
                    'speciality_id' => $request->speciality[$key],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        if($request->has('condition')){
            DoctorCondition::where('doctor_id', $user_id)->delete();
            foreach($request->condition as $key => $value){
                if(is_iterable($value)){
                    foreach($value as $k => $v){
                        DoctorCondition::insert([
                            'doctor_id' => $user_id,
                            'disease_id' => $v,
                            'speciality_id' => $request->speciality[$key],
                            'service_id' => $request->service[$key],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
                // if($key == 'others' && is_iterable($value)){
                //     foreach($value as $k => $v){
                //         $new_condition = Disease::create([
                //             'lang_id' => 1,
                //             'name' => $v['name'],
                //             'slug' => strtolower(str_replace(' ', '-', $v['name'])),
                //             'description' => $v['name'],
                //             'status' => 1,
                //             'speciality_id' => $v['speciality_id']
                //         ]);
                //             DoctorCondition::create([
                //             'doctor_id' => $user_id,
                //             'disease_id' => $new_condition->id,
                //         ]);
                //     }
                // }
                else{
                    DoctorCondition::insert([
                        'doctor_id' => $user_id,
                        'disease_id' => $value,
                        'speciality_id' => $request->speciality[$key],
                        'service_id' => $request->service[$key],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
        return $this->returnResponse(200, 'Practice Detail Updated Successfully', null);
    }

    public function getCertifications()
    {
        $certifications = Certification::where('status', 1)->get();
        return $this->returnResponse(200, '', $certifications);
    }
    public function getInstitute(Request $request){
        try{
            $institute=Institute::get();
            return $this->returnResponse(200, '', $institute);
        }
        catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function getDesignation(Request $request){
        try{
            $designation=Designation::get();
            return $this->returnResponse(200, '', $designation);
        }
        catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
}
