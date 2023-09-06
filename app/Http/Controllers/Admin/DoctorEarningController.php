<?php

namespace App\Http\Controllers\Admin;

use App\Http\Common\Constant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Settings as MainModel;
use App\Models\AdsWindow;
use App\Models\{DoctorEarning,DoctorEarningDetail,Appointment, AppointmentDeduction};
use App\Http\Common\Helper;
use App\Models\AppointmentPayable;
use App\Models\DoctorPayable;
use App\Models\Deduction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use stdClass;
use Yajra\DataTables\Facades\DataTables;

class DoctorEarningController extends Controller
{
    public function input_elements_creator()
    {
        $options = [];
        // $ad_windowws = AdsWindow::where('status', 1)->get();
        // if(isset($ad_windowws) && count($ad_windowws) > 0){
        //     foreach ($ad_windowws as $key => $ad_windoww) {
        //         $options[] = [
        //             "id" => $ad_windoww->id,
        //             "name" => $ad_windoww->name ." > ". $ad_windoww->dimensions,
        //         ];
        //     }
        // }

        $input_elements = array();

        // if (Gate::check('ads-management-add-ad_window_id')) {
            $input_element = array_push($input_elements, [
                "label" => "Select Ad Window",
                "element_type" => "dropdown",
                "name" => "ad_window_id",
                "options" => $options,
                "value_element" => "id",
                "select_element" => "ad_window_id",
                "label_element" => "name",
                "additional_ids" => ["id_1", "id_2"],
                "additional_classes" => ["class_1", "class_2"],
                "html_params" => ["required" => "required"],
            ]);
        // }
        // if (Gate::check('ads-management-add-name')) {
            $input_element = array_push($input_elements, [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Name",
                "name" => "name",
                "placeholder" => "Enter Name",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        // }
        // if (Gate::check('ads-management-add-redirect_url')) {
            $input_element = array_push($input_elements, [
                "element_type" => "input",
                "input_type" => "text",
                "label" => "Redirect URL",
                "name" => "redirect_url",
                "placeholder" => "Enter Redirect URL",
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ["required" => "required"],
            ]);
        // }
        // if (Gate::check('ads-management-add-image')) {
            $input_element = array_push($input_elements, [
                "element_type" => "image",
                "label" => "Image",
                "name" => "source",
                "include_asset_function" => 0,
                "additional_ids" => [],
                "additional_classes" => [],
                "html_params" => ['accept' => '.png,.jpg,.gif'],
            ]);
        // }

        $this->input_elements = $input_elements;
    }

    public $folder_name = 'doctor_earning'; // For view routes and file calling and saving
    public $module_name = 'Finance'; // For toast And page header
    public $input_elements;

    public function view(Request $request){
        // if (Gate::denies('ads-management-view')) {
        //     abort(403);
        // }
        if($request->isMethod('post')){
            foreach($request->sequence as $key => $id){
                $sequence = $key + 1;
                MainModel::find($id)->update(['sequence' => str_pad($sequence,2,"0",STR_PAD_LEFT)]);
            }
            return back();
        }else{
            $data['folder_name'] = $this->folder_name;
            $data['module_name'] = $this->module_name;

            $id=[40,39,35,47];
            // $data['results']= MainModel::select('value')->whereIn('id',$id)->get();

            $file = "admin.".$this->folder_name.".default_pricing";
            $instant= MainModel::where('group','Instant Consultation')->pluck('value');
            $instantId=MainModel::where('group','Instant Consultation')->first('id');
            $scheduled= MainModel::where('group','Scheduled')->pluck('value');
            $scheduledId= MainModel::where('group','Scheduled')->first('id');

            $instant= [
                'instantname'=>'Doctor Now',
                'instantfee' => isset($instant[0]) ? $instant[0] : null,
                'instantdisfees' => isset($instant[1]) ? $instant[1]: null,
                'instantmscommission' => isset($instant[4]) ? $instant[4]:null,
                'instantpenalty' => isset($instant[3]) ? $instant[3]:null
            ];
            $scheduled= [
                'scheduledname'=>'Scheduled',
                'scheduledfee' => isset($scheduled[0]) ? $scheduled[0] : null,
                'scheduleddisfees' => isset($scheduled[1]) ? $scheduled[1] : null,
                'scheduledmscommission' => isset($scheduled[2]) ? $scheduled[2] : null,
                'scheduledpenalty' => isset($scheduled[3]) ? $scheduled[3] : null
            ];


            return view($file,$data)->with(['result'=>$instant,'scheduled' => $scheduled,'instantId'=> $instantId,'scheduledId' => $scheduledId]);
        }
    }

    public function form(Request $request, $id = null){

        $validator = Validator::make($request->all(), [
            'set_price' => 'required|numeric',
            'discount_percantage' => 'required|numeric',
            'ms_commission' => 'required|numeric',
            'penalty_charges' => 'required|numeric'

        ]);

        if ($validator->fails()) {
            Helper::toast('error', 'Please enter numeric value.');
            return redirect()->back();
        }
        // $request->validate(MainModel::getValidationRules($id));
        $sequence = MainModel::count();
        $sequence = $sequence + 1;
        if($request->has('consult_type')){
            $data['consult_type']=$request->consult_type;
        }
        if($request->has('set_price')){
            $data['set_price'] = $request->set_price;
        }
        if($request->has('discount_percantage')){
            $data['discount_percantage'] = $request->discount_percantage;
        }
        if($request->has('ms_commission')){
            $data['ms_commission'] = $request->ms_commission;
        }
        if($request->has('penalty_charges')){

            $data['penalty_charges'] = $request->penalty_charges;
        }
        if($request->has('discount_price')){

            $data['discount_price'] = $request->discount_price;
        }
        if($data["set_price"] && $data["discount_percantage"] && $data["discount_price"] && $data["penalty_charges"] && $data['ms_commission']){
            $st=str_replace(' ','_',strtolower($data["consult_type"]));

            MainModel::insert(['key'=>$st.'_fee',
            'input_type' => 'numeric', 'value' => $data["set_price"],
            'title' => $data["consult_type"].' Fee', 'description' => $data["consult_type"].' Fee',
            'json_params' => null ,'created_at' => Carbon::now() ,'updated_at' => Carbon::now() , 'group' => $data["consult_type"]
            ]);
            MainModel::insert(['key'=>$st.'_discounted_fees',
            'input_type' => 'numeric', 'value' => $data["discount_price"],
            'title' => $data["consult_type"].' Discounted Fees', 'description' => $data["consult_type"].' Discounted Fees',
            'json_params' => null ,'created_at' => Carbon::now() ,'updated_at' => Carbon::now() , 'group' => $data["consult_type"]
            ]);
            MainModel::insert(['key'=>$st.'_discounted_percentage',
            'input_type' => 'numeric', 'value' => $data["discount_percantage"],
            'title' => $data["consult_type"].' Discounted Percentage', 'description' => $data["consult_type"].' Discounted Percentage',
            'json_params' => null ,'created_at' => Carbon::now() ,'updated_at' => Carbon::now() , 'group' => $data["consult_type"]
            ]);
            MainModel::insert(['key'=>$st.'_penalty_charges',
            'input_type' => 'numeric', 'value' => $data["penalty_charges"],
            'title' => $data["consult_type"].' Penalty Charges', 'description' => $data["consult_type"].' Penalty Charges',
            'json_params' => null ,'created_at' => Carbon::now() ,'updated_at' => Carbon::now() , 'group' => $data["consult_type"]
            ]);
            MainModel::insert(['key'=>$st.'_ms_commission',
            'input_type' => 'numeric', 'value' => $data["ms_commission"],
            'title' => $data["consult_type"].' MS Comission', 'description' => $data["consult_type"].' MS Comission',
            'json_params' => null ,'created_at' => Carbon::now() ,'updated_at' => Carbon::now() , 'group' => $data["consult_type"]
            ]);

        }

            Helper::toast('success',$this->module_name.' updated.');

        return redirect()->back();
    }

    public function add(Request $request){
        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
        $data['result'] = null;
        // $strings = ['string1', 'string2', 'string3'];

        $consulttype=MainModel::where('group','Instant Consultation')->orWhere('group','Scheduled')->orWhere('group','Clinic Visits')->distinct()->pluck('group')->toArray();
        $defineconsulttype=['Instant Consultation','Scheduled','Clinic Visits'];


        $uniqueconsulttype = array_merge(array_diff($consulttype, $defineconsulttype), array_diff($defineconsulttype, $consulttype));



        $settingsId= MainModel::where('group','Instant Consultation')->orWhere('group','MS Commission Fee')->pluck('id');


        $this->input_elements_creator();
        // if (Gate::denies('ads-management-add')) {
        //     abort(403);
        // }
        if($request->isMethod('post')){
            return $this->form($request);
        }else{
            return view( "admin.".$this->folder_name.".form",$data)->with(['settingsId'=>$settingsId,'consulttype'=>$uniqueconsulttype]);
        }
    }

    public function edit(Request $request, $id){

        $this->input_elements_creator();
        // $id = decrypt($id);
        // if (Gate::denies('ads-management-update')) {
        //     abort(403);
        // }

        $settingEditGroup=MainModel::where('id',$id)->pluck('group')->first();
        $settingID=MainModel::where('group',$settingEditGroup)->pluck('value');

        $data['consulttype']=MainModel::where('group','!=','Instant Consultation')->distinct()->pluck('group');

        $file = "admin.".$this->folder_name.".default_pricing_edit";

        $settingsId= MainModel::where('group','Instant Consultation')->orWhere('group','MS Commission Fee')->pluck('id');
        if($request->isMethod('post')){
            return $this->form($request,$id);
        }else{
            $data['folder_name'] = $this->folder_name;
            $data['module_name'] = $this->module_name;
            $data['page_header'] = "Edit ".$this->module_name;
            $data['result'] = MainModel::find($id);
            $input_elements = $this->input_elements;
            $data['include_status_radio'] = 0;
            $data['include_is_mobile_show_radio'] = 0;
            $data['include_is_web_show_radio'] = 0;
            if (Gate::check('ads-management-add-status')) {
                $data['include_status_radio'] = 1;
            }
            return view($file,$data)->with(compact('input_elements'))
            ->with(['settingID' => $settingID,'settingEditGroup' => $settingEditGroup]);
        }
    }

    public function delete(Request $request, $id){
        $group=MainModel::where('id',$id)->pluck('group')->first();
        MainModel::where('group',$group)->delete();
        Helper::toast('success','Pricing successfully deleted.');
        return back();
    }


    public function doctorEarningDetails(Request $request){
        $data['total_payouts'] = DoctorPayable::count();
        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
        return view( "admin.".$this->folder_name.".details",$data);
    }

    public function payoutTable(Request $request)
    {
        if($request->isMethod('post')){
            return back();
        }else{
            $data['folder_name'] = $this->folder_name;
            $data['module_name'] = $this->module_name;
            if ($request->ajax()) {
                $data = new DoctorPayable();
                $data = $data->whereHas('doctorEarning', function($data) {
                    $data = $data->with('doctor');
                });
                if ($request->has('search_doctor') && $request->search_doctor != '') {
                    $search_name = $request->search_doctor;
                    $data = $data->whereHas('doctorEarning', function($data) use ($search_name){
                        $data = $data->whereHas('doctor', function($data) use ($search_name){
                            $data = $data->where('name', 'like', '%'.$search_name.'%');
                        });
                    });
                }
                if ($request->has('searchDate') && $request->searchDate != '') {
                    $date = explode(' - ', $request->searchDate);
                    $start_date = date('Y-m-d', strtotime($date[0]));
                    $end_date = date('Y-m-d', strtotime($date[1]));
                    $data = $data->whereBetween('created_at', [Carbon::parse($start_date), Carbon::parse($end_date)]);
                }
                $data = $data->get();

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('date', function($row){
                        $date = date('d/m/Y', strtotime($row->created_at));
                        return $date;
                    })
                    ->addColumn('doctor_name', function($row){
                        $doctor = isset($row->doctorEarning->doctor->name) ? $row->doctorEarning->doctor->name : '-';
                        return $doctor;
                    })
                    ->addColumn('receiveable', function($row){
                        return number_format($row->total_receivable, 2);
                    })
                    ->addColumn('transaction_id', function($row){
                        return $row->bank_transaction_id;
                    })
                    ->addColumn('transaction_date', function($row){
                        return date('d/m/Y', strtotime($row->transaction_date));
                    })
                    ->addColumn('amount', function($row){
                        return number_format($row->amount_paid, 2);
                    })
                    ->addColumn('actionBy', function($row){
                        $btn = '';
                        $btn .= '<a class="btn btn-outline-primary btn-sm detail"><i class="icon-pencil"></i></a>'.'<a class="btn btn-outline-primary btn-sm detail"><i class="icon-trash"></i></a>';
                        return $btn;

                    })
                    ->rawColumns(['date','doctor_name','receiveable','transaction_id','transaction_date','amount','actionBy'])
                    ->make(true);
            }
            $data['total_payouts'] = DoctorPayable::count();
            $file = "admin.".$this->folder_name.".details";
            return view($file,$data);
        }
    }

    public function doctorEarning(Request $request){

        if($request->ajax()){
            $doctorEarning=DoctorEarning::with('doctor','doctorEarningDetails')
            ->whereHas('doctorEarningDetails',function ($query) {
                $query->where('status', 'paid')->orderBy('id', 'desc')->take(1);
            });
            if ($request->has('search_doctor_id') && $request->search_doctor_id != '') {
                $search_name = $request->search_doctor_id;
                $data =
                    $data = $doctorEarning->whereHas('doctor', function($data) use ($search_name){
                        $data = $data->where('name', 'like', '%'.$search_name.'%')->orWhere('id', 'like', '%'.$search_name.'%');
                    });
            }
            if ($request->has('searchEarningDate') && $request->searchEarningDate != '') {
                $date = explode(' - ', $request->searchEarningDate);
                $start_date = $date[0];
                $end_date = $date[1];
                $data = $doctorEarning->whereBetween('created_at', [ Carbon::parse($start_date),  Carbon::parse($end_date)]);

            }
            $doctorEarning = $doctorEarning->get();
            return DataTables::of($doctorEarning)
                    ->addIndexColumn()
                    ->addColumn('paid_amount', function($row){
                        return $row->total_payable - $row->remaining_payable;
                    })
                    ->addColumn('last_payout', function($row){
                        return \Carbon\Carbon::parse($row->doctorEarningDetails[0]->created_at)->format('d/m/y');

                    })
                    ->addColumn('actionBy', function($row){
                        $btn = '';
                        $btn .= '<a href="' . route('doctor-earning-doctor-earning-view',$row->id) . '" class="btn btn-outline-primary btn-sm detail"><i class="icon-eye"></i></a>';
                        return $btn;

                    })
                    ->rawColumns(['actionBy'])
                    ->make(true);
        }
    }

    public function doctorEarningView(Request $request,$id){
        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;

        $doctorName=DoctorEarning::find($id)->with(['doctor' => function($query){
            $query->with('doctorBankDetails');
        }])->first();

        $doctorTotalPayables=DoctorEarning::find($id)->pluck('total_payable')->first();

        $doctorTotalEarning=DoctorEarning::find($id)->with(['doctorPayables' => function($query){
            $query->where('status','paid');
        }])->first();

        $totalEarning=null;
        foreach($doctorTotalEarning->doctorPayables as $value){
            $totalEarning += $value->amount_paid;
        }

        $doctorAmountPaid=DoctorEarning::find($id)->pluck('remaining_payable')->first();

        $doctorThisMonthEarning=DoctorEarning::find($id)->with(['doctorPayables' => function($query){
            $query->where('status','paid')->whereMonth('created_at', Carbon::now()->month);
        }])->first();

        $thisMonthEarning=null;
        foreach($doctorThisMonthEarning->doctorPayables as $value){
            $thisMonthEarning += $value->amount_paid;
        }

        if($request->ajax()){
            $doctorearningdetails=DoctorEarningDetail::where('doctor_earning_id',$id)->with(['appointment' => function ($query){
                $query->with('user');
            }]);

            if ($request->has('search_appt_id') && $request->search_appt_id != '') {
                $search_name = $request->search_appt_id;
                $data =
                    $data = $doctorearningdetails->whereHas('appointment', function($data) use ($search_name){
                        $data = $data->where('id', 'like', '%'.$search_name.'%');
                    });
            }
            if ($request->has('searchApptDate') && $request->searchApptDate != '') {
                $date = explode(' - ', $request->searchApptDate);
                $start_date = date('Y-m-d', strtotime($date[0]));
                $end_date = date('Y-m-d', strtotime($date[1]));
                $data = $doctorearningdetails
                ->whereHas('appointment', function($data) use ($request,$start_date,$end_date){
                    $data = $data->whereBetween('created_at', [Carbon::parse($start_date),Carbon::parse($end_date)]);
                });

            }
            if ($request->has('paymentmethod') && $request->paymentmethod != '') {
                $payment_method=$request->paymentmethod;
                $data= $doctorearningdetails->whereHas('appointment', function($data) use ($payment_method){
                    $data = $data->where('booked_via_subscription', $payment_method);
                });

            }
            if ($request->has('consulttype') && $request->consulttype != '') {
                $consulttype=$request->consulttype;
                $data= $doctorearningdetails->whereHas('appointment', function($data) use ($consulttype){
                    $data = $data->where('type', $consulttype);
                });

            }
        $doctorearningdetails = $doctorearningdetails->get();
        return DataTables::of($doctorearningdetails)
                    ->addIndexColumn()
                    ->addColumn('appointment_created_at', function($row){
                        return \Carbon\Carbon::parse($row->appointment->created_at)->format('d/m/y h:i A');

                    })
                    ->addColumn('cancellation_fee', function($row){
                        return $row->appointment->cancellation_fee!=null && $row->appointment->cancellation_fee!="NA"? $row->appointment->cancellation_fee : "-";

                    })
                    ->addColumn('actionBy', function($row){
                        $btn = '';
                        $btn .= '<button type="button" class="btn btn-primary" onclick="doctorEarningInvoice('.$row->appointment->id.')"><i class="icon-eye"></i></button>';
                        return $btn;

                    })
                    ->rawColumns(['actionBy'])
                    ->make(true);

        }
        return view( "admin.".$this->folder_name.".doctor_earning_details",$data)->with(['doctorName'=>$doctorName,'doctorTotalPayables'
        => $doctorTotalPayables,'doctorTotalEarning' => $totalEarning, 'doctorAmountPaid' => $doctorAmountPaid,
        'doctorThisMonthEarning' => $thisMonthEarning
        ]);


    }

    public function doctorEarningInvoice(Request $request,$id){
        $appointment=Appointment::find($id);
        return $appointment;

    }

    public function deductionLists(Request $request){
        $data['total_payouts'] = Deduction::count();
        // $data['folder_name'] = $this->folder_name;
        // $data['module_name'] = $this->module_name;
        if($request->isMethod('post')){
            return back();
        }else{
            $data['folder_name'] = $this->folder_name;
            $data['module_name'] = $this->module_name;

            if ($request->ajax()) {
                $data = new Deduction();
                $data = $data->with(['doctorEarning' => function($data) {
                    $data = $data->with('doctor');
                }])->where('status', 'paid');
                if ($request->has('search_doctor') && $request->search_doctor != '') {
                    $search_name = $request->search_doctor;
                    $data = $data->whereHas('doctorEarning', function($data) use ($search_name){
                        $data = $data->whereHas('doctor', function($data) use ($search_name){
                            $data = $data->where('name', 'like', '%'.$search_name.'%');
                        });
                    });
                }
                if ($request->has('searchDate') && $request->searchDate != '') {
                    $date = explode(' - ', $request->searchDate);
                    $start_date = date('Y-m-d', strtotime($date[0]));
                    $end_date = date('Y-m-d', strtotime($date[1]));
                    $data = $data->whereBetween('created_at', [Carbon::parse($start_date), Carbon::parse($end_date)]);
                }
                $data = $data->get();

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('date', function($row){
                        $date = date('d/m/Y', strtotime($row->created_at));
                        return $date;
                    })
                    ->addColumn('doctor_name', function($row){
                        $doctor = isset($row->doctorEarning->doctor->name) ? $row->doctorEarning->doctor->name : '-';
                        return $doctor;
                    })
                    ->addColumn('receiveable', function($row){
                        return number_format($row->total_receivable, 2);
                    })
                    ->addColumn('actionBy', function($row){
                        $btn = '';
                        $btn .= '<a class="btn btn-outline-primary btn-sm detail"><i class="icon-pencil"></i></a>'.'<a class="btn btn-outline-primary btn-sm detail"><i class="icon-trash"></i></a>';
                        return $btn;

                    })
                    ->rawColumns(['date','doctor_name','receiveable','actionBy'])
                    ->make(true);
            }
        return view( "admin.".$this->folder_name.".deduction_list",$data);
    }

}

    public function addScreen(Request $request)
    {
        if($request->has('deduction')){
            $data['module_name'] = 'New Deduction';
            $data['deduction'] = true;
            return view('admin.doctor_earning.add_deduction', $data);
        }
        if($request->has('transaction')){
            $data['module_name'] = 'New Transaction';
            $data['transaction'] = true;
            return view('admin.doctor_earning.add', $data);
        }
    }

    public function searchDoctors(Request $request)
    {
        $constant = new Constant();
        if($request->has('search') && $request->search != ''){
            $doctors = User::where('role_id', $constant->DOCTOR_ROLE_ID)
            ->where('name', 'LIKE', "%$request->search%")
            ->get();
        }
        return json_encode($doctors);
    }

    public function getTransactionTable($id, Request $request)
    {
        $constant = new Constant();
        if($request->isMethod('post')){
            return back();
        }else{
            $data['folder_name'] = $this->folder_name;
            $data['module_name'] = $this->module_name;

            if ($request->ajax()) {
                $doctorEarning = DoctorEarning::where('doctor_id', $id)
                ->where('status', 'unpaid')->first();
                $doctorID = '';
                if(isset($doctorEarning)){
                    if($doctorEarning->parent_id == 0){
                        $doctorID = $doctorEarning->id;
                    }
                    else{
                        $doctorID = $doctorEarning->parent_id;
                    }
                }
                $appointments = DoctorEarningDetail::whereHas('appointment', function($appointments){
                    $appointments = $appointments->where('progress', (new Constant)->APPOINTMENT_STATUS_COMPLETED);
                })->where('doctor_earning_id', $doctorID);
                if($request->has('searchDate') && $request->searchDate != ''){
                    $date = explode(' - ', $request->searchDate);
                    $start_date = date('Y-m-d', strtotime($date[0]));
                    $end_date = date('Y-m-d', strtotime($date[1]));
                    $appointments = $appointments->whereBetween('created_at', [Carbon::parse($start_date), Carbon::parse($end_date)]);
                }
                $appointments = $appointments->with('appointment')->get();
                // dd($appointments[0]->appointment);
                return DataTables::of($appointments)
                ->addIndexColumn()
                ->addColumn('date', function($row){
                    return date('d/m/Y', strtotime($row->appointment->created_at));
                })
                ->addColumn('appt_id', function($row){
                    return $row->appointment->id;
                })
                ->addColumn('consultation_fee', function($row){
                    return isset($row->appointment->consultation_fee) ? $row->appointment->consultation_fee : 0;
                })
                ->rawColumns(['date', 'appt_id', 'consultation_fee'])
                ->make(true);
                return ['earning_id', $doctorID];
            }
        }
    }

    public function addTransaction(Request $request)
    {
        $doctorPayable = DoctorPayable::create([
            'doctor_earning_id' => $request->doctor_earning_id,
            'total_receivable' => $request->total_receivables,
            'amount_paid' => $request->amount,
            'progress' => 'completed',
            'status' => 'paid',
            'bank_transaction_id' => $request->bank_transaction_id,
            'transaction_date' => $request->date_of_transaction
        ]);
        if($doctorPayable){
            $appointments = explode(',',$request->checkAppointments);
            if(count($appointments) > 1){
                foreach ($appointments as $key => $value) {
                    AppointmentPayable::create([
                        'doctor_payable_id' => $doctorPayable->id,
                        'appointment_id' => $value,
                    ]);
                }
            }
            else{
                AppointmentPayable::create([
                    'doctor_payable_id' => $doctorPayable->id,
                    'appointment_id' => $request->checkAppointments,
                ]);
            }
            DoctorEarningDetail::where('doctor_earning_id', $request->doctor_earning_id)
            ->whereIn('appointment_id', $appointments)->update([
                'status' => 'paid'
            ]);
            $doctorEarning = DoctorEarning::where('id',$request->doctor_earning_id)->where('status', 'unpaid')->first();
            if($doctorEarning){
                if($doctorEarning->remaining_payable == $request->amount){
                    $doctorEarning->remaining_payable = $doctorEarning->remaining_payable - $request->amount;
                    $doctorEarning->status = 'paid';
                    $doctorEarning->save();
                }
                else{
                    $doctorEarning->remaining_payable = $doctorEarning->remaining_payable - $request->amount;
                    $doctorEarning->save();
                }
            }
            else{
                $doctorEarning = DoctorEarning::where('parent_id',$request->doctor_earning_id)
                ->where('status', 'unpaid')->first();
                if($doctorEarning->remaining_payable == $request->amount){
                    $doctorEarning->remaining_payable = $doctorEarning->remaining_payable - $request->amount;
                    $doctorEarning->status = 'paid';
                    $doctorEarning->save();
                }
                else{
                    $doctorEarning->remaining_payable = $doctorEarning->remaining_payable - $request->amount;
                    $doctorEarning->save();
                }
            }
            return redirect()->route('doctor-earning-details');
        }
        return redirect()->back();
    }

    public function getDeductionTable($id, Request $request)
    {
        $constant = new Constant();
        if($request->isMethod('post')){
            return back();
        }else{
            $data['folder_name'] = $this->folder_name;
            $data['module_name'] = $this->module_name;

            if ($request->ajax()) {
                $doctorEarning = DoctorEarning::where('doctor_id', $id)
                ->where('status', 'unpaid')->first();
                $doctorID = '';
                if(isset($doctorEarning)){
                    if($doctorEarning->parent_id == 0){
                        $doctorID = $doctorEarning->id;
                    }
                    else{
                        $doctorID = $doctorEarning->parent_id;
                    }
                }
                $appointments = DoctorEarningDetail::whereHas('appointment', function($appointments) use($constant){
                    $appointments = $appointments->where('progress', $constant->APPOINTMENT_STATUS_CANCELLED)
                    ->orWhere('progress', $constant->APPOINTMENT_STATUS_CANCELLED_BY_DOCTOR);
                })
                ->whereDoesntHave('appointment_deduction')
                ->where('doctor_earning_id', $doctorID);
                if($request->has('searchDate') && $request->searchDate != ''){
                    $date = explode(' - ', $request->searchDate);
                    $start_date = date('Y-m-d', strtotime($date[0]));
                    $end_date = date('Y-m-d', strtotime($date[1]));
                    $appointments = $appointments->whereBetween('created_at', [Carbon::parse($start_date), Carbon::parse($end_date)]);
                }
                $appointments = $appointments->with('appointment')->get();
                // dd($appointments[0]->appointment);
                return DataTables::of($appointments)
                ->addIndexColumn()
                ->addColumn('date', function($row){
                    return date('d/m/Y', strtotime($row->appointment->created_at));
                })
                ->addColumn('appt_id', function($row){
                    return $row->appointment->id;
                })
                ->addColumn('consultation_fee', function($row){
                    return isset($row->appointment->consultation_fee) ? $row->appointment->consultation_fee : 0;
                })
                ->rawColumns(['date', 'appt_id', 'consultation_fee'])
                ->make(true);
                return ['earning_id', $doctorID];
            }
        }
    }


    public function addDeduction(Request $request)
    {
        $doctorPayable = Deduction::create([
            'doctor_earning_id' => $request->doctor_earning_id,
            'total_receivable' => $request->total_receivables,
            'amount_paid' => $request->amount,
            'progress' => 'cancel',
            'status' => 'paid',
        ]);
        if($doctorPayable){
            $appointments = explode(',',$request->checkAppointments);
            if(count($appointments) > 1){
                foreach ($appointments as $key => $value) {
                    AppointmentDeduction::create([
                        'deduction_id' => $doctorPayable->id,
                        'appointment_id' => $value,
                    ]);
                }
            }
            else{
                AppointmentDeduction::create([
                    'deduction_id' => $doctorPayable->id,
                    'appointment_id' => $request->checkAppointments,
                ]);
            }
            DoctorEarningDetail::where('doctor_earning_id', $request->doctor_earning_id)
            ->whereIn('appointment_id', $appointments)->update([
                'status' => 'paid'
            ]);
            $doctorEarning = DoctorEarning::where('id',$request->doctor_earning_id)->where('status', 'unpaid')->first();
            if($doctorEarning){
                if($doctorEarning->remaining_payable == $request->amount){
                    $doctorEarning->remaining_payable = $doctorEarning->remaining_payable - $request->amount;
                    $doctorEarning->status = 'paid';
                    $doctorEarning->save();
                }
                else{
                    $doctorEarning->remaining_payable = $doctorEarning->remaining_payable - $request->amount;
                    $doctorEarning->save();
                }
            }
            else{
                $doctorEarning = DoctorEarning::where('parent_id',$request->doctor_earning_id)
                ->where('status', 'unpaid')->first();
                if($doctorEarning->remaining_payable == $request->amount){
                    $doctorEarning->remaining_payable = $doctorEarning->remaining_payable - $request->amount;
                    $doctorEarning->status = 'paid';
                    $doctorEarning->save();
                }
                else{
                    $doctorEarning->remaining_payable = $doctorEarning->remaining_payable - $request->amount;
                    $doctorEarning->save();
                }
            }
            return redirect()->route('doctors-deduction-list');
        }
        return redirect()->back();
    }
}
