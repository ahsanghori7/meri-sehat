<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\{Article,
    Clinic,
    ClinicTiming,
    DoctorClinic,
    FitnessDetail,
    User as MainModel,
    DoctorDetail,
    Review,
    FitnessService,
    Service,
    FitnessEducation,
    FitnessExperience,
    FitnessSpeciality,
    Speciality,
    Degree,
    University,
    City,
    WidgetMedia};
use App\Http\Common\{Helper, EmailHelper};
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class FitnessExpertsController extends Controller
{
    public $folder_name = 'fitness-experts'; // For view routes and file calling and saving
    public $module_name = 'Wellness Experts'; // For toast And page header

    public function view(Request $request){
        if (Gate::denies('fitness-management-view')) {
            abort(403);
        }
        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
        $data['result'] = MainModel::where('role_id', $this->getConstantByValue('FITNESS_EXPERTS_ROLE_ID'))->get();
        $data['specialities'] =  Speciality::where('lang_id', 1)->where('type','wellness-experts')->where('status','1')->get();
        $data['locations'] =  City::where('lang_id', 1)->where('status','1')->get();
        $file = "admin.".$this->folder_name.".view";
        return view($file,$data);
    }

    public function viewProfile(Request $request,$id){

        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
        $data['cities'] = City::where(['status' => true, 'lang_id' => 1])->get();
        $data['result'] = FitnessDetail::where('fitness_experts_id',$id)->first();
        $result = $data['result'];
        $data['fitness_name'] = $result && $result->prefix ? $result->prefix.' '.$result->user->name : $result->user->name;
        $data['fitness_link'] = '';
        if (isset($result->user->FitnessSpecialityDetails[0])) {
            $data['fitness_link'] = str_replace(' ','-', strtolower(env('APP_URL').'wellness-experts/'.$result->user->city->name.'/'.$result->user->FitnessSpecialityDetails[0]->name.'/'.($result->prefix ? $result->prefix.'-'.$result->user->name : $result->user->name).'/'.$result->user->id));
        }
        $data['include_status_radio'] = 1;
        $data['include_is_mobile_show_radio'] = 1;
        $data['include_is_web_show_radio'] = 1;
        $file = "admin.".$this->folder_name.".profile_new";
        return view($file,$data);
    }

    public function viewServices(Request $request,$id){
        if (Gate::denies('fitness-management-service')) {
            abort(403);
        }
        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
        $data['services'] = Service::all();
        $data['result'] = FitnessDetail::where('fitness_experts_id',$id)->first();
        $result = $data['result'];
        $data['fitness_link'] = '';
        if (isset($result->user->FitnessSpecialityDetails[0])) {
            $data['fitness_link'] = str_replace(' ','-', strtolower(env('APP_URL').'wellness-experts/'.$result->user->city->name.'/'.$result->user->FitnessSpecialityDetails[0]->name.'/'.($result->prefix ? $result->prefix.'-'.$result->user->name : $result->user->name).'/'.$result->user->id));
        }
        $file = "admin.".$this->folder_name.".services";
//        dd($data);
        return view($file,$data);
    }

    public function viewSpecialities(Request $request,$id){
        if (Gate::denies('fitness-management-specialties')) {
            abort(403);
        }
        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
        $data['specialities'] = Speciality::where('type','wellness-experts')->get();
        $data['result'] = FitnessDetail::where('fitness_experts_id',$id)->first();
        $result = $data['result'];
        $data['fitness_link'] = '';
        if (isset($result->user->fitnessSpecialityDetails[0])) {
            $data['fitness_link'] = str_replace(' ','-', strtolower(env('APP_URL').'wellness-experts/'.$result->user->city->name.'/'.$result->user->FitnessSpecialityDetails[0]->name.'/'.($result->prefix ? $result->prefix.'-'.$result->user->name : $result->user->name).'/'.$result->user->id));
        }
        $file = "admin.".$this->folder_name.".specialities";
        return view($file,$data);
    }

    public function viewEducations(Request $request,$id){
        if (Gate::denies('fitness-management-education')) {
            abort(403);
        }
        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
        $data['degrees'] = Degree::all();
        $data['universities'] = University::all();
        $data['result'] = FitnessDetail::where('fitness_experts_id',$id)->first();
        $result = $data['result'];
        $data['fitness_link'] = '';
        if (isset($result->user->FitnessSpecialityDetails[0])) {
            $data['fitness_link'] = str_replace(' ','-', strtolower(env('APP_URL').'wellness-experts/'.$result->user->city->name.'/'.$result->user->FitnessSpecialityDetails[0]->name.'/'.($result->prefix ? $result->prefix.'-'.$result->user->name : $result->user->name).'/'.$result->user->id));
        }
        $file = "admin.".$this->folder_name.".educations";
        return view($file,$data);
    }

    public function viewExperiences(Request $request,$id){
        if (Gate::denies('fitness-management-experience')) {
            abort(403);
        }
        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
        $positions = [
            "Professor",
            "Assistant Professor",
            "Associate Professor",
            "Senior Registrar",
            "Consultant",
            "HO",
        ];
        $data['positions'] = $positions;
        $data['result'] = FitnessDetail::where('fitness_experts_id',$id)->first();
        $result = $data['result'];
        $data['fitness_link'] = '';
        if (isset($result->user->FitnessSpecialityDetails[0])) {
            $data['fitness_link'] = str_replace(' ','-', strtolower(env('APP_URL').'wellness-experts/'.$result->user->city->name.'/'.$result->user->FitnessSpecialityDetails[0]->name.'/'.($result->prefix ? $result->prefix.'-'.$result->user->name : $result->user->name).'/'.$result->user->id));
        }
        $file = "admin.".$this->folder_name.".experiences";
        return view($file,$data);
    }

    public function viewArticles(Request $request,$id){
        if (Gate::denies('fitness-management-assign-articles')) {
            abort(403);
        }
        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
        $data['articles'] = Article::where('approved_by', $id)->get();
        $data['assign_articles'] = Article::where('approved_by', null)->get();
        $data['result'] = FitnessDetail::where('fitness_experts_id',$id)->first();
        $result = $data['result'];
        $data['fitness_link'] = '';
        if (isset($result->user->FitnessSpecialityDetails[0])) {
            $data['fitness_link'] = str_replace(' ','-', strtolower(env('APP_URL').'wellness-experts/'.$result->user->city->name.'/'.$result->user->FitnessSpecialityDetails[0]->name.'/'.($result->prefix ? $result->prefix.'-'.$result->user->name : $result->user->name).'/'.$result->user->id));
        }
        $file = "admin.".$this->folder_name.".articles";
        return view($file,$data);
    }

    public function viewVideos(Request $request,$id){
        if (Gate::denies('fitness-management-assign-videos')) {
            abort(403);
        }
        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
//        $widget_video = new WidgetMedia();
//        $widget_video = $widget_video->whereHas('referenceWidget', function($widget_video) use ($id) {
//            $widget_video = $widget_video->whereHas('article', function($widget_video) use ($id) {
//                $widget_video = $widget_video
//                    ->where('approved_by', $id);
//            });
//        })->get();
//        $widget_unassign_video = new WidgetMedia();
//        $widget_unassign_video = $widget_unassign_video->whereHas('referenceWidget', function($widget_unassign_video) {
//            $widget_unassign_video = $widget_unassign_video->whereHas('article', function($widget_unassign_video) {
//                $widget_unassign_video = $widget_unassign_video
//                    ->where('approved_by', null);
//            });
//        })->get();

        $data['videos'] = WidgetMedia::where('type', 'video')->where('approved_by', $id)->get();
        $data['assign_videos'] = WidgetMedia::with('referenceWidget')->where('type', 'video')->where('approved_by', null)->get();
        $data['result'] = FitnessDetail::where('fitness_experts_id',$id)->first();
        $result = $data['result'];
        $data['fitness_link'] = '';
        if (isset($result->user->FitnessSpecialityDetails[0])) {
            $data['fitness_link'] = str_replace(' ','-', strtolower(env('APP_URL').'wellness-experts/'.$result->user->city->name.'/'.$result->user->FitnessSpecialityDetails[0]->name.'/'.($result->prefix ? $result->prefix.'-'.$result->user->name : $result->user->name).'/'.$result->user->id));
        }
        $file = "admin.".$this->folder_name.".videos";
        return view($file,$data);
    }


    public function delete(Request $request){
        return true;
    }

    public function fetch(Request $request){
        $query = MainModel::where('role_id', MainModel::FITNESS_EXPERTS_ROLE)
        ->with(['FitnessSpecialityDetails','city']);

        //$query = MainModel::with(['parent','language'])->get();
        if(isset($request->name)) {
            $query = $query->where('name', 'like', '%'.$request->name.'%');
        }
        if(isset($request->email)) {
            $query = $query->where('email', 'like', '%'.$request->email.'%');
        }
        if(isset($request->speciality)) {
            $request_speciality = $request->speciality;
            $query = $query->whereHas('fitnessSpecialities', function ($query) use ($request_speciality) {
                $query->where('fitness_experts_specialities.speciality_id', $request_speciality);
            });
        }

        if(isset($request->location)) {
            $request_location = $request->location;
            $query = $query->whereHas('city', function ($query) use ($request_location) {
                $query->where('cities.id', $request_location);
            });
        }
        if(isset($request->status)) {
            $query = $query->where('status', $request->status);
        }
        return Datatables::of($query)->make(true);
    }

    public function form(Request $request, $id = null){
        $request->validate(FitnessDetail::getValidationRules($id));
        $unique_code = Helper::generateUniqueCode();
        if (Gate::denies('fitness-management-add') || Gate::denies('fitness-management-update')) {
            abort(403);
        }
        // $data = [
        //     'prefix' => $request->prefix,
        //     'experience_year' => $request->experience_year,
        //     'about' => $request->about,
        //     'title' => $request->title,
        //     'description' => $request->description,
        //     'status' => $request->profile_status ?? 0,
        //     'is_featured' => $request->is_featured ?? 0,
        //     'social_facebook' => $request->social_facebook,
        //     'social_twitter' => $request->social_twitter,
        //     'social_youtube' => $request->social_youtube,
        //     'social_instagram' => $request->social_instagram,
        //     'social_linkedin' => $request->social_linkedin,
        // ];
        if ($request->has('prefix')) {
            $data['prefix']=$request->prefix;
        }if ($request->has('experience_year')) {
            $data['experience_year']=$request->experience_year;
        }if ($request->has('about')) {
            $data['about']=$request->about;
        }if ($request->has('title')) {
            $data['title']=$request->title;
        }if ($request->has('description')) {
            $data['description']=$request->description;
        }if ($request->has('status')) {
            $data['status']=$request->status;
        }if ($request->has('is_featured')) {
            $data['is_featured']=$request->is_featured;
        }if ($request->has('social_facebook')) {
            $data['social_facebook']=$request->social_facebook;
        }if ($request->has('social_twitter')) {
            $data['social_twitter']=$request->social_twitter;
        }if ($request->has('social_youtube')) {
            $data['social_youtube']=$request->social_youtube;
        }if ($request->has('social_instagram')) {
            $data['social_instagram']=$request->social_instagram;
        }if ($request->has('social_linkedin')) {
            $data['social_linkedin']=$request->social_linkedin;
        }

        $password = Str::random(10);

        $user_data = [
            'role_id' => $this->getConstantByValue('FITNESS_EXPERTS_ROLE_ID'),
            'password' => Hash::make($password)
        ];
        if ($request->has('is_phone_verified')) {
            $user_data['is_phone_verified']=$request->is_phone_verified;
        }if ($request->has('is_blocked')) {
            $user_data['is_blocked']=$request->is_blocked;
        }if ($request->has('status')) {
            $user_data['status']=$request->status;
        }if ($request->has('name')) {
            $user_data['name']=$request->name;
        }if ($request->has('phone')) {
            $user_data['phone']=$request->phone;
        }if ($request->has('email')) {
            $user_data['email']=$request->email;
        }if ($request->has('gender')) {
            $user_data['gender']=$request->gender;
        }if ($request->has('city_id')) {
            $user_data['city_id']=$request->city_id;
        }






        if($request->hasFile('image')){
            $folder="fitness-experts";
            $key="image";
            $user_data['image'] = $this->S3UploaderSpecificKey($key,$request,$folder);
        }

        if($id){
            if(FitnessDetail::where('fitness_experts_id', $id)->update($data)){
                $get_user_data = MainModel::find($id);
                $user_update_data = $get_user_data->update($user_data);
                Helper::toast('success',$this->module_name.' Updated.');
            }
        }else{
            if($request->status && !$request->is_login_credentials_sent){
//            $this->sendDefaultDoctorCredentials($request->user_id);
                $data['is_login_credentials_sent'] = 1;
            }
            if($created_data = MainModel::create($user_data)){
                $data['fitness_experts_id'] = $created_data->id;
                FitnessDetail::create($data);
                if ($request->status == 1) {
                    // SEND CREDENTIAL EMAIL
                    $template_path = 'email_templates.fitness_login_credentials';
                    $template_data = [
                        "email" => $created_data->email,
                        "password" => $password,
                    ];
                    $to_email = $created_data->email;
                    $subject = "Welcome to MeriSehat";
//                    EmailHelper::sendMail($template_path, $template_data, $to_email, $subject);
                }
                Helper::toast('success',$this->module_name.' created.');
                return redirect()->route('fitness-experts-edit', ['id' => encrypt($created_data->id)]);
            }
        }
        // return redirect()->route($this->folder_name.'-view');
        return redirect()->back();

    }

     public function add(Request $request){

         if (Gate::denies('fitness-management-add')) {
             abort(403);
         }
         if($request->isMethod('post')){
             return $this->form($request);
         }else{
             $data['page_header'] = "Add ".$this->module_name;
             $data['result'] = null;
             $data['specialities'] = Speciality::where('type','wellness-experts')->get();
             $data['cities'] = City::all();
             $data['fitness_name'] = null;
             $data['fitness_link'] = null;
             $data['include_status_radio'] = 1;
             $data['include_is_mobile_show_radio'] = 1;
             $data['include_is_web_show_radio'] = 1;
             $file = "admin.".$this->folder_name.".add";

             return view($file,$data);
         }
     }

    public function edit(Request $request, $id){

        if (Gate::denies('fitness-management-update')) {
            abort(403);
        }
        $id = decrypt($id);
        if($request->isMethod('post')){
            return $this->form($request,$id);
        }else{
            $data['page_header'] = "Edit ".$this->module_name;
            $data['cities'] = City::where(['status' => true, 'lang_id' => 1])->get();
            $data['result'] = FitnessDetail::where('fitness_experts_id',$id)->first();
            $result = $data['result'];
            $data['fitness_name'] = $result && $result->prefix ? $result->prefix.' '.$result->user->name : $result->user->name;
            $data['fitness_link'] = '';
            if (isset($result->user->FitnessSpecialityDetails[0])) {
                $data['fitness_link'] = str_replace(' ','-', strtolower(env('APP_URL').'wellness-experts/'.$result->user->city->name.'/'.$result->user->FitnessSpecialityDetails[0]->name.'/'.($result->prefix ? $result->prefix.'-'.$result->user->name : $result->user->name).'/'.$result->user->id));
            }
            $data['include_status_radio'] = 1;
            $data['include_is_mobile_show_radio'] = 1;
            $data['include_is_web_show_radio'] = 1;
            $file = "admin.".$this->folder_name.".form";
            return view($file,$data);
        }
    }

    public function resetDoctorPassword(MainModel $user){
        $password = Helper::getRandomPassword();
        $hashed_password = \Hash::make($password);
        $user->password = $hashed_password;
        $user->save();
        return [
            'status' => true,
            'password' => $password
        ];
    }

    public function sendDefaultDoctorCredentials($user_id){
        $user = MainModel::find($user_id);
        $password = $this->resetDoctorPassword($user)['password'];

        $template_path = 'email_templates.fitness_login_credentials';
        $template_data = [
            "email" => $user->email,
            "password" => $password,
        ];
        $to_email = $user->email;
        $subject = "Welcome to MeriSehat";
        return true;
        return EmailHelper::sendMail($template_path, $template_data, $to_email, $subject);
    }

    public function updateServices(Request $request, $fitness_experts_id){
        if($request->isMethod('post')){
//            FitnessService::where('fitness_experts_id',$fitness_experts_id)->delete();
            if($request->service && count($request->service) > 0){
                foreach($request->service as $service){
                    FitnessService::where('fitness_experts_id',$fitness_experts_id)->create([
                        'fitness_experts_id' => $fitness_experts_id,
                        'service_id' => $service,
                    ]);
                }
            }
            Helper::toast('success','Wellness Experts Services Updated.');
            return back();
        }else{
            $data['services'] = Service::where('status', '1')->get();
            $data['result'] = FitnessService::where('fitness_experts_id',$fitness_experts_id)->get();
            $file = "admin.".$this->folder_name.".edit_services";
            return view($file,$data);
        }
    }

    public function editServices(Request $request, $service_id){
        if($request->isMethod('post')){
            if($request->has('service')){
                FitnessService::where('id',$service_id)->update([
                    'service_id' => $request->service,
                ]);
            }
            Helper::toast('success','Wellness Experts Services Updated.');
            return back();
        }else{
            $data['services'] = Service::where('status', '1')->get();
            $data['result'] = FitnessService::where('id',$service_id)->get();
            return $data;
        }
    }

    public function deleteServices(Request $request, $fitness_experts_id){
        FitnessService::where('fitness_experts_id',$fitness_experts_id)->where('id', $request->recordId)->delete();
        Helper::toast('success','Wellness Experts Services Deleted.');
        return back();
    }


    public function updateSpecialities(Request $request, $fitness_experts_id){
        if($request->isMethod('post')){
//            FitnessSpeciality::where('fitness_experts_id',$fitness_experts_id)->delete();
            if($request->specialities && count($request->specialities) > 0){
                foreach($request->specialities as $speciality){
                    FitnessSpeciality::where('fitness_experts_id',$fitness_experts_id)->create([
                        'fitness_experts_id' => $fitness_experts_id,
                        'speciality_id' => $speciality,
                    ]);
                }
            }
            Helper::toast('success','Wellness Experts Specialities Updated.');
            return back();
        }else{
            $data['specialities'] = Speciality::where('type','wellness-experts')->where('status', '1')->get();
            $data['result'] = FitnessSpeciality::where('fitness_experts_id',$fitness_experts_id)->get();
            $file = "admin.".$this->folder_name.".edit_specialities";
            return view($file,$data);
        }
    }

    public function editSpecialities(Request $request, $speciality_id){
        if($request->isMethod('post')){
            if($request->has('speciality')){
                FitnessSpeciality::where('id',$speciality_id)->update([
                    'speciality_id' => $request->speciality,
                ]);
            }
            Helper::toast('success','Wellness Experts Specialities Updated.');
            return back();
        }else{
            $data['specialities'] = Speciality::where('type','wellness-experts')->where('status', '1')->get();
            $data['result'] = FitnessSpeciality::where('id',$speciality_id)->get();
            return $data;
        }
    }

    public function deleteSpecialities(Request $request, $fitness_experts_id){
        FitnessSpeciality::where('fitness_experts_id',$fitness_experts_id)->where('id', $request->recordId)->delete();
        Helper::toast('success','Wellness Experts Specialities Deleted.');
        return back();
    }

    public function updateExperiences(Request $request, $fitness_experts_id){
        if($request->isMethod('post')){
//            FitnessExperience::where('fitness_experts_id',$fitness_experts_id)->delete();
            if($request->position && count($request->position) > 0){
                foreach($request->position as $key => $position){
                    $data = [
                        'fitness_experts_id' => $fitness_experts_id,
                        'position' => $position,
                        'institute' => $request->institute[$key],
                        'start_year' => $request->start_year[$key],
                        'is_completed' => 0,
                        'end_year' => null,
                    ];
                    if(isset($request->year_of_completion[$key])){
                        $data['end_year'] = $request->year_of_completion[$key];
                        $data['is_completed'] = 1;
                    }
                    FitnessExperience::where('fitness_experts_id',$fitness_experts_id)->create($data);
                }
            }
            Helper::toast('success','Wellness Experts Experiences Updated.');
            return back();
        }else{
            $positions = [
                "Professor",
                "Assistant Professor",
                "Associate Professor",
                "Senior Registrar",
                "Consultant",
                "HO",
            ];
            $data['positions'] = $positions;
            $data['result'] = FitnessExperience::where('fitness_experts_id',$fitness_experts_id)->get();
            $file = "admin.".$this->folder_name.".edit_experiences";
            return view($file,$data);
        }
    }

    public function editExperiences(Request $request, $experience_id){
        if($request->isMethod('post')){
            if($request->has('position')){
                $data = [
                    'position' => $request->position,
                    'institute' => $request->institute,
                    'start_year' => $request->start_year,
                    'is_completed' => 0,
                    'end_year' => null,
                ];
                if(isset($request->year_of_completion)){
                    $data['end_year'] = $request->year_of_completion;
                    $data['is_completed'] = 1;
                }
                FitnessExperience::where('id',$experience_id)->update($data);
            }
            Helper::toast('success','Wellness Experts Experiences Updated.');
            return back();
        }else{
            $positions = [
                "Professor",
                "Assistant Professor",
                "Associate Professor",
                "Senior Registrar",
                "Consultant",
                "HO",
            ];
            $data['positions'] = $positions;
            $data['result'] = FitnessExperience::where('id',$experience_id)->get();
            return $data;
        }
    }

    public function deleteExperiences(Request $request, $fitness_experts_id){
        FitnessExperience::where('fitness_experts_id',$fitness_experts_id)->where('id', $request->recordId)->delete();
        Helper::toast('success','Wellness Experts Experiences Deleted.');
        return back();
    }

    public function updateEducations(Request $request, $fitness_experts_id){
        if($request->isMethod('post')){
            if($request->degree && count($request->degree) > 0){
                foreach($request->degree as $key => $degree){
                    $data = [
                        'fitness_experts_id' => $fitness_experts_id,
                        'degree' => $degree,
                        'institute' => $request->university[$key],
                        'is_completed' => 0,
                        'year_of_completion' => null,
                    ];
                    if(isset($request->year_of_completion[$key])){
                        $data['year_of_completion'] = $request->year_of_completion[$key];
                        $data['is_completed'] = 1;
                    }
                    FitnessEducation::where('fitness_experts_id',$fitness_experts_id)->create($data);
                }
            }
            Helper::toast('success','Wellness Experts Educations Updated.');
            return back();
        }else{
            $data['degrees'] = Degree::where('status', '1')->orderBy('name','asc')->get();
            $data['universities'] = University::where('status', '1')->orderBy('name','asc')->get();
            $data['result'] = FitnessEducation::where('fitness_experts_id',$fitness_experts_id)->get();
            $file = "admin.".$this->folder_name.".edit_educations";
            return view($file,$data);
        }
    }

    public function editEducations(Request $request, $education_id){
        if($request->isMethod('post')){
            if($request->has('degree')){
                $data = [
                    'degree' => $request->degree,
                    'institute' => $request->university,
                    'is_completed' => 0,
                    'year_of_completion' => null,
                ];
                if(isset($request->year_of_completion)){
                    $data['year_of_completion'] = $request->year_of_completion;
                    $data['is_completed'] = 1;
                }
                FitnessEducation::where('id',$education_id)->update($data);
            }
            Helper::toast('success','Wellness Experts Educations Updated.');
            return back();
        }else{
            $data['degrees'] = Degree::where('status', '1')->orderBy('name','asc')->get();
            $data['universities'] = University::where('status', '1')->orderBy('name','asc')->get();
            $data['result'] = FitnessEducation::where('id',$education_id)->get();
            return $data;
        }
    }

    public function deleteEducations(Request $request, $fitness_experts_id){
        FitnessEducation::where('fitness_experts_id',$fitness_experts_id)->where('id', $request->recordId)->delete();
        Helper::toast('success','Wellness Experts Educations Deleted.');
        return back();
    }

    public function updateArticles(Request $request, $fitness_experts_id){
        if($request->article){
            Article::where('id',$request->article)->update([
                'approved_by' => $fitness_experts_id,
            ]);
        }
        Helper::toast('success','Article has been assigned.');
        return back();
    }

    public function deleteArticles(Request $request, $fitness_experts_id){
        if($request->recordId){
            Article::where('id',$request->recordId)->where('approved_by',$fitness_experts_id)->update([
                'approved_by' => null,
//                'status' => 0,
            ]);
        }
        Helper::toast('success','Article has been unassigned.');
        return back();
    }

    public function updateVideos(Request $request, $fitness_experts_id){
        if($request->video){
            WidgetMedia::where('id',$request->video)->update([
                'approved_by' => $fitness_experts_id,
            ]);
        }
        Helper::toast('success','Video has been assigned.');
        return back();
    }

    public function deleteVideos(Request $request, $fitness_experts_id){
        if($request->recordId){
            WidgetMedia::where('id',$request->recordId)->where('approved_by',$fitness_experts_id)->update([
                'approved_by' => null,
//                'status' => 0,
            ]);
        }
        Helper::toast('success','Video has been unassigned.');
        return back();
    }

}
