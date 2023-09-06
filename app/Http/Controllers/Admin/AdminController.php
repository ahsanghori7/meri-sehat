<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\{DoctorDetail,
    Subscription,
    User as MainModel,
    Article,
    Disease,
    Topics,
    Drug,
    HealthScan,
    Newsletter,
    Appointment,
    User,
    UserSubscription};
use App\Models\Role;
use App\Models\UserRoles;
use Auth;
use Yajra\Datatables\Datatables;
use App\Http\Common\Helper;
use App\Http\Common\Constant;
use Hash;
use Carbon\Carbon;


class AdminController extends Controller
{
    public $folder_name = 'admin_users'; // For view routes and file calling and saving
    public $module_name = 'Manage Admin Users'; // For toast And page header


    public function dashboard(Request $request){
        // $appointments = Appointment::all();
        // foreach ($appointments as $appointment){
        //     if($appointment->ms_commission_is_percentage){
        //         $subtotal = 0;
        //         if($appointment->grand_total > 0){
        //             $ms_total = $appointment->grand_total * ($appointment->ms_commission / 100);
        //             $appointment->ms_total = $ms_total;
        //             $appointment->doctor_total = $appointment->grand_total - $ms_total;
        //             $appointment->save();
        //         }
        //         $subtotal = 0;
        //     }else{
        //         $ms_total = $appointment->ms_commission;
        //         $appointment->ms_total = $ms_total;
        //         $appointment->doctor_total = $appointment->grand_total - $ms_total;
        //         $appointment->save();
        //     }
        // }
        // $request->validate([
        //     'start_date' => 'required|date_format:Y-m-d|before_or_equal:end_date',
        //     'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date'
        // ]);
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $action = $request->get('action');
        $constants = new Constant();

        $doctor_available_count = DoctorDetail::where('is_available', 1)->count();


        if($start_date && $end_date){
            $start_date = new Carbon($start_date);
            $end_date = new Carbon($end_date);

            $total_consultations_count = Appointment::whereBetween('created_at', [$start_date->format('Y-m-d')." 00:00:00", $end_date->format('Y-m-d')." 23:59:59"])->count();
            $consultation_completed_count = Appointment::whereBetween('created_at', [$start_date->format('Y-m-d')." 00:00:00", $end_date->format('Y-m-d')." 23:59:59"])->where('progress',(new Constant)->APPOINTMENT_STATUS_COMPLETED)->count();
            $consultation_cancelled_count = Appointment::whereBetween('created_at', [$start_date->format('Y-m-d')." 00:00:00", $end_date->format('Y-m-d')." 23:59:59"])->whereIn('progress',[(new Constant)->APPOINTMENT_STATUS_CANCELLED])->count();
            $consultation_pending_count = Appointment::whereBetween('created_at', [$start_date->format('Y-m-d')." 00:00:00", $end_date->format('Y-m-d')." 23:59:59"])->where('progress',(new Constant)->APPOINTMENT_STATUS_PENDING)->count();
            $total_earnings = Appointment::whereBetween('created_at', [$start_date->format('Y-m-d')." 00:00:00", $end_date->format('Y-m-d')." 23:59:59"])->sum('ms_total');
            $articles_count = Article::whereBetween('created_at', [$start_date->format('Y-m-d')." 00:00:00", $end_date->format('Y-m-d')." 23:59:59"])->count();
            $diseases_count = Disease::whereBetween('created_at', [$start_date->format('Y-m-d')." 00:00:00", $end_date->format('Y-m-d')." 23:59:59"])->count();
            $topics_count = Topics::whereBetween('created_at', [$start_date->format('Y-m-d')." 00:00:00", $end_date->format('Y-m-d')." 23:59:59"])->count();
            $users_count = MainModel::whereBetween('created_at', [$start_date->format('Y-m-d')." 00:00:00", $end_date->format('Y-m-d')." 23:59:59"])->where('role_id',$constants->USER_ROLE_ID)->count();
            $registered_count = MainModel::whereBetween('created_at', [$start_date->format('Y-m-d')." 00:00:00", $end_date->format('Y-m-d')." 23:59:59"])->whereHas('subscription')->count();
            $scans_count = HealthScan::whereBetween('created_at', [$start_date->format('Y-m-d')." 00:00:00", $end_date->format('Y-m-d')." 23:59:59"])->count();
            $drugs_count = Drug::whereBetween('created_at', [$start_date->format('Y-m-d')." 00:00:00", $end_date->format('Y-m-d')." 23:59:59"])->count();
            $newsletter_count = Newsletter::whereBetween('created_at', [$start_date->format('Y-m-d')." 00:00:00", $end_date->format('Y-m-d')." 23:59:59"])->count();

            $new_scans_count = HealthScan::whereDate( 'created_at', '>=', $start_date->format('Y-m-d'))->whereDate( 'created_at', '<=', $end_date->format('Y-m-d'))->count();
            $total_scans_count = HealthScan::count();

            $new_subscribers_count = User::where('role_id', (new Constant)->USER_ROLE_ID)->whereDate( 'created_at', '>=', $start_date->format('Y-m-d'))->whereDate( 'created_at', '>=', $start_date->format('Y-m-d'))->whereDate( 'created_at', '<=', $end_date->format('Y-m-d'))
                ->whereHas('subscription', function($new_subscribers_count) use ($start_date, $end_date) {
                    $new_subscribers_count = $new_subscribers_count->whereDate( 'created_at', '>=', $start_date->format('Y-m-d'))->whereDate( 'created_at', '<=', $end_date->format('Y-m-d'))->where('is_paid', '1')->where('status', '1')->distinct('user_id');
                })->count();
            $total_subscribers_count = User::where('role_id', (new Constant)->USER_ROLE_ID)
                ->whereHas('subscription')->count();

            $recurring_subscribers_count = User::where('role_id', (new Constant)->USER_ROLE_ID)->whereDate( 'created_at', '>=', $start_date->format('Y-m-d'))->whereDate( 'created_at', '<=', $end_date->format('Y-m-d'))
                ->whereHas('subscription', function($new_subscribers_count) use ($start_date, $end_date) {
                    $new_subscribers_count = $new_subscribers_count->whereDate( 'created_at', '>=', $start_date->format('Y-m-d'))->whereDate( 'created_at', '<=', $end_date->format('Y-m-d'))->where('is_paid', '1')->where('status', '1')->distinct('user_id');
                })->count();
            $subscription_count = Subscription::where('status', '1')->count();
            $getAverageCompletionTime = Appointment::select(\DB::raw("AVG(TIME_TO_SEC(TIMEDIFF(call_started, DATE_ADD(created_at, INTERVAL ". Constant::APPOINTMENT_INSTANT_TIME ." second)))) AS timediff"))
                ->whereDate('created_at', '>=', $start_date->format('Y-m-d'))
                ->whereDate('created_at', '<=', $end_date->format('Y-m-d'))
                ->where('type', 'instant-consultation')
                ->where('progress', 'completed')
                ->where('status', 1)
                ->whereNotNull('call_started')
                ->get();
            $doctor_average_time = CarbonInterval::seconds((int)$getAverageCompletionTime[0]->timediff)
                ->cascade()
                ->forHumans();
          }else{
            $total_consultations_count = Appointment::count();
            $consultation_completed_count = Appointment::where('progress',(new Constant)->APPOINTMENT_STATUS_COMPLETED)->count();
            $consultation_cancelled_count = Appointment::where('progress',(new Constant)->APPOINTMENT_STATUS_CANCELLED)->count();
            $consultation_pending_count = Appointment::where('progress',(new Constant)->APPOINTMENT_STATUS_PENDING)->count();
            $total_earnings = Appointment::sum('ms_total');
            $articles_count = Article::count();
            $diseases_count = Disease::count();
            $topics_count = Topics::count();
            $users_count = MainModel::where('role_id',$constants->USER_ROLE_ID)->count();
            $registered_count = MainModel::whereHas('subscription')->count();
            $scans_count = HealthScan::count();
            $drugs_count = Drug::count();
            $newsletter_count = Newsletter::count();

            $new_scans_count = HealthScan::whereDate( 'created_at', '>=', Carbon::now()->subWeek())->count();
            $total_scans_count = HealthScan::count();

            $new_subscribers_count = User::where('role_id', (new Constant)->USER_ROLE_ID)->whereDate( 'created_at', '>=', Carbon::now()->subWeek())
                ->whereHas('subscription', function($new_subscribers_count) {
                    $new_subscribers_count = $new_subscribers_count->whereDate( 'created_at', '>=', Carbon::now()->subWeek())->where('is_paid', '1')->where('status', '1')->distinct('user_id');
                })->count();
            $total_subscribers_count = User::where('role_id', (new Constant)->USER_ROLE_ID)
                ->whereHas('subscription')->count();

            $recurring_subscribers_count = User::where('role_id', (new Constant)->USER_ROLE_ID)->whereDate( 'created_at', '>=', Carbon::now()->subWeek())
                ->whereHas('subscription', function($new_subscribers_count) {
                    $new_subscribers_count = $new_subscribers_count->whereDate( 'created_at', '>=', Carbon::now()->subWeek())->where('is_paid', '1')->where('status', '1')->distinct('user_id');
                })->count();
            $subscription_count = Subscription::where('status', '1')->count();
            $getAverageCompletionTime = Appointment::select(\DB::raw("AVG(TIME_TO_SEC(TIMEDIFF(call_started, DATE_ADD(created_at, INTERVAL ". Constant::APPOINTMENT_INSTANT_TIME ." second)))) AS timediff"))
                ->where('type', 'instant-consultation')
                ->where('progress', 'completed')
                ->where('status', 1)
                ->whereNotNull('call_started')
                ->get();
            $doctor_average_time = CarbonInterval::seconds((int)$getAverageCompletionTime[0]->timediff)
                ->cascade()
                ->forHumans();
        }
        $doctor_average_time = str_replace(array('hours','minutes','seconds'),array('hr','min','sec'),$doctor_average_time);

        $data = [
            'doctor_available_count' => $doctor_available_count,
            'total_consultations_count' => $total_consultations_count,
            'consultation_completed_count' => $consultation_completed_count,
            'consultation_cancelled_count' => $consultation_cancelled_count,
            'consultation_pending_count' => $consultation_pending_count,
            'total_earnings' => $total_earnings,
            'articles_count' => $articles_count,
            'diseases_count' => $diseases_count,
            'topics_count' => $topics_count,
            'users_count' => $users_count,
            'registered_count' => $registered_count,
            'scans_count' => $scans_count,
            'drugs_count' => $drugs_count,
            'newsletter_count' => $newsletter_count,
            'new_scans_count' => $new_scans_count,
            'total_scans_count' => $total_scans_count,
            'new_subscribers_count' => $new_subscribers_count,
            'total_subscribers_count' => $total_subscribers_count,
            'recurring_subscribers_count' => $recurring_subscribers_count,
            'subscription_count' => $subscription_count,
            'doctor_average_time' => $doctor_average_time,
        ];
        $data['start_date'] = $start_date ? Carbon::parse($start_date)->isoFormat('Y-MM-DD'): null;
        $data['end_date'] = $end_date ? Carbon::parse($end_date)->isoFormat('Y-MM-DD'): null;
        return view('admin.dashboard',$data);
    }

