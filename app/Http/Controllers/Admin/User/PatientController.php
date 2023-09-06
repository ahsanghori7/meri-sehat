<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Common\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Appointment,
    City,
    MedicalRecord,
    Role,
    Transaction,
    User as MainModel,
    UserFamilyMember,
    UserSubscription};
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;

class PatientController extends Controller
{
    public $folder_name = 'patient_management'; // For view routes and file calling and saving
    public $module_name = 'Patients'; // For toast And page heade

    public function view(){
        if (Gate::denies('patients-management-view')) {
            abort(403);
        }
        // $data['roles'] = Role::where('id', MainModel::CUSTOMER_ROLE)->get();
        // $file = "admin.".$this->folder_name.".view";
        $data['locations'] =  City::where('lang_id', 1)->where('status','1')->get();
        $file = "admin.".$this->folder_name.".view";
        return view($file,$data);
    }

    public function fetch(Request $request){
        $query = MainModel::where('role_id', MainModel::CUSTOMER_ROLE)->with(['role','city']);
        if( $request->name != '') {
            $query = $query->where('name', 'like', '%'.$request->name.'%');
        }
        if( $request->phone != '') {
            $query = $query->where('phone', 'like', '%'.$request->phone.'%');
        }
        if( $request->gender != '') {
            $query = $query->where('gender', $request->gender);
        }
        if($request->location != '') {
            $request_location = $request->location;
            $query = $query->whereHas('city', function ($query) use ($request_location) {
                $query->where('cities.id', $request_location);
            });
        }
        if( $request->user_type != '' ) {
            $query = $query->where('is_subscribed', $request->user_type);
        }
        if( $request->status != '' ) {
            $query = $query->where('status', $request->status);
        }
        return DataTables::of($query)->make(true);
    }

    public function form(Request $request, $id = null){
//        $validate = $request->validate([
//            'name'=>'required',
//            'gender'=>'required',
//            'city_id'=>'required',
//            'phone'=>'required',
//            'email'=>'required',
//            'birth_date'=>'required',
//            'height'=>'required',
//            'weight'=>'required',
//            'status'=>'required',
//        ]);
//        dd($request->all());

        $height = $request->height_feet."' ".$request->height_inch.'"';
        $data = [
            'name' => $request->name,
            'gender' => $request->gender,
            'city_id' => $request->city,
            'phone' => $request->phone,
            'email' => $request->email,
            'birth_date' => $request->birth_date,
            'blood_group' => $request->blood_group,
            'height' => $height,
            'weight' => $request->weight,
            'status' => $request->status ?? 1,

        ];
        if($request->hasFile('image')){
            if($request->user_id==3)
            {
                $folder="doctor";
            }else{
                $folder="user";
            }
            $key="image";
            $data['image'] = $this->S3UploaderSpecificKey($key,$request,$folder);
        }
        if($id){
            if(MainModel::find($id)->update($data)){
                Helper::toast('success',$this->module_name.' Updated.');
            }
        }else{
            Helper::toast('error',$this->module_name.' not updated.');
        }
        // return redirect()->route($this->folder_name.'-view');
        return redirect()->back();

    }

    public function add(Request $request){
        if (Gate::denies('patients-management-add')) {
            abort(403);
        }
        if($request->isMethod('post')){
            return $this->form($request);
        }else{
            $data['page_header'] = "Add ".$this->module_name;
            $data['result'] = null;
            $data['specialities'] = Speciality::all();
            $data['cities'] = City::all();
            $data['include_status_radio'] = 1;
            $file = "admin.".$this->folder_name.".add";
            return view($file,$data);
        }
    }

    public function edit(Request $request, $id){
        if (Gate::denies('patients-management-update')) {
            abort(403);
        }
        $id = decrypt($id);
        if($request->isMethod('post')){
            return $this->form($request,$id);
        }else{
            $data['page_header'] = "Edit ".$this->module_name;
            $data['cities'] = City::where(['status' => true, 'lang_id' => 1])->get();
            $data['result'] = MainModel::where('id',$id)->first();
            $data['include_status_radio'] = 1;
            $file = "admin.".$this->folder_name.".form";
            return view($file,$data);
        }
    }

