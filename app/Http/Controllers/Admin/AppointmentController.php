<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Appointment as MainModel,
    City,
    DoctorClinic,
    Speciality,
    UserFamilyMember,
    User,
    QuestionaireFormData,
    Appointment};
use App\Http\Common\{Helper, Constant};
use DataTables;
use Illuminate\Support\Facades\DB;
use Str;
use Illuminate\Support\Facades\Gate;

class AppointmentController extends Controller
{
    public $folder_name = 'appointment'; // For view routes and file calling and saving
    public $module_name = 'Appointment'; // For toast And page header
    public $input_elements;
    public $toDate;
    public $fromDate;

    public function view(Request $request){
        if (Gate::denies('booking-management-view')) {
            abort(403);
        }
        if($request->isMethod('post')){
            return back();
        }else{
            $data['folder_name'] = $this->folder_name;
            $data['module_name'] = $this->module_name;
            if ($request->ajax()) {
//                $data = MainModel::get();
                $data = new Appointment();
                if ($request->has('search_name') && $request->search_name != '') {
                    $search_name = $request->search_name;
                    $data = $data->whereHas('user', function($data) use ($search_name){
                        $data = $data->where('name', 'like', '%'.$search_name.'%');
                    });
                }
                if ($request->has('search_toDate') && $request->search_toDate != '') {
                    if ($request->tab == 'past') {
                        $data = $data->where('date', '<', $request->search_toDate);
                    } elseif ($request->tab == 'today') {
                        $data = $data->where('date', $request->search_toDate);
                    } elseif ($request->tab == 'upcoming') {
                        $data = $data->where('date', '>', $request->search_toDate);
                    } else {
                        $data = $data->where('date', $request->search_toDate);
                    }
                } else {
                    if ($request->tab == 'past') {
                        $data = $data->where('date', '<', date('Y-m-d'));
                    } elseif ($request->tab == 'today') {
                        $data = $data->where('date', date('Y-m-d'));
                    } elseif ($request->tab == 'upcoming') {
                        $data = $data->where('date', '>', date('Y-m-d'));
                    }
                }
                if ($request->has('search_category') && $request->search_category != '') {
                    $data = $data->where('priority', $request->search_category);
                }
                if ($request->has('search_type') && $request->search_type != '') {
                    $data = $data->where('type', $request->search_type);
                }
                if ($request->has('search_progress') && $request->search_progress != '') {
                    $data = $data->where('progress', $request->search_progress);
                }
//                dd($data->toSql(), $data->getBindings());
                $data = $data->select("*", DB::raw("CONCAT(date,' ',time) as appt_datetime"));
                $data = $data->orderBy('appt_datetime', 'desc')->get();

                return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('time_slot', function($row){
                            $date = date('d M', strtotime($row->date));
                            $time = date('h:i a', strtotime($row->time));
                            return $date.' '.$time;
//                            return date('', strtotime($row->status));
                        })
                        ->addColumn('status', function($row){
                            if(Gate::allows('booking-management-update-status')){
                                $id = "status-change";
                            }else{
                                $id = "";
                            }
                            if($row->status == 1)
                            {
                               $btn = '<a class="edit btn btn-primary btn-sm" id="'.$id.'" data-id="'.$row->id.'" data-status="'.$row->status.'">Confirmed</a>';
                            }
                            else
                            {
                                $btn = '<a class="edit btn btn-danger btn-sm" id="'.$id.'" data-id="'.$row->id.'" data-status="'.$row->status.'">Unconfirmed</a>';
                            }
                            return $btn;
                        })
                        ->addColumn('user', function($row){
                            $user = $row->patientname;
                            return $user;
                        })
                        ->addColumn('doctor', function($row){
                            $user = User::where('id',$row->doctor_id)->value('name');
                            return $user;
                        })
                        ->addColumn('doctor_speciality', function($row){
                            $specialities = '';
                            $user = User::with('doctorSpecialityDetails')->where('id',$row->doctor_id)->first();
                            if (isset($user->doctorSpecialityDetails)) {
                                foreach ($user->doctorSpecialityDetails as $speciality) {
                                    $specialities .= '<div>'.$speciality->name.'</div>';
                                }
                            }
                            if (isset($user->doctorSpecialityDetails)) {
                                $specialities = $user->doctorSpecialityDetails;
                            }
                            return $specialities;
                        })
//                        ->addColumn('family_member', function($row){
//                            $user = UserFamilyMember::where('id',$row->family_member_id)->value('name');
//                            return $user;
//                        })
                        ->addColumn('actionby', function($row){
                            $btn = '';
//                            if(Gate::allows('booking-management-update')){
//                                $btn .= '<a class="btn btn-outline-info btn-sm" href="'.route('appointment-edit','['.$row->e_id.']').'"><i class="icon-edit"></i></a>';
//                            }
                            if(Gate::allows('booking-management-update')){
                                $btn .= '<a class="btn btn-outline-primary btn-sm detail" href="'.route('appointment-detail','['.$row->e_id.']').'"><i class="icon-eye"></i></a>';
                            }
//                            if(Gate::allows('booking-management-questionaire-form')){
//                                $btn .= '<a class="btn btn-outline-primary btn-sm fill_questionaire" href="#" data-id="'.$row->id.'"><i class="icon-format_align_justify"></i></a>';
//                            }
                            return $btn;
                        })
                        ->rawColumns(['created_at','status','user','doctor','family_member','actionby','action'])
                        ->make(true);
            }
//            dd($data);
            $file = "admin.".$this->folder_name.".view";
            // return $data;
            return view($file,$data);
        }
    }