    public function view(){
        if (Gate::denies('admin-roles-view')) {
            abort(403);
        }
        $get_roles = Role::where('id','!=','2');
        if (Auth::user()->role_id != 1) {
            $get_roles = $get_roles->where('parent_id',Auth::user()->role_id);
        }
        $get_roles = $get_roles->get();
        $data['roles'] = $get_roles;
        $file = "admin.".$this->folder_name.".view";
        return view($file,$data);
    }

    public function fetch(Request $request){
        $query = new MainModel();
        // dd(MainModel::CUSTOMER_ROLE);
        if ($request->has('search_name') && $request->search_name != '') {
            $query = $query->where('name', 'LIKE', '%'.$request->search_name.'%');
        }
        if ($request->has('search_phone') && $request->search_phone != '') {
            $query = $query->where('phone', 'LIKE', '%'.$request->search_phone.'%');
        }
        if ($request->has('search_email') && $request->search_email != '') {
            $query = $query->where('email', 'LIKE', '%'.$request->search_email.'%');
        }
        if ($request->has('search_role') && $request->search_role != '') {
            $query = $query->where('role_id', $request->search_role);
        } else {
            if(Auth::user()->role_id == 1){
            $query = $query->where('role_id', '!=' ,MainModel::CUSTOMER_ROLE);
            }
            else {
                $query = $query->where('role_id', '!=' ,MainModel::CUSTOMER_ROLE);
            }
        }
        if ($request->has('search_status') && $request->search_status != '') {
            $query = $query->where('status', $request->search_status);
        }
        if(Auth::user()->role_id != 1){
            $query = $query->with(['role'])->whereHas('role', function ($query) {
                $query->where('parent_id',Auth::user()->role_id);
            });
        }else{
            $query = $query->with(['role']);
        }

        return Datatables::of($query)->make(true);
    }