    public function delete(Request $request){
        if (Gate::denies('patients-management-delete')) {
            abort(403);
        }
        return true;
    }

    public function viewProfile(Request $request,$id){

        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
        $data['cities'] = City::where(['status' => true, 'lang_id' => 1])->get();
        $data['result'] = MainModel::where('id',$id)->first();
        $data['include_status_radio'] = 1;
        $file = "admin.".$this->folder_name.".profile";
        return view($file,$data);
    }

    public function viewPatientFamily(Request $request,$id){

        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
        $data['cities'] = City::where(['status' => true, 'lang_id' => 1])->get();
        $data['result'] = UserFamilyMember::where('id',$request->family_id)->where('user_id',$id)->first();
        $data['include_status_radio'] = 1;
        $file = "admin.".$this->folder_name.".family";
        return view($file,$data);
    }

    public function editPatientFamily(Request $request,$id){

        $height = $request->height_feet."' ".$request->height_inch.'"';
        $data = [
            'user_id' => $id,
            'name' => $request->name,
            'gender' => $request->gender,
            'relationship' => $request->relationship,
            'phone' => $request->phone,
            'email' => $request->email,
            'birth_date' => $request->birth_date,
            'blood_group' => $request->blood_group,
            'height' => $height,
            'weight' => $request->weight,
            'status' => 1,

        ];
        if($request->hasFile('image')){
            if($request->user_id==3)
            {
                $folder="doctor";
            }else{
                $folder="user";
            }
            $key="image";
            $data['image'] = $this->S3UploaderSpecificKey($key,$request,$folder);
        }
        if($id){
            if(UserFamilyMember::where('id', $request->user_id)->where('user_id', $id)->update($data)){
                Helper::toast('success',$this->module_name.' family Updated.');
            }
        }else{
            Helper::toast('error',$this->module_name.' not updated.');
        }
        return redirect()->back();
    }

    public function viewAppointments(Request $request,$id){

        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
        $data['cities'] = City::where(['status' => true, 'lang_id' => 1])->get();
        $data['result'] = Appointment::where('user_id',$id)->get();
        $data['include_status_radio'] = 1;
        $file = "admin.".$this->folder_name.".appointments";
        return view($file,$data);
    }

    public function viewReports(Request $request,$id){

        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
        $data['result'] = MedicalRecord::with('medicalRecordFiles')->where('user_id',$id)->get();
        $data['include_status_radio'] = 1;
        $file = "admin.".$this->folder_name.".reports";
        return view($file,$data);
    }

    public function viewLabHistory(Request $request,$id){

        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
        $data['cities'] = City::where(['status' => true, 'lang_id' => 1])->get();
        $data['result'] = MainModel::where('id',$id)->first();
        $data['include_status_radio'] = 1;
        $file = "admin.".$this->folder_name.".labhistory";
        return view($file,$data);
    }

    public function viewVitalHistory(Request $request,$id){

        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
        $data['result'] = MainModel::where('id',$id)->first();
        $data['include_status_radio'] = 1;
        $file = "admin.".$this->folder_name.".vitalhistory";
        return view($file,$data);
    }

    public function viewSubscriptions(Request $request,$id){

        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
        $data['result'] = MainModel::where('id',$id)->first();
        $data['include_status_radio'] = 1;
        $file = "admin.".$this->folder_name.".subscriptions";
        return view($file,$data);
    }

    public function deleteSubscription(Request $request, $patient_id){
        UserSubscription::where('user_id',$patient_id)->where('id', $request->recordId)->delete();
        UserSubscription::where('id',$patient_id)->update([
            'is_subscribed' => 0
        ]);
        Helper::toast('success','Subscription has been cancelled.');
        return back();
    }

    public function viewWallet(Request $request,$id){

        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
        $data['result'] = MainModel::where('id',$id)->first();
        $data['include_status_radio'] = 1;
        $file = "admin.".$this->folder_name.".wallet";
        return view($file,$data);
    }

    public function viewTransaction(Request $request,$id){
        $recordId = decrypt($request->recordId);
        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
        $data['result'] = Transaction::where('user_id', $id)->where('id',$recordId)->first();
        $file = "admin.".$this->folder_name.".transaction_view";
        return view($file,$data);
    }

}