    public function bookingNow(Request $request){
        if (Gate::denies('booking-management-view')) {
            abort(403);
        }
        if($request->isMethod('post')){
            return back();
        }else{
            $data['folder_name'] = $this->folder_name;
            $data['module_name'] = $this->module_name;

            $data['locations'] = City::all();
            $data['specialities'] = Speciality::all();

            if ($request->ajax()) {
                $data = new Appointment();
                $data = $data->whereHas('doctor', function($data) {
                    $data = $data->whereHas('doctorDetail', function($data) {
                        $data = $data
                            ->where('is_instant_consultation', 1)
                            ->where('is_available', 1);
                    });
                });
                if ($request->has('search_name') && $request->search_name != '') {
                    $search_name = $request->search_name;
                    $data = $data->whereHas('user', function($data) use ($search_name){
                        $data = $data->where('name', 'like', '%'.$search_name.'%');
                    });
                }
                if ($request->has('search_toDate') && $request->search_toDate != '') {
                    $data = $data->where('date', $request->search_toDate);
                }
                if ($request->has('search_speciality') && $request->search_speciality != '') {
                    $search_speciality = $request->search_speciality;
//                    $data = $data->where('priority', $request->search_speciality);
                    $data = $data->whereHas('doctor', function($data) use ($search_speciality){
//                        $data = $data->where('city_id', $search_location);
                        $data = $data->whereHas('doctorSpecialities', function($data) use ($search_speciality){
                            $data = $data->where('speciality_id', $search_speciality);
                        });
                    });
                }
                if ($request->has('search_location') && $request->search_location != '') {
                    $search_location = $request->search_location;
                    $data = $data->whereHas('user', function($data) use ($search_location){
                        $data = $data->where('city_id', $search_location);
                    });
                }
                if ($request->has('search_progress') && $request->search_progress != '') {
                    $data = $data->where('progress', $request->search_progress);
                }
                $data = $data->get();


                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('time_slot', function($row){
                        $date = date('d M', strtotime($row->date));
                        $time = date('h:i a', strtotime($row->time));
                        return $date.' '.$time;
                    })
                    ->addColumn('status', function($row){
                        if(Gate::allows('booking-management-update-status')){
                            $id = "status-change";
                        }else{
                            $id = "";
                        }
                        if($row->status == 1)
                        {
                            $btn = '<a class="edit btn btn-primary btn-sm" id="'.$id.'" data-id="'.$row->id.'" data-status="'.$row->status.'">Confirmed</a>';
                        }
                        else
                        {
                            $btn = '<a class="edit btn btn-danger btn-sm" id="'.$id.'" data-id="'.$row->id.'" data-status="'.$row->status.'">Unconfirmed</a>';
                        }
                        return $btn;
                    })
                    ->addColumn('user', function($row){
//                        $user = User::where('id',$row->user_id)->value('name');
                        $user = $row->patientname;
                        return $user;
                    })
                    ->addColumn('doctor', function($row){
                        $doctor = User::where('id',$row->doctor_id)->value('name');
                        return $doctor;
                    })
                    ->addColumn('location', function($row){
                        $location = User::with('city')->where('id',$row->user_id)->first();
                        if (isset($location->city)) {
                            return $location->city->name;
                        } else {
                            return '-';
                        }
                    })
                    ->addColumn('doctor_speciality', function($row){
                        $specialities = '';
                        $user = User::with('doctorSpecialityDetails')->where('id',$row->doctor_id)->first();
                        if (isset($user->doctorSpecialityDetails)) {
                            foreach ($user->doctorSpecialityDetails as $speciality) {
                                $specialities .= '<div>'.$speciality->name.'</div>';
                            }
                        }
                        if (isset($user->doctorSpecialityDetails)) {
                            $specialities = $user->doctorSpecialityDetails;
                        }
                        return $specialities;
                    })
                    ->addColumn('actionby', function($row){
                        $btn = '';
//                            if(Gate::allows('booking-management-edit')){
//                                $btn .= '<a class="btn btn-outline-info btn-sm" href="'.route('appointment-edit','['.$row->e_id.']').'"><i class="icon-edit"></i></a>';
//                            }
                        if(Gate::allows('booking-management-detail')){
                            $btn .= '<a class="btn btn-outline-primary btn-sm detail" href="'.route('appointment-detail','['.$row->e_id.']').'"><i class="icon-eye"></i></a>';
                        }
//                            if(Gate::allows('booking-management-questionaire-form')){
//                                $btn .= '<a class="btn btn-outline-primary btn-sm fill_questionaire" href="#" data-id="'.$row->id.'"><i class="icon-format_align_justify"></i></a>';
//                            }
                        return $btn;
                    })
                    ->rawColumns(['created_at','status','user','doctor','family_member','actionby','action'])
                    ->make(true);
            }
            $file = "admin.".$this->folder_name.".booking_now_view";
            // return $data;
            return view($file,$data);
        }
    }