    public function form(Request $request, $id = null){
        $request->validate(MainModel::getRules($id));

        if($request->has('name')){
            $data['name'] = $request->name;
        }
        if($request->has('email')){
            $data['email'] = $request->email;
        }
        if($request->has('phone')){
            $data['phone'] = $request->phone;
        }
        if($request->has('role')){
            $data['role_id'] = $request->role;
        }
        if($request->has('status')){
            $data['status'] = $request->status ?? 1;
        }
        if($request->has('password')){
            $data['password'] = Hash::make($request->password);
        }
        // if($request->role == MainModel::PICKUP_DISPATCHER_ROLE){
        //     $data['city_id'] = $request->city;
        // }
        // if($request->role == MainModel::DELIVERY_TRANSPORTER_ROLE){
        //     $data['region_id'] = $request->region;
        // }
        // if($request->role == MainModel::OUTLET_MANAGER_ROLE){
        //     $data['outlet_id'] = $request->outlet;
        // }
        if($request->hasFile('image')){
            $image = $request->image->store($this->folder_name,'public');
            $data['image'] = $image;
        }

        if($id){
            $admin = MainModel::find($id);
            if($admin->role_id == 1){
                $data['role_id'] = $admin->role_id;
            }
            if(MainModel::find($id)->update($data)){
                $createOrUpdate['where']['user_id'] = $id;
                $createOrUpdate['data']['role_id'] = $request->role;
                Helper::toast('success',$this->module_name.' Updated.');
            }
        }else{
            if($created_data = MainModel::create($data)){
                $createOrUpdate['where']['user_id'] = $created_data->id;
                $createOrUpdate['data']['role_id'] = $request->role;
                Helper::toast('success',$this->module_name.' created.');
            }
        }

        return redirect()->route($this->folder_name.'-view');
    }

    public function add(Request $request){
        if (Gate::denies('admin-roles-add')) {
            abort(403);
        }
        if($request->isMethod('post')){
            return $this->form($request);
        }else{
            $get_roles = Role::where('id','!=','2');
//            if (Auth::user()->role_id != 1) {
                $get_roles = $get_roles->where('parent_id',Auth::user()->role_id);
//            }
            $get_roles = $get_roles->get();
            $data['page_header'] = "Add ".$this->module_name;
            $data['roles'] = $get_roles;
            $data['result'] = null;
            $file = "admin.".$this->folder_name.".form";
            return view($file,$data);
        }
    }

    public function edit(Request $request, $id){
        if (Gate::denies('admin-roles-update')) {
            abort(403);
        }
        $id = decrypt($id);
        if($request->isMethod('post')){
            return $this->form($request,$id);
        }else{
            $data['page_header'] = "Edit ".$this->module_name;
            $data['roles'] = Role::where('id','!=','2')->get();
            $data['result'] = MainModel::find($id);
            $file = "admin.".$this->folder_name.".form";
            return view($file,$data);
        }
    }

}
