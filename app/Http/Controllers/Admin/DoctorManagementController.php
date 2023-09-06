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
    User as MainModel,
    DoctorDetail,
    Review,
    DoctorService,
    Service,
    DoctorEducation,
    DoctorExperience,
    DoctorSpeciality,
    Speciality,
    Degree,
    University,
    City,
    Disease,
    DoctorBankDetail,
    DoctorCondition,
    DoctorVerification,
    WidgetMedia};
use App\Http\Common\{DoctorVerificationHelper, Helper, EmailHelper};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

use function PHPUnit\Framework\isEmpty;

class DoctorManagementController extends Controller
{
    public $folder_name = 'doctor'; // For view routes and file calling and saving
    public $module_name = 'Doctors'; // For toast And page header

    public function view(Request $request){
        if (Gate::denies('doctor-management-view')) {
            abort(403);
        }
        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
        $data['result'] = MainModel::where('role_id', $this->getConstantByValue('DOCTOR_ROLE_ID'))->get();
        $data['specialities'] =  Speciality::where('lang_id', 1)->where('status','1')->get();
        $data['locations'] =  City::where('lang_id', 1)->where('status','1')->get();
        $file = "admin.".$this->folder_name.".view";
        //Modal Add Doctor Fields
        $data['page_header'] = "Add ".$this->module_name;
        $data['add_result'] = null;
        $data['add_specialities'] = Speciality::all();
        $data['add_cities'] = City::all();
        $data['doctor_name'] = null;
        $data['doctor_link'] = null;
        return view($file,$data);
    }

    public function viewProfile(Request $request,$id){

        if (Gate::denies('doctor-management-update')) {
            abort(403);
        }
        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
        $data['cities'] = City::where(['status' => true, 'lang_id' => 1])->get();
        $data['result'] = DoctorDetail::where('doctor_id',$id)->first();
        $result = $data['result'];
        $data['doctor_name'] = $result && $result->prefix ? $result->prefix.' '.$result->user->name : $result->user->name;
        $data['doctor_link'] = '';
        if (isset($result->user->doctorSpecialityDetails[0])) {
            $data['doctor_link'] = str_replace(' ','-', strtolower(str_replace('api','',env('APP_URL')).'doctor/'.$result->user->city->name.'/'.$result->user->doctorSpecialityDetails[0]->name.'/'.($result->prefix ? $result->prefix.'-'.$result->user->name : $result->user->name).'/'.$result->user->id));
        }
        $data['include_status_radio'] = 1;
        $data['include_is_mobile_show_radio'] = 1;
        $data['include_is_web_show_radio'] = 1;
        $file = "admin.".$this->folder_name.".profile_new";
        return view($file,$data);
    }

    public function viewShifts(Request $request,$id){
        if (Gate::denies('doctor-management-shift')) {
            abort(403);
        }

        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
        $data['clinics'] = Clinic::where('status', 1)->get();
        $days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
        $data['days'] = $days;
        $data['result'] = DoctorDetail::where('doctor_id',$id)->first();
        $result = $data['result'];
        $data['doctor_link'] = '';
        if (isset($result->user->doctorSpecialityDetails[0])) {
            $data['doctor_link'] = str_replace(' ','-', strtolower(str_replace('api','',env('APP_URL')).'doctor/'.$result->user->city->name.'/'.$result->user->doctorSpecialityDetails[0]->name.'/'.($result->prefix ? $result->prefix.'-'.$result->user->name : $result->user->name).'/'.$result->user->id));
        }
        $file = "admin.".$this->folder_name.".shifts";
        return view($file,$data);
    }

    public function viewServices(Request $request,$id){
        if (Gate::denies('doctor-management-service')) {
            abort(403);
        }

        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
        $data['services'] = Service::all();
        $data['result'] = DoctorDetail::where('doctor_id',$id)->first();
        $result = $data['result'];
        $data['doctor_link'] = '';
        if (isset($result->user->doctorSpecialityDetails[0])) {
            $data['doctor_link'] = str_replace(' ','-', strtolower(str_replace('api','',env('APP_URL')).'doctor/'.$result->user->city->name.'/'.$result->user->doctorSpecialityDetails[0]->name.'/'.($result->prefix ? $result->prefix.'-'.$result->user->name : $result->user->name).'/'.$result->user->id));
        }
        $file = "admin.".$this->folder_name.".services";
//        dd($data);
        return view($file,$data);
    }

    public function viewSpecialities(Request $request,$id){
        if (Gate::denies('doctor-management-specialties')) {
            abort(403);
        }

        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
        $data['specialities'] = Speciality::all();
        $data['result'] = DoctorDetail::where('doctor_id',$id)->first();
        $result = $data['result'];
        $data['doctor_link'] = '';
        if (isset($result->user->doctorSpecialityDetails[0])) {
            $data['doctor_link'] = str_replace(' ','-', strtolower(str_replace('api','',env('APP_URL')).'doctor/'.$result->user->city->name.'/'.$result->user->doctorSpecialityDetails[0]->name.'/'.($result->prefix ? $result->prefix.'-'.$result->user->name : $result->user->name).'/'.$result->user->id));
        }
        $file = "admin.".$this->folder_name.".specialities";
        return view($file,$data);
    }

