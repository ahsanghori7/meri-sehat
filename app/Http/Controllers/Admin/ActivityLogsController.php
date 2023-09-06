<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogActivity;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Common\{Helper, EmailHelper};
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class ActivityLogsController extends Controller
{
    public $folder_name = 'activity-logs'; // For view routes and file calling and saving
    public $module_name = 'Activity Logs'; // For toast And page header

    public function index(Request $request){
        if (Gate::denies('activity-logs')) {
            abort(403);
        }
        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
//        $data['result'] = LogActivity::all();
        $file = "admin.".$this->folder_name.".index";
        return view($file,$data);
    }

    public function view(Request $request, $id=null){
        if (Gate::denies('activity-logs')) {
            abort(403);
        }
        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $this->module_name;
        $data['result'] = LogActivity::where('id',$id)->with(['user'])->first();
        $file = "admin.".$this->folder_name.".view";
        return view($file,$data);
    }

    public function delete(Request $request){
        if (Gate::denies('activity-logs')) {
            abort(403);
        }
        return true;
    }

    public function fetch(Request $request){
        if (Gate::denies('activity-logs')) {
            abort(403);
        }
        $query = LogActivity::with(['user']);
        if(isset($request->name)) {
            $request_name = $request->name;
            $users = User::where('name', 'like', '%'.$request_name.'%')->get()->pluck('id');
            $query = $query->whereIn('user_id', $users);
        }
        if(isset($request->email)) {
            $request_email = $request->email;
            $users = User::where('email', 'like', '%'.$request_email.'%')->get()->pluck('id');
            $query = $query->whereIn('user_id', $users);
        }
        if(isset($request->module)) {
            $query = $query->where('module', 'like', '%'.$request->module.'%');
        }
        if(isset($request->event)) {
            $query = $query->where('event', $request->event);
        }
        return Datatables::of($query)->make(true);
    }

}
