<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;
use DB;
use Illuminate\Support\Facades\Gate;
use Str;
use App\Http\Common\Helper;
use App\User;


class RightsController extends Controller
{
    public function show_roles()
    {
        if (Gate::denies('rights-management-view')) {
            abort(403);
        }
        $roles=Role::get();
        return view('admin.rights.roles_list',compact('roles'));
    }
    public function add_role(){
        $role=null;
        $selected_permission = array();
        $permissions=Permission::with('child')->whereNull('parent_id')->orderBy('name','ASC')->get()->all();
        return view('admin.rights.add_roles',compact('permissions','selected_permission'))->with('role',$role);
    }
    public function create_role(Request $request)
    {
        if (Gate::denies('rights-management-add')) {
            abort(403);
        }
        $data=request()->validate(['name'=>'required|max:100',]);
        try {
            $role=Role::create(['name'=>$data['name'] , 'slug'=>Str::slug($data['name']), 'translation' => 'en']);
            if($request->permissions != null &&  count($request->input('permissions')) > 0){
                foreach ($request->input('permissions') as $perm_id) {
                    DB::table('role_permissions')->insert(['role_id'=>$role->id,'permission_id'=>$perm_id]);
                }
                // return back();
            }
            Helper::toast('success','Role created.');
            return redirect()->route('roles.show');
        } catch (\Throwable $th) {
            Helper::toast('error','Role creation failed.');
            return back();
            // return redirect()->route('roles.show');
        }

    }

    public function update_role(Request $request,Role $role)
    {
        if ($role->slug == 'superadmin') {
            abort(403, 'This role '.$role->name.' permission cannot be edit.');
        }
        if (Gate::denies('rights-management-update')) {
            abort(403);
        }
        $data=request()->validate(['name'=>'required|max:100']);
        $role->update(['name'=>$data['name']]);
        DB::table('role_permissions')->where('role_id',$role->id)->delete();
        if($request->permissions != null){
            foreach ($request->input('permissions') as $perm_id) {
                DB::table('role_permissions')->insert(['role_id'=>$role->id,'permission_id'=>$perm_id]);
            }
        }
        Helper::toast('success','Role updated.');
        return back();
//        return redirect()->route('roles.show');

    }
    public function edit_role(Role $role)
    {
        if ($role->slug == 'superadmin') {
            abort(403, 'This role '.$role->name.' permission cannot be edit.');
        }
        if (Gate::denies('rights-management-update')) {
            abort(403);
        }
        $selected_permission = $role->permissions->pluck('id')->toArray();
        // $nonSelected_permission=[];
        $permissions=Permission::where('parent_id',null)->get();
        // foreach ($permissions as $key=> $perm) {
        //     foreach ($selected_permission as $sel_perm) {
        //         if($sel_perm->id==$perm->id){
        //             unset($permissions[$key]);
        //         }
        //     }
        // }

        return view('admin.rights.add_roles',compact('role'),compact('permissions','selected_permission'));
    }

}