    public function viewEducations(Request $request,$id){
        if (Gate::denies('doctor-management-education')) {
            abort(403);
        }
        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
        $data['degrees'] = Degree::all();
        $data['universities'] = University::all();
        $data['result'] = DoctorDetail::where('doctor_id',$id)->first();
        $result = $data['result'];
        $data['doctor_link'] = '';
        if (isset($result->user->doctorSpecialityDetails[0])) {
            $data['doctor_link'] = str_replace(' ','-', strtolower(str_replace('api','',env('APP_URL')).'doctor/'.$result->user->city->name.'/'.$result->user->doctorSpecialityDetails[0]->name.'/'.($result->prefix ? $result->prefix.'-'.$result->user->name : $result->user->name).'/'.$result->user->id));
        }
        $file = "admin.".$this->folder_name.".educations";
        return view($file,$data);
    }

    public function viewExperiences(Request $request,$id){
        if (Gate::denies('doctor-management-experience')) {
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
        $data['result'] = DoctorDetail::where('doctor_id',$id)->first();
        $result = $data['result'];
        $data['doctor_link'] = '';
        if (isset($result->user->doctorSpecialityDetails[0])) {
            $data['doctor_link'] = str_replace(' ','-', strtolower(str_replace('api','',env('APP_URL')).'doctor/'.$result->user->city->name.'/'.$result->user->doctorSpecialityDetails[0]->name.'/'.($result->prefix ? $result->prefix.'-'.$result->user->name : $result->user->name).'/'.$result->user->id));
        }
        $file = "admin.".$this->folder_name.".experiences";
        return view($file,$data);
    }

    public function viewRatings(Request $request,$id){

        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
        $data['result'] = DoctorDetail::where('doctor_id',$id)->first();
        $result = $data['result'];
        $data['doctor_link'] = '';
        if (isset($result->user->doctorSpecialityDetails[0])) {
            $data['doctor_link'] = str_replace(' ','-', strtolower(str_replace('api','',env('APP_URL')).'doctor/'.$result->user->city->name.'/'.$result->user->doctorSpecialityDetails[0]->name.'/'.($result->prefix ? $result->prefix.'-'.$result->user->name : $result->user->name).'/'.$result->user->id));
        }
        $file = "admin.".$this->folder_name.".ratings";
        return view($file,$data);
    }

    public function viewArticles(Request $request,$id){

        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
        $data['articles'] = Article::where('approved_by', $id)->get();
        $data['assign_articles'] = Article::where('approved_by', null)->get();
        $data['result'] = DoctorDetail::where('doctor_id',$id)->first();
        $result = $data['result'];
        $data['doctor_link'] = '';
        if (isset($result->user->doctorSpecialityDetails[0])) {
            $data['doctor_link'] = str_replace(' ','-', strtolower(str_replace('api','',env('APP_URL')).'doctor/'.$result->user->city->name.'/'.$result->user->doctorSpecialityDetails[0]->name.'/'.($result->prefix ? $result->prefix.'-'.$result->user->name : $result->user->name).'/'.$result->user->id));
        }
        $file = "admin.".$this->folder_name.".articles";
        return view($file,$data);
    }

    public function viewVideos(Request $request,$id){

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
        $data['result'] = DoctorDetail::where('doctor_id',$id)->first();
        $result = $data['result'];
        $data['doctor_link'] = '';
        if (isset($result->user->doctorSpecialityDetails[0])) {
            $data['doctor_link'] = str_replace(' ','-', strtolower(str_replace('api','',env('APP_URL')).'doctor/'.$result->user->city->name.'/'.$result->user->doctorSpecialityDetails[0]->name.'/'.($result->prefix ? $result->prefix.'-'.$result->user->name : $result->user->name).'/'.$result->user->id));
        }
        $file = "admin.".$this->folder_name.".videos";
        return view($file,$data);
    }

    public function postRatings(Request $request){
        $validator = Validator::make($request->all(), [
            'status' => ['required'],
            'id' => ['required'],
        ]);
        if ($validator->fails()) {
            return $this->returnResponse(400, $validator->errors()->first());
        }

        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
        $result = Review::where('id',$request->id)->first();
        if ($result) {
            $result->status = $request->status;
            $result->save();
        }
        $data['result'] = $result;
        return $this->returnResponse(200, $data);
    }


    public function delete(Request $request){
        if (Gate::denies('doctor-management-delete')) {
            abort(403);
        }
        return true;
    }

    public function fetch(Request $request){
        $query = MainModel::where('role_id', MainModel::DOCTOR_ROLE)
        ->with(['doctorSpecialityDetails','city', 'doctorDetail']);

        if(isset($request->name)) {
            $query = $query->where('name', 'like', '%'.$request->name.'%');
        }
        if(isset($request->email)) {
            $query = $query->where('email', 'like', '%'.$request->email.'%');
        }
        if(isset($request->speciality)) {
            $request_speciality = $request->speciality;
            $query = $query->whereHas('doctorSpecialities', function ($query) use ($request_speciality) {
                $query->where('doctor_specialities.speciality_id', $request_speciality);
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

        if($request->has('tab') && $request->tab != ''){
            if($request->tab == 'live'){
                $query = $query->whereHas('doctorDetail', function($query){
                    $query = $query->where('is_admin_verified', 1);
                })->where('status', 1);
            }
            else if($request->tab == 'onboarding'){
                $query = $query->where('status', 1);
                return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('doctorId', function($row){
                    return $row->id;
                })
                ->addColumn('name', function($row){
                    return $row->name;
                })
                ->addColumn('phone', function($row){
                    return $row->phone;
                })
                ->addColumn('last_updated', function($row){
                    return Carbon::parse($row->updated_at)->format('d/m/Y');
                })
                ->addColumn('about', function($row){
                    $table = 'doctor_details';
                    $columns = Schema::getColumnListing($table);
                    $dates = ['created_at', 'updated_at'];
                    $mainColumns = array_diff($columns, $dates);
                    $data = DB::table($table)->where('doctor_id', $row->id)->where(function ($query) use ($row,$mainColumns, $dates) {
                        foreach ($mainColumns as $column) {
                            $query->orWhereNull($column);
                        }
                    })->get();
                    if($data->isEmpty()){
                        return 'Pending';
                    }
                    else{
                        return '<i class="icon icon-check"></i>';
                    }
                })
                ->addColumn('qualification', function($row){
                    $table = 'doctor_educations';
                    $columns = Schema::getColumnListing($table);
                    $dates = ['created_at', 'updated_at'];
                    $mainColumns = array_diff($columns, $dates);
                    $data = DB::table($table)->where('doctor_id', $row->id)->where(function ($query) use ($row,$mainColumns, $dates) {
                        foreach ($mainColumns as $column) {
                            $query->orWhereNull($column);
                        }
                    })->get();
                    if($data->isEmpty()){
                        return 'Pending';
                    }
                    else{
                        return '<i class="icon icon-check"></i>';
                    }
                })
                ->addColumn('consultation', function($row){
                    $table = 'doctor_clinics';
                    $columns = Schema::getColumnListing($table);
                    $dates = ['created_at', 'updated_at'];
                    $mainColumns = array_diff($columns, $dates);
                    $data = DB::table($table)->where('doctor_id', $row->id)->where(function ($query) use ($row,$mainColumns, $dates) {
                        foreach ($mainColumns as $column) {
                            $query->orWhereNull($column);
                        }
                    })->get();
                    if($data->isEmpty()){
                        return 'Pending';
                    }
                    else{
                        return '<i class="icon icon-check"></i>';
                    }
                })
                ->addColumn('location', function($row){
                    return $row->city ? $row->city->name : '-';
                })
                ->addColumn('status', function($row){
                    if(isset($row->doctorDetail)){
                    $completion = $this->progress($row->doctorDetail->doctor_id)['completion_progress'];
                    }
                    if(isset($row->doctorDetail) && $row->doctorDetail->is_verified == 1){
                        return '<span class="badge p-2 text-white" style="background-color:#19B3B5;">Verified <i class="icon icon-check"></i></span>';
                    }
                    else if(isset($row->doctorDetail) && $row->doctorDetail->is_verified == 0 && $completion >= 100){
                        return '<span class="badge p-2 text-white" style="background-color:#EB8E39;">Pending Verification <i class="icon icon-check"></i></span>';
                    }
                    else{
                        return '<span class="badge p-2 badge-danger text-white">Incomplete <i class="icon icon-check"></i></span>';
                    }
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(Gate::allows('doctor-management-update')){
                        $btn .= '<a class="btn-primary btn-sm btn cursor-pointer" href="'.route('doctor-onboard-edit', ['id' => $row->e_id]).'"> <i class="icon-edit"></i></a>';
                    }
                    return $btn;
                })
                ->rawColumns(['name','phone','last_updated','about','qualification','consultation','location','status', 'action'])
                ->make(true);

            }
            else if($request->tab == 'deactivated'){
                $query = $query->where('status', 0);
            }
        }

        return Datatables::of($query)->make(true);
    }

    public function form(Request $request, $id = null){
        if (Gate::denies('doctor-management-view')) {
            abort(403);
        }
        $request->validate(DoctorDetail::getValidationRules($id));
        $unique_code = Helper::generateUniqueCode();
        if ($request->has('prefix')) {
            $data['prefix']=$request->prefix;
        }if ($request->has('experience_year')) {
            $data['experience_year']=$request->experience_year;
        }if ($request->has('pmc_no')) {
            $data['pmc_no']=$request->pmc_no;
        }if ($request->has('badge')) {
            $data['badge']=$request->badge;
        }if ($request->has('about')) {
            $data['about']=$request->about;
        }if ($request->has('cnic')) {
            $data['cnic']=$request->cnic;
        }if ($request->has('waiting_time')) {
            $data['waiting_time']=$request->waiting_time;
        }if ($request->has('is_available')) {
            $data['is_available']=$request->is_available;
        }if ($request->has('profile_status')) {
            $data['profile_status']=$request->profile_status;
        }if ($request->has('is_physical_consultancy')) {
            $data['is_physical_consultancy']=$request->is_physical_consultancy;
        }if ($request->has('is_video_consultancy')) {
            $data['is_video_consultancy']=$request->is_video_consultancy;
        }if ($request->has('is_voice_consultancy')) {
            $data['is_voice_consultancy']=$request->is_voice_consultancy;
        }if ($request->has('is_physical_consultancy')) {
            $data['is_instant_consultation']=$request->is_instant_consultation;
        }if ($request->has('is_instant_consultation')) {
            $data['is_physical_consultancy']=$request->is_physical_consultancy;
        }if ($request->has('is_featured')) {
            $data['is_featured']=$request->is_featured;
        }if ($request->has('is_verified')) {
            $data['is_verified']=$request->is_verified;
        }if ($request->has('is_admin_verified')) {
            $data['is_admin_verified']=$request->is_admin_verified;
        }
        if ($request->status == 1) {
            $password = Str::random(10);
            $user_data = [
                'role_id' => $this->getConstantByValue('DOCTOR_ROLE_ID'),
                'unique_code' => $unique_code,
                'is_subscribed' => $request->is_subscribed ?? 0,
                'is_phone_verified' => $request->is_phone_verified ?? 0,
                'is_blocked' => $request->is_blocked ?? 0,
                'status' => $request->status ?? 1,
                'name' => $request->name,
                'city_id' => $request->city,
            ];
        } else {
            $user_data = [
                'role_id' => $this->getConstantByValue('DOCTOR_ROLE_ID'),
                'unique_code' => $unique_code,
                'is_subscribed' => $request->is_subscribed ?? 0,
                'is_phone_verified' => $request->is_phone_verified ?? 0,
                'is_blocked' => $request->is_blocked ?? 0,
                'status' => $request->status ?? 1,
                'name' => $request->name,
                'city_id' => $request->city,
            ];
        }
        if($request->hasFile('image')){
            $folder="doctor";
            $key="image";
            $user_data['image'] = $this->S3UploaderSpecificKey($key,$request,$folder);
        }
        if($request->status && !$request->is_login_credentials_sent){
            $data['is_login_credentials_sent'] = 1;
        }
        if($id){
            if(DoctorDetail::where('doctor_id', $id)->update($data)){
                if($request->user_id != null){
                    $get_user_data = MainModel::where('id', $request->user_id)->first();
                    $user_update_data = $get_user_data->update($user_data);
                    if ($request->status == 1) {
                        // SEND CREDENTIAL EMAIL
                        $template_path = 'email_templates.doctor_login_credentials';
                        $template_data = [
                            "email" => $get_user_data->email,
                            "password" => $password,
                        ];
                        $to_email = $get_user_data->email;
                        $subject = "Welcome to MeriSehat";
//                        EmailHelper::sendMail($template_path, $template_data, $to_email, $subject);
                    }
                    Helper::toast('success',$this->module_name.' Updated.');
                }
            }
        }else{
            if($created_data = MainModel::create($user_data)){
                DoctorDetail::create([
                    'doctor_id' => $created_data->id,
                    'prefix' => $request->prefix,
                    'experience_year' => $request->experience_year,
                    'pmc_no' => $request->pmc_no,
                    'badge' => $request->badge,
                    'about' => $request->about,
                    'cnic' => $request->cnic,
                    'waiting_time' => $request->waiting_time,
                    'is_available' => $request->is_available ?? 0,
                    'profile_status' => $request->profile_status ?? 0,
                    'is_physical_consultancy' => $request->is_physical_consultancy ?? 0,
                    'is_video_consultancy' => $request->is_video_consultancy ?? 0,
                    'is_voice_consultancy' => $request->is_voice_consultancy ?? 0,
                    'is_instant_consultation' => $request->is_instant_consultation ?? 0,
                ]);
                DoctorVerificationHelper::insertAbout($created_data->id, 'About');
                if ($request->status == 1) {
                    // SEND CREDENTIAL EMAIL
                    $template_path = 'email_templates.doctor_login_credentials';
                    $template_data = [
                        "email" => $created_data->email,
                        "password" => $password,
                    ];
                    $to_email = $created_data->email;
                    $subject = "Welcome to MeriSehat";
//                    EmailHelper::sendMail($template_path, $template_data, $to_email, $subject);
                }
                Helper::toast('success',$this->module_name.' created.');
            }
        }
        // return redirect()->route($this->folder_name.'-view');
        return redirect()->back();

    }

     public function add(Request $request){
         if (Gate::denies('doctor-management-add')) {
             abort(403);
         }
         if($request->isMethod('post')){
             return $this->form($request);
         }else{
             $data['page_header'] = "Add ".$this->module_name;
             $data['result'] = null;
             $data['specialities'] = Speciality::all();
             $data['cities'] = City::all();
             $data['doctor_name'] = null;
             $data['doctor_link'] = null;
             $data['include_status_radio'] = 1;
             $data['include_is_mobile_show_radio'] = 1;
             $data['include_is_web_show_radio'] = 1;
             $file = "admin.".$this->folder_name.".add";
             return view($file,$data);
         }
     }

    public function edit(Request $request, $id){
        if (Gate::denies('doctor-management-update')) {
            abort(403);
        }
        $id = decrypt($id);
        if($request->isMethod('post')){
            return $this->form($request,$id);
        }else{
            $data['page_header'] = "Edit ".$this->module_name;
            $data['cities'] = City::where(['status' => true, 'lang_id' => 1])->get();
            $data['result'] = DoctorDetail::where('doctor_id',$id)->first();
            $result = $data['result'];
            $data['doctor_name'] = $result && $result->prefix ? $result->prefix.' '.$result->user->name : $result->user->name;
            $data['doctor_link'] = '';
            if (isset($result->user->doctorSpecialityDetails[0])) {
                $data['doctor_link'] = str_replace(' ','-', strtolower(str_replace('api','',env('APP_URL')).'doctor/'.$result->user->city->name.'/'.$result->user->doctorSpecialityDetails[0]->name.'/'.($result->prefix ? $result->prefix.'-'.$result->user->name : $result->user->name).'/'.$result->user->id));
            }
            $data['include_status_radio'] = 1;
            $data['include_is_mobile_show_radio'] = 1;
            $data['include_is_web_show_radio'] = 1;
            $data['completion_progress'] = $this->progress($id)['completion_progress'];
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

        $template_path = 'email_templates.doctor_login_credentials';
        $template_data = [
            "email" => $user->email,
            "password" => $password,
        ];
        $to_email = $user->email;
        $subject = "Welcome to MeriSehat";
        return true;
        return EmailHelper::sendMail($template_path, $template_data, $to_email, $subject);
    }

    public function reviews($doctor_id){
        Review::getReviews($doctor_id);
         $doctor = DoctorDetail::find($doctor_id);
         return $doctor->appointments[0]->review;
    }

    public function addShifts(Request $request, $doctor_id){
        if (Gate::denies('doctor-management-shift-add-shift')) {
            abort(403);
        }
        $doctor_clinic = DoctorClinic::create([
            'doctor_id' => $doctor_id,
            'clinic_id' => $request->clinic,
            'consultation_fee' => $request->consultation_fee,
            'consultation_duration' => $request->consultation_duration,
            'status' => 1
        ]);


        if ($request->has('timeslot')) {
            foreach ($request->timeslot as $timeslot) {
                $start_time = $timeslot;
                $end_time_find = strtotime($start_time);
                $end_time_add = date("H:i:s", strtotime('+'.$request->consultation_duration.' minutes', $end_time_find));
                $end_time = $end_time_add;
                $clinic_timings = ClinicTiming::create([
                    'doctor_clinic_id' => $doctor_clinic->id,
                    'day' => $request->day,
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'is_physical' => $request->is_physical ?? 0,
                    'status' => 1
                ]);
            }
        }

        Helper::toast('success','Doctor Shift Created.');
        return back();
    }

    public function deleteShifts(Request $request, $doctor_id){
        ClinicTiming::where('id', $request->recordId)->delete();
        Helper::toast('success','Doctor Shift Deleted.');
        return back();
    }

    public function updateServices(Request $request, $doctor_id){
        if (Gate::denies('doctor-management-service-edit-services')) {
            abort(403);
        }
        if($request->isMethod('post')){
//            DoctorService::where('doctor_id',$doctor_id)->delete();
            if($request->service && count($request->service) > 0){
                foreach($request->service as $service){
                    DoctorService::where('doctor_id',$doctor_id)->create([
                        'doctor_id' => $doctor_id,
                        'service_id' => $service,
                    ]);
                }
            }
            Helper::toast('success','Doctor Services Updated.');
            return back();
        }else{
            $data['services'] = Service::where('status', '1')->get();
            $data['result'] = DoctorService::where('doctor_id',$doctor_id)->get();
            $file = "admin.".$this->folder_name.".edit_services";
            return view($file,$data);
        }
    }

    public function editServices(Request $request, $service_id){
        if (Gate::denies('doctor-management-service-edit-services')) {
            abort(403);
        }
        if($request->isMethod('post')){
            if($request->has('service')){
                DoctorService::where('id',$service_id)->update([
                    'service_id' => $request->service,
                ]);
            }
            Helper::toast('success','Doctor Services Updated.');
            return back();
        }else{
            $data['services'] = Service::where('status', '1')->get();
            $data['result'] = DoctorService::where('id',$service_id)->get();
            return $data;
        }
    }

    public function deleteServices(Request $request, $doctor_id){
        if (Gate::denies('doctor-management-service-delete')) {
            abort(403);
        }
        DoctorService::where('doctor_id',$doctor_id)->where('id', $request->recordId)->delete();
        Helper::toast('success','Doctor Services Deleted.');
        return back();
    }


    public function updateSpecialities(Request $request, $doctor_id){
        if (Gate::denies('doctor-management-specialties-edit')) {
            abort(403);
        }
        if($request->isMethod('post')){
//            DoctorSpeciality::where('doctor_id',$doctor_id)->delete();
            if($request->specialities && count($request->specialities) > 0){
                foreach($request->specialities as $speciality){
                    DoctorSpeciality::where('doctor_id',$doctor_id)->create([
                        'doctor_id' => $doctor_id,
                        'speciality_id' => $speciality,
                    ]);
                }
            }
            Helper::toast('success','Doctor Specialities Updated.');
            return back();
        }else{
            $data['specialities'] = Speciality::where('status', '1')->get();
            $data['result'] = DoctorSpeciality::where('doctor_id',$doctor_id)->get();
            $file = "admin.".$this->folder_name.".edit_specialities";
            return view($file,$data);
        }
    }

    public function editSpecialities(Request $request, $speciality_id){
        if (Gate::denies('doctor-management-specialties-edit')) {
            abort(403);
        }
        if($request->isMethod('post')){
            if($request->has('speciality')){
                DoctorSpeciality::where('id',$speciality_id)->update([
                    'speciality_id' => $request->speciality,
                ]);
            }
            Helper::toast('success','Doctor Specialities Updated.');
            return back();
        }else{
            $data['specialities'] = Speciality::where('status', '1')->get();
            $data['result'] = DoctorSpeciality::where('id',$speciality_id)->get();
            return $data;
        }
    }

    public function deleteSpecialities(Request $request, $doctor_id){
        if (Gate::denies('doctor-management-specialties-delete')) {
            abort(403);
        }
        DoctorSpeciality::where('doctor_id',$doctor_id)->where('id', $request->recordId)->delete();
        Helper::toast('success','Doctor Specialities Deleted.');
        return back();
    }

    public function updateExperiences(Request $request, $doctor_id){
        if (Gate::denies('doctor-management-experience-edit')) {
            abort(403);
        }
        if($request->isMethod('post')){
//            DoctorExperience::where('doctor_id',$doctor_id)->delete();
            if($request->position && count($request->position) > 0){
                foreach($request->position as $key => $position){
                    $data = [
                        'doctor_id' => $doctor_id,
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
                    DoctorExperience::where('doctor_id',$doctor_id)->create($data);
                }
            }
            Helper::toast('success','Doctor Experiences Updated.');
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
            $data['result'] = DoctorExperience::where('doctor_id',$doctor_id)->get();
            $file = "admin.".$this->folder_name.".edit_experiences";
            return view($file,$data);
        }
    }

    public function editExperiences(Request $request, $experience_id){
        if (Gate::denies('doctor-management-experience-edit')) {
            abort(403);
        }
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
                DoctorExperience::where('id',$experience_id)->update($data);
            }
            Helper::toast('success','Doctor Experiences Updated.');
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
            $data['result'] = DoctorExperience::where('id',$experience_id)->get();
            return $data;
        }
    }

    public function deleteExperiences(Request $request, $doctor_id){
        if (Gate::denies('doctor-management-experience-delete')) {
            abort(403);
        }
        DoctorExperience::where('doctor_id',$doctor_id)->where('id', $request->recordId)->delete();
        Helper::toast('success','Doctor Experiences Deleted.');
        return back();
    }

    public function updateEducations(Request $request, $doctor_id){
        if($request->isMethod('post')){
//            dd($request->all());
//            DoctorEducation::where('doctor_id',$doctor_id)->delete();
            if($request->degree && count($request->degree) > 0){
                foreach($request->degree as $key => $degree){
                    $data = [
                        'doctor_id' => $doctor_id,
                        'degree' => $degree,
                        'institute' => $request->university[$key],
                        'is_completed' => 0,
                        'year_of_completion' => null,
                    ];
                    if(isset($request->year_of_completion[$key])){
                        $data['year_of_completion'] = $request->year_of_completion[$key];
                        $data['is_completed'] = 1;
                    }
                    DoctorEducation::where('doctor_id',$doctor_id)->create($data);
                }
            }
            Helper::toast('success','Doctor Educations Updated.');
            return back();
        }else{
            $data['degrees'] = Degree::where('status', '1')->orderBy('name','asc')->get();
            $data['universities'] = University::where('status', '1')->orderBy('name','asc')->get();
            $data['result'] = DoctorEducation::where('doctor_id',$doctor_id)->get();
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
                DoctorEducation::where('id',$education_id)->update($data);
            }
            Helper::toast('success','Doctor Educations Updated.');
            return back();
        }else{
            $data['degrees'] = Degree::where('status', '1')->orderBy('name','asc')->get();
            $data['universities'] = University::where('status', '1')->orderBy('name','asc')->get();
            $data['result'] = DoctorEducation::where('id',$education_id)->get();
            return $data;
        }
    }

    public function deleteEducations(Request $request, $doctor_id){
        DoctorEducation::where('doctor_id',$doctor_id)->where('id', $request->recordId)->delete();
        Helper::toast('success','Doctor Educations Deleted.');
        return back();
    }

    public function updateArticles(Request $request, $doctor_id){
        if($request->article){
            Article::where('id',$request->article)->update([
                'approved_by' => $doctor_id,
            ]);
        }
        Helper::toast('success','Article has been assigned.');
        return back();
    }

    public function deleteArticles(Request $request, $doctor_id){
        if($request->recordId){
            Article::where('id',$request->recordId)->where('approved_by',$doctor_id)->update([
                'approved_by' => null,
//                'status' => 0,
            ]);
        }
        Helper::toast('success','Article has been unassigned.');
        return back();
    }

    public function updateVideos(Request $request, $doctor_id){
        if($request->video){
            WidgetMedia::where('id',$request->video)->update([
                'approved_by' => $doctor_id,
            ]);
        }
        Helper::toast('success','Video has been assigned.');
        return back();
    }

    public function deleteVideos(Request $request, $doctor_id){
        if($request->recordId){
            WidgetMedia::where('id',$request->recordId)->where('approved_by',$doctor_id)->update([
                'approved_by' => null,
            ]);
        }
        Helper::toast('success','Video has been unassigned.');
        return back();
    }

    public function updateOnboardDoctor($id)
    {
        $id = decrypt($id);
        $about = $this->mainModel($id);
        $progress = $this->progress($id);
        $data['completion_progress'] = $progress['completion_progress'];
        $data['verification_progress'] = $progress['verification_progress'];
        
        $data['doctor'] = $about;
        $data['specialities'] =  Speciality::where('lang_id', 1)->where('status','1')->get();
        $data['locations'] =  City::where('lang_id', 1)->where('status','1')->get();
        $data['clinics'] = Clinic::where('status', 1)->get();
        $data['services'] = Service::all();
        $data['degrees'] = Degree::all();
        $data['universities'] = University::all();
        $data['conditions'] = Disease::all();
        $positions = [
            "Professor",
            "Assistant Professor",
            "Associate Professor",
            "Senior Registrar",
            "Consultant",
            "HO",
        ];
        $data['positions'] = $positions;
        $data['days'] = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
        $data['module_name'] = $this->module_name;
        $file = "admin.".$this->folder_name.".edit_onboard";
        
        return view($file, $data);
    }

    public function editOnboard(Request $request, $id)
    {
        $doctor = MainModel::where('id', $id)->first();
        if ($request->has('prefix')) {
            $user_detail['prefix']=$request->prefix;
        }
        if($request->has('name')){
            $user_data['name'] = $request->name;
        }
        if($request->has('phone')){
            $user_data['phone'] = $request->phone;
        }
        if($request->has('email')){
            $user_data['email'] = $request->email;
        }
        if($request->has('birth_date')){
            $user_data['birth_date'] = $request->birth_date;
        }
        if($request->has('gender')){
            $user_data['gender'] = $request->gender;
        }
        if($request->has('city_id')){
            $user_data['city_id'] = $request->city_id;
        }
        if($request->hasFile('image')){
            $folder="doctor";
            $key="image";
            $user_data['image'] = $this->S3UploaderSpecificKey($key,$request,$folder);
        }
        if($request->has('is_staff')){
            $user_detail['is_staff'] = $request->staff;
        }
        if($request->has('about')){
            $user_detail['about'] = $request->about;
        }
        if($request->has('pmc_no')){
            $user_detail['pmc_no'] = $request->pmc_no;
        }
        if($request->has('cnic')){
            $user_detail['cnic'] = $request->cnic;
        }
        if($request->has('year_of_experience')){
            $user_detail['experience_year'] = $request->year_of_experience;
        }
        if($request->has('assistant_phone')){
            $user_detail['assistant_phone'] = $request->assistant_phone;
        }
        if($request->has('video_consultation')){
            $doctor_clinic = DoctorClinic::create([
                'doctor_id' => $id,
                'clinic_id' => 0,
                'consultation_fee' => $request->consultation_fee_video,
                'consultation_duration' => $request->consultation_duration_video,
                'status' => 1
            ]);


            if ($request->has('days_video')) {
                foreach ($request->days_video as $key => $timeslot_video) {
                    $start_time = $request->start_time_video;
                    $end_time = $request->end_time_video;
                    $clinic_timings = ClinicTiming::create([
                        'doctor_clinic_id' => $doctor_clinic->id,
                        'day' => $timeslot_video,
                        'start_time' => $start_time,
                        'end_time' => $end_time,
                        'is_physical' => 0,
                        'status' => 1
                    ]);
                }
            }
            $user_detail['is_video_consultancy'] = 1;
        }
        if($request->has('clinic_consultation')){
            $doctor_clinic = DoctorClinic::create([
                'doctor_id' => $id,
                'clinic_id' => $request->clinic[0],
                'consultation_fee' => $request->consultation_fee,
                'consultation_duration' => $request->consultation_duration,
                'status' => 1
            ]);


            if ($request->has('days')) {
                foreach ($request->days as $key => $timeslot) {
                    $start_time = $request->start_time;
                    $end_time = $request->end_time;
                    $clinic_timings = ClinicTiming::create([
                        'doctor_clinic_id' => $doctor_clinic->id,
                        'day' => $timeslot,
                        'start_time' => $start_time,
                        'end_time' => $end_time,
                        'is_physical' => 1,
                        'status' => 1
                    ]);
                }
            }
            $user_detail['is_physical_consultancy'] = 1;
        }
        $doctor->update($user_data);
        DoctorDetail::where('doctor_id', $id)->update($user_detail);
        if($request->has('speciality')){
            DoctorSpeciality::where('doctor_id', $id)->delete();
            foreach($request->speciality as $speciality){
                DoctorSpeciality::create([
                    'doctor_id' => $id,
                    'speciality_id' => $speciality,
                ]);
            }
        }
        if($request->has('account_name')
        || $request->has('bank_name')
        || $request->has('iban'))
        {

            $validator = Validator::make($request->all(), [
                'account_name' => ['required','string'],
                'bank_name' => ['required'],
                'iban' => ['required'],
            ]);
            if ($validator->fails()) {
                return back()
                ->withErrors($validator)
                ->withInput();
            }

        }
        
        DoctorBankDetail::updateOrCreate(
            ['doctor_id' => $id],
            [
            'account_name' => $request->account_name,
            'bank_name' => $request->bank_name,
            'iban_number' => $request->iban,
            'account_number' => $request->iban,
            ]
        );
        if($request->has('service')){
            foreach($request->service as $service){
                DoctorService::create([
                    'doctor_id' => $id,
                    'service_id' => $service,
                ]);
            }
        }
        if($request->has('condition')){
            DoctorCondition::where('doctor_id', $id)->delete();
            foreach($request->condition as $condition){
                DoctorCondition::create([
                    'doctor_id' => $id,
                    'disease_id' => $condition,
                ]);
            }
        }
        if($request->has('degree')){
            DoctorEducation::where('doctor_id', $id)->delete();
            foreach($request->degree as $key => $degree){
                $is_completed = 0;
                if(Carbon::parse($request->year_of_completion[$key])->format('Y') !== now()->format('Y')){
                    $is_completed = 1;
                }
                DoctorEducation::create([
                    'doctor_id' => $id,
                    'degree' => $degree,
                    'institute' => $request->institute_degree[$key],
                    'year_of_completion' => $request->year_of_completion[$key],
                    'is_completed' => $is_completed,
                ]);
            }
        }
        if($request->has('designation')){
            DoctorExperience::where('doctor_id', $id)->delete();
            foreach($request->designation as $key => $designation){
                $is_completed = $request->end_year[$key];
                if($request->end_year[$key] == 0){
                    $is_completed = "null";
                }
                DoctorExperience::create([
                    'doctor_id' => $id,
                    'position' => $designation,
                    'institute' => is_array($request->institute_experience) ? $request->institute_experience[$key] : $request->institute_experience,
                    'start_year' => $request->start_year[$key],
                    'end_year' => $request->end_year[$key],
                    'is_completed' => $is_completed,
                ]);
            }
        }
        Helper::toast('success', 'Profile Updated');
        return redirect()->route('doctor-view');
    }

    private function mainModel($id)  
    {
        return MainModel::with('doctorDetail', 'doctorBankDetails', 'doctorSpecialities',
        'doctorServices','doctorEducation', 'doctorExperiences', 'doctorClinics', 'doctorConditions')
        ->where('id', $id)->first();
    }

    private function progress($id)
    {   
        $about = $this->mainModel($id);
        $data['about_completion'] = 7;
        if(!is_null($about->doctorDetail))
            $data['about_completion'] += 7;
        if(!is_null($about->doctorBankDetails))
            $data['about_completion'] += 7;
        if(!is_null($about->doctorSpecialities) && count($about->doctorSpecialities) > 0)
            $data['about_completion'] += 6;
        if(!is_null($about->doctorServices) && count($about->doctorServices) > 0)
            $data['about_completion'] += 6;
        $qualification = MainModel::with('doctorEducation', 'doctorExperiences')->where('id', $id)->first();
        $data['qualification_completion'] = 11;
        if(!is_null($qualification->doctorEducation) && count($qualification->doctorEducation) > 0)
            $data['qualification_completion'] += 11;
        if(!is_null($qualification->doctorExperiences) && count($qualification->doctorExperiences) > 0)
            $data['qualification_completion'] += 11;
        $consultation = MainModel::with('doctorClinics')->where('id', $id)->first();
        $data['consultation_completion'] = 0;
        if(!is_null($consultation->doctorClinics) && count($consultation->doctorClinics) > 0)
            $data['consultation_completion'] += 34;
        $data['completion_progress'] = $data['about_completion']+$data['qualification_completion']+$data['consultation_completion'];
        $verification = DoctorVerification::where('doctor_id', $id)
        ->where('status',1)
        ->count();
        $data['verification_progress'] = 0;
        if($verification > 0)
            if($verification === 12)
                $data['verification_progress'] = 0;
            $data['verification_progress'] += ($verification * 100) / 12;
            
        return $data;    
    }
}
