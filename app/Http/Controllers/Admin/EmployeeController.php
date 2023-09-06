<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User as MainModel;
use App\Models\OutletBoxQty;
use App\Models\SaudiCity;
use App\Models\InventoryHistory;
use App\Models\Box;
use App\Models\Role;
use App\Models\Outlet;
use App\Models\Region;
use App\Models\UserRoles;
use Auth;
use Illuminate\Support\Facades\Gate;
use Yajra\Datatables\Datatables;
use App\Http\Common\Helper;
use Hash;

class EmployeeController extends Controller
{
    public $folder_name = 'employee'; // For view routes and file calling and saving
    public $module_name = 'Employee'; // For toast And page header


    public function view(){
        if (Gate::denies('patients-management-view')) {
            abort(403);
        }
        $data['roles'] = Role::where('id','<>',MainModel::CUSTOMER_ROLE)->where('id','<>',MainModel::DEMO_ROLE)->get();
        $file = "admin.".$this->folder_name.".view";
        return view($file,$data);
    }

    public function fetch(Request $request){
        $query = MainModel::
        // where('role_id', '!=',MainModel::SUPERADMIN_ROLE)
        // ->where('role_id', '!=', MainModel::SUPERADMIN_ROLE)
        where('role_id', '!=' ,MainModel::DEMO_ROLE)
        ->where('role_id', '!=' ,MainModel::CUSTOMER_ROLE)
        ->with(['role']);
        if( $request->status != '' ) {
            $query = $query->where('status',$request->status);
        }
        return Datatables::of($query)->make(true);
    }

    public function form(Request $request, $id = null){
        $request->validate(MainModel::getRules($id));
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role_id' => $request->role,
            'status' => $request->status ?? 1,
        ];
        if($request->password){
            $data['password'] = Hash::make($request->password);
        }
        if($request->role == MainModel::PICKUP_DISPATCHER_ROLE){
            $data['city_id'] = $request->city;
        }
        if($request->role == MainModel::DELIVERY_TRANSPORTER_ROLE){
            $data['region_id'] = $request->region;
        }
        if($request->role == MainModel::OUTLET_MANAGER_ROLE){
            $data['outlet_id'] = $request->outlet;
        }
        if($request->hasFile('image')){
            $image = $request->image->store($this->folder_name,'public');
            $data['image'] = $image;
        }

        if($id){
            if(MainModel::find($id)->update($data)){
                $createOrUpdate['where']['user_id'] = $id;
                $createOrUpdate['data']['role_id'] = $request->role;
                UserRoles::updateOrCreate($createOrUpdate['where'],$createOrUpdate['data']);
                Helper::toast('success',$this->module_name.' Updated.');
            }
        }else{
            if($created_data = MainModel::create($data)){
                $createOrUpdate['where']['user_id'] = $created_data->id;
                $createOrUpdate['data']['role_id'] = $request->role;
                UserRoles::updateOrCreate($createOrUpdate['where'],$createOrUpdate['data']);
                Helper::toast('success',$this->module_name.' created.');
            }
        }


        return redirect()->route($this->folder_name.'-view');
    }

    public function add(Request $request){
        if (Gate::denies('patients-management-add')) {
            abort(403);
        }
        if($request->isMethod('post')){
            return $this->form($request);
        }else{
            $data['page_header'] = "Add ".$this->module_name;
            $data['roles'] = Role::where('id','!=','3')->get();
            $data['outlets'] = Outlet::where('status','1')->get();
            $data['regions'] = Region::where('status','1')->get();
            $data['cities'] = SaudiCity::where('status','1')->get();
            $data['result'] = null;
            $file = "admin.".$this->folder_name.".form";
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
            $data['roles'] = Role::where('id','!=','3')->get();
            $data['result'] = MainModel::find($id);
            $data['outlets'] = Outlet::where('status','1')->get();
            $data['regions'] = Region::where('status','1')->get();
            $data['cities'] = SaudiCity::where('status','1')->get();
            $file = "admin.".$this->folder_name.".form";
            return view($file,$data);
        }
    }

}