    public function detail(Request $request, $id){
        if (Gate::denies('booking-management-view')) {
            abort(403);
        }
        $id = decrypt($id);
        $appointment = MainModel::find($id);
        $get_date = Helper::get_date_from_calender();
        $data = [
            'appointment' => $appointment,
            'calendar' => $get_date
        ];
        $file = "admin.".$this->folder_name.".detail";
        return view($file,$data);
    }

    public function changeStatus($id){
        if (Gate::denies('booking-management-update-status')) {
            abort(403);
        }
        try
        {
            if(!$id)
            {
                return "No appointment found";
            }
            $appointment = MainModel::where('id',$id)->first();
            if($appointment->status == 1)
            {
                MainModel::where('id',$id)->update(['status' => 0,'action_by'=>auth()->user()->id,'progress' =>(new Constant)->APPOINTMENT_STATUS_PENDING]);
                return ['message' =>"Status Updated Successfully"];
            }
            else
            {
                MainModel::where('id',$id)->update(['status' => 1,'action_by'=>auth()->user()->id,'progress' =>(new Constant)->APPOINTMENT_STATUS_COMPLETED]);
                return ['message' =>"Status Updated Successfully"];
            }

        }
        catch(\Exception $e)
        {
            return "Something went wrong";
        }
    }

