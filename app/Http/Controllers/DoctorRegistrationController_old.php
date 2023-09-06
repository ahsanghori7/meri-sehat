<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Speciality;
use App\Models\Service;
use App\Models\DoctorService;
use App\Models\DoctorSpeciality;
use App\Models\DoctorEducation;
use App\Models\DoctorExperience;
use App\Models\User;
use App\Models\DoctorDetail;

class DoctorRegistrationOldController extends Controller
{
    public function register(Request $request){
        if($request->isMethod('post'))
        {
            // return $request->birth_date;
            $user = new User;
            $user->role_id= '3';
            $user->name= $request->name;
            $user->phone= $request->phone;
            $user->email= $request->email;
            $user->gender= $request->gender;
            $user->birth_date= $request->birth_date;
            $user->status= 0;
            $user->is_blocked= 1;

            $user->save();
            if($user)
            {
                $doctor_detail = new DoctorDetail;
                $doctor_detail->doctor_id = $user->id;
                $doctor_detail->prefix = 'Prefix';
                $doctor_detail->experience_year = '0';
                $doctor_detail->pmc_no = $request->pmc_no;
                $doctor_detail->about = $request->about;
                $doctor_detail->save();
            }
            $request->session()->put('doctor_detail', $request->all());
            return redirect()->route('register_doctor_education_public_url',['id' => $user->id]);
        }else
        {
            $data = null;
            if(session('doctor_detail')){
                $data = session('doctor_detail');
            }
            return view('register_doctor.form',['data'=>$data]);
        }
    }

    public function edit_register(Request $request,$id){
        if($request->isMethod('post'))
        {
            // return $request->birth_date;
            $user =User::where('id',$id)->first();
            $user->name= $request->name;
            $user->phone= $request->phone;
            $user->email= $request->email;
            $user->gender= $request->gender;
            $user->birth_date= $request->birth_date;
            $user->save();
            if($user)
            {
                $doctor_detail = DoctorDetail::where('doctor_id',$id)->first();
                $doctor_detail->prefix = 'Prefix';
                $doctor_detail->experience_year = '0';
                $doctor_detail->pmc_no = $request->pmc_no;
                $doctor_detail->about = $request->about;
                $doctor_detail->save();
            }
            $request->session()->put('doctor_detail', $request->all());
            return redirect()->route('register_doctor_education_public_url',['id' => $user->id]);
        }else
        {
            $data = null;
            if(session('doctor_detail')){
                $data = session('doctor_detail');
            }
            return view('register_doctor.form',['data'=>$data,'id'=>$id]);
        }
    }
    public function registerEducation(Request $request,$id){
        if($request->isMethod('post'))
        {
            $delete_education = DoctorEducation::where('doctor_id',$id)->delete();
            foreach($request->degree as $key=>$item)
            {
                $education = new DoctorEducation;
                $education->doctor_id = $id;
                $education->degree = $item;
                $education->institute = $request->institute[$key];
                $education->year_of_completion = $request->year[$key];
                $education->save();
            }

            $request->session()->put('doctor_education', $request->all());
            return redirect()->route('register_doctor_experience_public_url',['id'=>$id]);
        }else{
            $data = null;
            $education = DoctorEducation::where('doctor_id',$id)->get();
            if(session('doctor_education')){
                $data = session('doctor_education');
            }
            return view('register_doctor.doctor_Education',['data'=>$data,'id'=>$id,'education'=>$education]);
        }
    }
    public function registerExperience(Request $request,$id){
        if($request->isMethod('post'))
        {
            // return $request->all();
            $delete_education = DoctorExperience::where('doctor_id',$id)->delete();
            foreach($request->position as $key=>$item)
            {
                $education = new DoctorExperience;
                $education->doctor_id = $id;
                $education->position = $item;
                $education->institute = $request->institute[$key];
                $education->year_of_completion = $request->year_of_completion[$key];
                $education->years = $request->years[$key];
                $education->save();
            }

            $request->session()->put('doctor_education', $request->all());
            return redirect()->route('register_doctor_location_public_url',['id'=>$id]);
        }else{
            $data = null;
            $experience = DoctorExperience::where('doctor_id',$id)->get();
            // return $experience;
            if(session('doctor_experience')){
                $data = session('doctor_experience');
            }
            return view('register_doctor.experince',['data'=>$data,'id'=>$id,'experience'=>$experience]);
        }
    }
    public function registerLocation(Request $request,$id)
    {
        // return 'ajax';
        if($request->isMethod('post'))
        {
            // return $request->all();

            $request->session()->put('doctor_location', $request->all());
            $delete = DoctorSpeciality::where('doctor_id',$id)->delete();
            $delete = DoctorService::where('doctor_id',$id)->delete();

            foreach($request->spaciality as $key=>$item)
            {
                $doctor_speciality = new DoctorSpeciality;
                $doctor_speciality->doctor_id = $id;;
                $doctor_speciality->speciality_id=$item;
                $doctor_speciality->save();
            }
            if($request->services){
                foreach($request->services as $key=>$item)
                {
                    $doctor_service = new DoctorService;
                    $doctor_service->doctor_id = $id;
                    $doctor_service->service_id =$item;
                    $doctor_service->save();
                }
            }else{
                return back()->with('error','Please select services');
            }
            $doctor_detail = DoctorDetail::where('doctor_id', $id)->first();
            $doctor_detail->experience_year	= $request->expirence;
            $doctor_detail->collaborations	= $request->collaborations;
            $doctor_detail->is_physical_consultancy	= $request->physical_appointment;
            $doctor_detail->is_video_consultancy	= $request->video_consultation;
            $doctor_detail->is_voice_consultancy	= $request->voice_call_consultation;
            $doctor_detail->save();
            $doctor['detail'] = session('doctor_detail');
            $doctor['education'] = session('doctor_education');
            $doctor['location'] = session('doctor_location');

            $request->session()->forget('doctor_detail');
            $request->session()->forget('doctor_education');
            $request->session()->forget('doctor_location');

            return view('register_doctor.thankyou');
        }else
        {

            $data = null;
            $service = null;
            // return session('doctor_location')['spaciality'];
            // $request->session()->forget('doctor_location');
            if(session('doctor_location'))
            {
                $data = session('doctor_location');
                $service = null;
                if(isset(session('doctor_location')['services']))
                {
                    $serv = session('doctor_location')['services'];
                    $service = Service::whereIn('speciality_id',$serv)->get();
                }

            }
            $speciality = Speciality::get();
            return view('register_doctor.location',['data'=>$data,'speciality' => $speciality,'service'=>$service,'id'=>$id]);
        }
    }
    public function services(Request $request)
    {
        if(count($request->id)>0)
        {
            $service = Service::whereIn('speciality_id',$request->id)->get();
            return $service;
        }
        return [];
    }
}
