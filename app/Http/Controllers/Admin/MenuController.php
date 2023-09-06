<?php

namespace App\Http\Controllers\Admin;

use App\Http\Common\Constant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Menu as MainModel,
    Settings,
};
use App\Http\Common\Helper;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Gate;

class MenuController extends Controller
{
    public $folder_name = 'menu'; // For view routes and file calling and saving
    public $module_name = 'Web Header Menu Link'; // For toast And page header

    public function view(Request $request, $lang_id){
        if (Gate::denies('menu-management-view')) {
            abort(403);
        }
        $lang_id = decrypt($lang_id);
        if($request->isMethod('post')){
            foreach($request->sequence as $key => $id){
                $sequence = $key + 1;
                MainModel::find($id)->where('lang_id',$lang_id)->update(['sequence' => str_pad($sequence,2,"0",STR_PAD_LEFT)]);
            }
            return back();
        }else{
            $data['folder_name'] = $this->folder_name;
            $data['module_name'] = $this->module_name;
            $file = "admin.".$this->folder_name.".view";
            return view($file,$data);
        }
    }

    public function fetch(Request $request, $lang_id){
        $lang_id = decrypt($lang_id);
        $query = MainModel::where('lang_id',$lang_id)->with(['language']);
        if(isset($request->status)) {
            $query = $query->where('status', $request->status);
        }
        return DataTables::of($query)->make(true);
    }

    public function form(Request $request, $id = null, $lang_id){
        Settings::where('key', Constant::CACHE_SIGNATURE)->updateOrCreate([ 'key' => Constant::CACHE_SIGNATURE ], [ 'value' => rand(100000, 999999)]);
        $data = [
            'lang_id' => $lang_id,
            'name' => $request->name,
            'type' => $request->type,
            'link' => $request->link,
            'target' => $request->target,
            'col_width' => $request->col_width,
            'parent_id' => $request->parent,
            'status' => $request->status ?? 1,
        ];
        if($request->hasFile('image')){
            $folder=$this->folder_name;
            $key="image";
            $data['image'] = $this->S3UploaderSpecificKey($key,$request,$folder);
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
        return redirect()->route($this->folder_name.'-view',['lang_id' => encrypt($lang_id)]);
    }

    public function add(Request $request, $lang_id){
        if (Gate::denies('menu-management-add')) {
            abort(403);
        }

//        Gate::authorize('menu-management-add');
        $lang_id = decrypt($lang_id);
        if($request->isMethod('post')){
            return $this->form($request,null,$lang_id);
        }else{
            $data['page_header'] = "Add ".$this->module_name;
            $data['result'] = null;
            $data['include_status_radio'] = 1;
            $file = "admin.".$this->folder_name.".form";
            return view($file,$data);
        }
    }

    public function edit(Request $request, $id, $lang_id){
        if (Gate::denies('menu-management-update')) {
            abort(403);
        }

        $lang_id = decrypt($lang_id);
        $id = decrypt($id);
        if($request->isMethod('post')){
            Gate::authorize('menu-management-update');
            return $this->form($request,$id,$lang_id);
        }else{
            Gate::authorize('menu-management-view');
            $data['page_header'] = "Edit ".$this->module_name;
            $data['result'] = MainModel::find($id);
            $data['include_status_radio'] = 1;
            $file = "admin.".$this->folder_name.".form";
            return view($file,$data);
        }
    }

    public function getParentsByType(Request $request){
        $lang_id = $request->lang_id;
        $menu_id = $request->menu_id;
        $general_response = ['status' => true];
        $type = $request->type;
        try{
            if($type == 'nav-link-with-dropdown'){
                $data['parents'] = MainModel::where('type','nav-link')->where('lang_id',$lang_id)->get();
            }else if($type == 'dropdown-header') {
                $data['parents'] = MainModel::where('type','nav-link-with-dropdown')->where('lang_id',$lang_id)->get();
            }else if($type == 'dropdown-nav-link') {
                $data['parents'] = MainModel::where('type','dropdown-header')->where('lang_id',$lang_id)->get();
            }
            $data['result'] = MainModel::where('id', $menu_id)->first();
            $file = "admin.".$this->folder_name.".parents_dropdown";
            $html = view($file,$data)->render();
            $general_response['html'] = $html;
            return $general_response;
        }catch(\Exception $e){
            return [
                'status' => false,
                'message' => 'Something went wrong'
            ];
        }
    }
}