    public function changeProgress(Request $request){
        if (Gate::denies('booking-management')) {
            abort(403);
        }
        try
        {
            $id = decrypt($request->id);

            if(!$request->id)
            {
                return "No appointment found";
            }
            $appointment = MainModel::where('id',$id)->first();
            if($appointment->status == 1)
            {
                MainModel::where('id',$id)->update(['progress' => $request->progress]);
                return ['message' =>"Status Updated Successfully"];
            }
            else
            {
                return ['message' =>"Status not Updated"];
            }

        }
        catch(\Exception $e)
        {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function changeCancelled(Request $request){
        if (Gate::denies('booking-management-update-status')) {
            abort(403);
        }
        try
        {
            $id = decrypt($request->id);

            if(!$request->id)
            {
                return "No appointment found";
            }
            $appointment = MainModel::where('id',$id)->first();
            if($appointment->status == 1)
            {
                MainModel::where('id',$id)->update(['progress' => (new Constant)->APPOINTMENT_STATUS_CANCELLED]);
                Helper::cancelAppointment($id);
                return ['message' =>"Status Updated Successfully"];
            }
            else
            {
                return ['message' =>"Status not Updated"];
            }

        }
        catch(\Exception $e)
        {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function form(Request $request, $id = null){
        $request->validate(MainModel::getValidationRules($id));
        $sequence = MainModel::count();
        $sequence = $sequence + 1;
        $data = [
            'name' => $request->name,
            'ad_window_id' => $request->ad_window_id,
            'source_type' => "image",
        ];
        if($request->hasFile('source')){
            $folder=$this->folder_name;
            $key="source";
            $data['source'] = $this->S3UploaderSpecificKey($key,$request,$folder);
        }
        if($id){
            if(MainModel::find($id)->update($data)){
                Helper::toast('success',$this->module_name.' Updated.');
            }
        }else{
            if(MainModel::create($data)){
                Helper::toast('success',$this->module_name.' created.');
            }
        }
        return redirect()->route($this->folder_name.'-view');
    }

    public function edit(Request $request, $id){
        if(Gate::denies('booking-management-update')){
            abort(403);
        }
        $id = decrypt($id);
        if($request->isMethod('post')){
            return $this->form($request,$id);
        }else{
            $data['page_header'] = "Edit ".$this->module_name;
            $data['result'] = MainModel::find($id);
            $file = "admin.".$this->folder_name.".form";
            return view($file,$data);
        }
    }

    public function getQuestionaireForm(Request $request,$appointment_id){
        if(Gate::denies('booking-management-questionaire-form')){
            abort(403);
        }
        if($request->isMethod('post')){
            $data = [];
            foreach($request->post() as $key => $value){
                if($key == "_token"){
                    continue;
                }
                QuestionaireFormData::updateOrCreate([
                    'appointment_id' => $appointment_id,
                    'title' => $key,
                ],[
                    'value' => $value,
                ]);
            }
            return back();
        }else{
            $fields = null;
            $appointment = MainModel::find($appointment_id);
            if(isset($appointment->doctor->doctorSpecialities[0]->speciality->questionaire_form->fields)){
                 $fields = $appointment->doctor->doctorSpecialities[0]->speciality->questionaire_form->fields;
            };
            $values = [];
            if(isset($appointment->questionaire_data)){
                foreach($appointment->questionaire_data as $key => $value){
                    $values[$value->title] = $value->value;
                }
            }
            $data['page_header'] = "Fill Questionaire Form";
            $data['result'] = $fields;
            $data['values'] = $values;
            $file = "admin.".$this->folder_name.".questionaire_form";
            return view($file,$data);
        }
    }
}
